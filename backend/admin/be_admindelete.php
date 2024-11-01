<?php
try {
    session_start();
    include("../../_conn/connection.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM ezdadmintbl WHERE id = $id";
        mysqli_query($conn, $sql);

        $_SESSION['success'] = 'Account deleted successfully';
        header("Location: be_adminaccounts.php");
        exit;
    } else {
        $_SESSION['error'] = 'Failed to delete account';
        header("Location: be_adminaccounts.php");
    }

    mysqli_close($conn);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>