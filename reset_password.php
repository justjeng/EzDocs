<?php
include("_conn/connection.php");
include("_includes/styles.php");
include("_includes/scripts.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query to find the entry with the token
    $query = "SELECT * FROM password_recovery WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result); // Fetch data as an associative array
        $student_id = $row['student_id']; // Get student_id from the result
        
        ?>
        <div class="container flex flex-col items-center justify-center w-full">
            <div class="form-container shadow-lg">
                <h1 class="text-[32px] !text-left text-black">Reset Password</h1>
                <p class="text-[14px] text-black-300">
                    Please enter your new password.
                </p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?token=' . $token; ?>">
                    <input type="text" value="<?= $student_id ?>" readonly>
                    <label for="newPassword">Enter new password:</label>
                    <input type="password" name="newPassword" required>
                    <label for="confirmPassword">Confirm new password:</label>
                    <input type="password" name="confirmPassword" required>
                    <button type="submit" name="submit">Reset password</button>
                </form>
            </div>
        </div>

        <?php
        if (isset($_POST['submit'])) {
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($newPassword == $confirmPassword) {
                // Securely hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the student_tbl with the new password
                $query = "UPDATE student_tbl SET password = '$hashedPassword' WHERE id = '$student_id'";
                mysqli_query($conn, $query);

                echo "Password reset successfully!";
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid token.";
    }
}
?>
