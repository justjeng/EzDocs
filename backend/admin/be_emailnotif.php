<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Adjust path as needed

session_start();

if (isset($_POST["btnsend"])) {
    try {
        include("../../_conn/connection.php");

        // Get Data and sanitize inputs
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $message = mysqli_real_escape_string($conn, $_POST["message"]);

        // Fetch student data using email
        $sql = "SELECT s.studentId, s.firstname, s.lastname, r.reqDoc 
                FROM student_tbl s 
                JOIN ezdrequesttbl r ON s.studentId = r.studentLRN 
                WHERE s.emailAddress = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch associative array of the student data
            $studentData = $result->fetch_assoc();

            $studentId = $studentData['studentId'];
            $fullName = $studentData['firstname'] . ' ' . $studentData['lastname'];
            $reqDoc = $studentData['reqDoc'];

            // Initialize PHPMailer
            $mail = new PHPMailer(true);

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
                $mail->addAddress($email, $fullName); // Use $email instead of $emailAddress

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Document Ready for Claiming';
                $mail->Body = "<p>Hi $fullName,</p>
                               <p>Your requested document, <strong>$reqDoc</strong>, is ready for claiming.</p>
                               
                               <p>Message from Admin: $message</p>
                               <p>Please visit our office to claim it at your earliest convenience.</p>
                               <p>Thank you,</p>
                               <p>EzDocs Support</p>";

                $mail->send();
                $_SESSION['msgSuccess'] = "Email sent successfully.";
                header("Location: ../../adminui/dashboard.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['msgError'] = "Failed to send email. Error: {$mail->ErrorInfo}";
                header("Location: ../../adminui/dashboard.php");
                exit();
            }
        } else {
            // No student found with the provided email
            $_SESSION['msgError'] = "No student found with this email.";
            header("Location: ../../adminui/admin_msgreq.php");
            exit();
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: ../../adminui/admin_msgreq.php?emailAddress=$email");
        exit();
    }
}
