<?php
session_start();
include("../../_conn/connection.php");

$student_id = $_GET['studentId'];
try {
    // Prepare and execute the update statement
    $verifyAccount = "UPDATE student_tbl SET can_login = 1 WHERE studentId = ?";
    $stmt = $conn->prepare($verifyAccount);
    $stmt->bind_param("i", $student_id);

    if (!$stmt->execute()) {
        $errorMessage = "Failed to verify the account " . $stmt->error;
        mysqli_rollback($conn);
        header("Location: be_studentacc.php?errorMsg=" . urlencode($errorMessage));
        exit;
    }

    // Close statement and connection
    $stmt->close();
    mysqli_close($conn);

    $_SESSION['verifiedSuccess'] = 'Account verified successfully';
    header('Location: be_studentacc.php');
    exit;
} catch (Exception $e) {
    // Redirect with error message
    header("Location: be_studentacc?errorMsg=" . urlencode($e->getMessage()));
    exit;
}
