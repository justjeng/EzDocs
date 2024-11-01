<?php
session_start();
try {
    include("../../_conn/connection.php");

    if (isset($_GET['studentLRN'])) {
        $studentLRN = $_GET['studentLRN'];

        // Delete student requests from request_tbl
        $sql = "DELETE FROM ezdrequesttbl WHERE studentLRN = $studentLRN";
        mysqli_query($conn, $sql);

        // Redirect back to student_info.php
        $_SESSION['msgSuccess'] = "Request successfully deleted";
        header("Location: ../../adminui/dashboard.php");
        exit;
    } else {
        echo "Error: No student ID provided.";
    }

    mysqli_close($conn);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>