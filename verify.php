<?php
include("_conn/connection.php"); // Database connection

$script = ''; // Initialize a variable to hold the script

if (isset($_GET['token'])) {
    $verificationToken = $_GET['token'];

    // Query to find a matching token
    $stmt = $conn->prepare("SELECT id FROM student_tbl WHERE email_verification_token = ? AND is_verified = 0");
    $stmt->bind_param("s", $verificationToken);
    $stmt->execute();
    $stmt->store_result();

    // Check if we found a user with this token and is not verified yet
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId);
        $stmt->fetch();

        // Update to mark the user as verified
        $updateStmt = $conn->prepare("UPDATE student_tbl SET is_verified = 1, email_verification_token = NULL, email_verified_at = now() WHERE id = ?");
        $updateStmt->bind_param("i", $userId);

        if ($updateStmt->execute()) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your email has been successfully verified. Wait for the verification of account.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Verification failed. Please try again later.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'registration.php';
                });
            ";
        }

        $updateStmt->close();
    } else {
        // Token not found or already verified
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Invalid Token!',
                text: 'Invalid or expired verification link.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'registration.php';
            });
        ";
    }

    $stmt->close();
    $conn->close();
} else {
    // No token provided in the URL
    $script = "
        Swal.fire({
            icon: 'warning',
            title: 'No Token!',
            text: 'No verification token provided.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '../registration.php';
        });
    ";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    include("_includes/styles.php");
    include("_includes/scripts.php");
    ?>
</head>

<body style="background-color: #004a3a;">
    <script>
        <?php echo $script; // Output the SweetAlert script here ?>
    </script>

    <!-- Your body content -->
</body>

</html>
