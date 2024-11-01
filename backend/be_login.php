<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST["btnLogin"])) {
    session_start();
    include("../_conn/connection.php");

    $emailAddress = $_POST['inputEmailAddress'];
    $password = $_POST['inputPassword'];
    $captchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    $secretKey = "6LdwZG4qAAAAAOZNDEX-_4UW7gMMzpDMhCiz8bHM";
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse");
    $responseData = json_decode($verifyResponse);

    if ($responseData->success) {
        // Proceed with login if CAPTCHA is successful
        $sql = "SELECT * FROM student_tbl WHERE emailAddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $emailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($row["is_verified"] == 0 && empty($row["email_verification_token"])) {
                // Generate verification token and send email if not verified and token is empty
                require '../vendor/autoload.php';
                $verificationToken = bin2hex(random_bytes(16));

                // Save the verification token in the database
                $stmt = $conn->prepare("UPDATE student_tbl SET email_verification_token = ?, can_login = 0 WHERE emailAddress = ?");
                $stmt->bind_param("ss", $verificationToken, $emailAddress);
                $stmt->execute();

                // Send verification email
                $mail = new PHPMailer(true);
                $verifyLink = "http://localhost/EzDocs/verify.php?token=$verificationToken";

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'emailngmarabutit@gmail.com';
                    $mail->Password = 'nhxzrbquupanivte';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('ezdocs-support@localhost.com', 'EzDocs');
                    $mail->addAddress($emailAddress, $row["firstname"]);

                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification';
                    $mail->Body = "<p>Hi " . $row["firstname"] . ",</p><p>Please click the link below to verify your email address:</p><p><a href='$verifyLink'>Verify Email</a></p><p>If you did not request this, please ignore this email.</p>";

                    $mail->send();
                    header("Location: ../login.php?info=true&infoMsg=Please check your email to verify your account.");
                    exit();
                } catch (Exception $e) {
                    // Delete user if email fails to send
                    $stmt = $conn->prepare("DELETE FROM student_tbl WHERE emailAddress = ?");
                    $stmt->bind_param("s", $emailAddress);
                    $stmt->execute();
                    header("Location: ../login.php?error=true&errorMsg={$mail->ErrorInfo}");
                    exit();
                }
            } elseif ($row["is_verified"] == 1) {
                if ($row["can_login"] == 1) {
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['studentId'] = $row["studentId"];
                        $_SESSION['fullName'] = $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"] . " " . $row["suffix"];
                        $_SESSION['emailAddress'] = $row["emailAddress"];

                        header("Location: ../index.php");
                        exit();
                    } else {
                        header("Location: ../login.php?emailAddress=$emailAddress&error=true&errorMsg=Invalid password.");
                        exit();
                    }
                } else {
                    header("Location: ../login.php?emailAddress=$emailAddress&error=true&errorMsg=Login not permitted for this account.");
                    exit();
                }
            } else {
                header("Location: ../login.php?emailAddress=$emailAddress&error=true&errorMsg=Email not verified.");
                exit();
            }
        } else {
            header("Location: ../login.php?emailAddress=$emailAddress&error=true&errorMsg=Account not found.");
            exit();
        }
    } else {
        header("Location: ../login.php?emailAddress=$emailAddress&error=true&errorMsg=Captcha verification failed.");
        exit();
    }

    $stmt->close();
    $conn->close();
}
