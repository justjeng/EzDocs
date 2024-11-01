<?php
$errorMessage = "";

try {
    session_start();
    include("../../_conn/connection.php");

    if (isset($_POST['btneditdoc'])) {
        $studentid = $_POST['studentLRN'];
        $studentname = $_POST['studentName'];
        $gradelev = $_POST['gradelvl'];
        $docreq = $_POST['reqDoc'];
        // $datereq = $_POST['reqDate'];
        // $claimdate = $_POST['claimDate'];
        // Start transaction
        mysqli_autocommit($conn, FALSE);

        // Prepare and execute the update statement
        $editRequestSql = "UPDATE ezdrequesttbl SET fullName = ?, gradelvl = ?, reqDoc = ? WHERE studentLRN = ?";
        $stmt = $conn->prepare($editRequestSql);
        $stmt->bind_param("ssss", $studentname, $gradelev, $docreq, $studentid);

        if (!$stmt->execute()) {
            $errorMessage = "Failed to update the document request: " . $stmt->error;
            mysqli_rollback($conn);
            header("Location: ../../adminui/adminedit.php?errorMsg=" . urlencode($errorMessage));
            exit;
        }

        // Commit transaction
        if (!mysqli_commit($conn)) {
            $errorMessage = "Transaction commit failed";
            mysqli_rollback($conn);
            header("Location: ../../adminui/adminedit.php?errorMsg=" . urlencode($errorMessage));
            exit;
        }

        // Close statement and connection
        $stmt->close();
        mysqli_close($conn);

        $_SESSION['updateSuccess'] = 'Request updated successfully';
        header('Location: ../../adminui/dashboard.php');
        exit;
    }
} catch (Exception $e) {
    // Redirect with error message
    header("Location: ../../adminui/admineditreq.php?errorMsg=" . urlencode($e->getMessage()));
    exit;
}
