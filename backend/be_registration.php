<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path as needed

session_start(); // Start the session at the beginning

if (isset($_POST["btnCreateAccount"])) {
    try {
        include("../_conn/connection.php");

        // Get Data and sanitize inputs
        $studentId = mysqli_real_escape_string($conn, $_POST["inputStudentId"]);
        $firstname = mysqli_real_escape_string($conn, $_POST["inputFirstname"]);
        $middlename = mysqli_real_escape_string($conn, $_POST["inputMiddlename"]);
        $lastname = mysqli_real_escape_string($conn, $_POST["inputLastname"]);
        $suffix = mysqli_real_escape_string($conn, $_POST["inputSuffix"]);
        $parent = mysqli_real_escape_string($conn, $_POST["inputParent"]);
        $gradeLevel = mysqli_real_escape_string($conn, $_POST["inputGradeLevel"]);
        $phoneNumber = mysqli_real_escape_string($conn, $_POST["inputPhoneNumber"]);
        $emailAddress = mysqli_real_escape_string($conn, $_POST["inputEmailAddress"]);
        $password = $_POST["inputPassword"];
        $confirmPassword = $_POST["inputConfirmPassword"];
        $recaptchaResponse = $_POST['g-recaptcha-response']; // Get the reCAPTCHA response

        // Validate reCAPTCHA
        $secretKey = "6LdwZG4qAAAAAOZNDEX-_4UW7gMMzpDMhCiz8bHM"; // Replace with your secret key
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
        $responseKeys = json_decode($response, true);

        if (!$responseKeys["success"]) {
            $_SESSION['error'] = "Please complete the reCAPTCHA verification.";
            header("Location: ../registration.php");
            exit;
        }

        // Validate inputs
        $errorMsg = '';
        $regexValidPassword = "/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[@#$%^&+=_!]).{8,16}$/";

        if (!preg_match($regexValidPassword, $password)) {
            $errorMsg = "Password is too weak. Please change your password.";
        } elseif ($password !== $confirmPassword) {
            $errorMsg = "Password does not match.";
        }

        if ($errorMsg) {
            // Store error in session and redirect
            $_SESSION['errorMsg'] = $errorMsg;
            $_SESSION['formData'] = [
                'studentId' => $studentId,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
                'suffix' => $suffix,
                'parent' => $parent,
                'gradeLevel' => $gradeLevel,
                'phoneNumber' => $phoneNumber,
                'emailAddress' => $emailAddress,
            ];
            header("Location: ../registration.php");
            exit;
        }

        // Hash the password
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        // Generate verification token
        $verificationToken = bin2hex(random_bytes(16));

        // Prepare SQL query
        $stmt = $conn->prepare("INSERT INTO student_tbl (studentId, firstname, middlename, lastname, suffix, parent, gradeLevel, phoneNumber, emailAddress, password, email_verification_token, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssssssssss", $studentId, $firstname, $middlename, $lastname, $suffix, $parent, $gradeLevel, $phoneNumber, $emailAddress, $hashPassword, $verificationToken);

        if ($stmt->execute()) {
            // Send verification email with PHPMailer
            $mail = new PHPMailer(true);
            $verifyLink = "http://localhost/EzDocs/verify.php?token=$verificationToken";

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'emailngmarabutit@gmail.com'; // SMTP username
                $mail->Password = 'nhxzrbquupanivte'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('ezdocs-support@localhost.com', 'EzDocs');
                $mail->addAddress($emailAddress, $firstname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $mail->Body = "<p>Hi $firstname,</p><p>Please click the link below to verify your email address:</p><p><a href='$verifyLink'>Verify Email</a></p><p>If you did not request this, please ignore this email.</p>";

                $mail->send();
                $_SESSION['success'] = "Account successfully created. Please check your email to verify your account.";
                header("Location: ../registration.php");
            } catch (Exception $e) {
                // Delete user if email fails to send
                $stmt = $conn->prepare("DELETE FROM student_tbl WHERE emailAddress = ?");
                $stmt->bind_param("s", $emailAddress);
                $stmt->execute();
                $errorMsg = "Failed to send verification email. Error: {$mail->ErrorInfo}";
                $_SESSION['error'] = $errorMsg;
                header("Location: ../registration.php");
            }
        } else {
            $_SESSION['error'] = "Failed to create account. Please try again later.";
            header("Location: ../registration.php");
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        $_SESSION['errorMsg'] = $e->getMessage();
        header("Location: ../registration.php");
    }
}
?>
