<?php
session_start();
include("_conn/connection.php"); // Ensure this file initializes $conn
// Use PHPMailer to send the email
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the 'emailAddress' is set in $_POST
if (isset($_POST['emailAddress'])) {
    $email = $_POST['emailAddress'];

    if (empty($email)) {
        $_SESSION['error'] = "No Email Address provided.";
        header("Location: forgot_password.php");
        exit();
    }
    // Verify the $conn connection
    if (!$conn) {
        die("Database connection failed.");
    }

    // Check if the email address exists in the database
    $query = "SELECT * FROM student_tbl WHERE emailAddress = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $student_id = $student['id'];
        $token = bin2hex(random_bytes(16));
        $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $query = "INSERT INTO password_recovery (student_id, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $student_id, $token, $expires_at);
        $stmt->execute();


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

            $mail->setFrom('admin@gmail.com', 'EzDocs');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Recovery';
            $mail->Body = "Click the link to reset your password: <a href='http://localhost/EzDocs/reset_password.php?token=$token'>Reset Password</a>";

            $mail->send();

            $_SESSION['success'] = "Password reset link has been sent to " . $email;
            header("Location: forgot_password.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Password reset link could not be sent.";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Account cannot be found.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    $_SESSION['error'] = "No Email Address provided.";
    header("Location: forgot_password.php");
    exit();
}
