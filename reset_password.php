<?php
session_start();
include("_conn/connection.php");
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query to find the entry with the token
    $query = "SELECT * FROM password_recovery WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result); // Fetch data as an associative array
        $student_id = $row['student_id']; // Get student_id from the result

        if (isset($_POST['submit'])) {
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($newPassword == $confirmPassword) {
                // Securely hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the student_tbl with the new password
                $query = "UPDATE student_tbl SET password = '$hashedPassword' WHERE id = '$student_id'";
                mysqli_query($conn, $query);

                $_SESSION['success'] = "Password reset successfully!";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Password do not match!";
                header("Location: reset_password.php?token=$token");
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "Invalid token";
        header("Location: forgot_password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Includes -->
    <?php 
    
    include("_includes/styles.php");
    include("_includes/scripts.php");
    ?>

    <link rel="stylesheet" href="css/_global.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #004a3a;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .container {
            position: relative;
            z-index: 2;
            overflow-y: auto;
            /* Add this property */
            padding: 20px;
            /* Adjust the padding value */
            max-width: 800px;
            /* Add a max-width to prevent the form from taking up the full screen */
            margin: 40px auto;
            /* Adjust the margin value */
        }

        .form-container {
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
            background-color: white;
            opacity: 95%;
        }

        .form-label {
            color: black;
        }

        .text-white {
            color: black;
        }

        .requirements {
            font-size: 0.9em;
            margin-top: 10px;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .requirement span {
            margin-right: 10px;
            font-size: 1.2em;
            /* Adjust the size as needed */
        }

        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['success'])) { ?>

        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                toast: true,
                position: 'center',
                iconColor: 'green',
                customClass: {
                    popup: 'colored-toast',
                },
                customClass: 'swal-wide',
                text: '<?= $_SESSION['success'] ?>',
                confirmButtonText: 'OK'
            });
        </script>


        <?php
        unset($_SESSION['success']); ?>

    <?php } else if (isset($_SESSION['error'])) { ?>

        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                toast: true,
                position: 'center',
                iconColor: 'red',
                customClass: 'swal-wide',
                customClass: {
                    popup: 'colored-toast',
                },
                text: '<?= $_SESSION['error'] ?>',
                confirmButtonText: 'OK'
            });
        </script>

    <?php
        unset($_SESSION['error']);
    } ?>
    <div class="container flex flex-col items-center justify-center w-full">

        <div class="form-container shadow-lg">

            <div class="mb-4">
                <h1 class="text-[32px] !text-left">Reset Password</h1>

            </div>

            <?php

            if (isset($_GET['errorMsg'])) {
                echo "<div class='py-4 px-2 !bg-red-200 !text-red-700 rounded mb-4'>
                        <p class='!m-0 text-center font-medium'>" . $_GET['errorMsg'] . "</p>
                      </div>";
            }

            ?>

            <form class="w-full max-w-[650px]" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?token=' . $token; ?>">
                <div class="grid grid-cols-2 gap-x-3">
                    <input type="hidden" value="<?= $student_id ?>" readonly>
                    <div class="mb-3 col-span-4 sm:col-span-1">
                        <label for="newPassword">Enter new password:</label>
                        <input type="password" id="inputPassword" class="form-control" name="newPassword" required>
                    </div>

                    <div class="mb-3 col-span-4 sm:col-span-1">
                        <label for="inputConfirmPassword" class="form-label">Confirm new password:</label>
                        <input type="password" id="inputConfirmPassword" name="confirmPassword"  class="form-control" required>
                    </div>
                </div>

                <div class="requirements">
                    <div class="requirement">
                        <span id="length-icon" class="icon">✘</span> At least 8 characters long
                    </div>
                    <div class="requirement">
                        <span id="uppercase-icon" class="icon">✘</span> Contains at least one uppercase letter (A-Z)
                    </div>
                    <div class="requirement">
                        <span id="lowercase-icon" class="icon">✘</span> Contains at least one lowercase letter (a-z)
                    </div>
                    <div class="requirement">
                        <span id="number-icon" class="icon">✘</span> Contains at least one number (0-9)
                    </div>
                    <div class="requirement">
                        <span id="special-icon" class="icon">✘</span> Contains at least one special character (e.g., @#$%^&+=_!)
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full py-2 mt-2" name="submit">Reset password</button>
            </form>
        </div>
    </div>


    <script>
        document.getElementById('inputPhoneNumber').addEventListener('input', function() {
            let value = this.value;

            // Remove all non-digit characters
            value = value.replace(/\D/g, '');

            // Ensure the value starts with 63
            if (!value.startsWith('63')) {
                value = '63' + value;
            }

            // Limit the length to 11 characters
            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            this.value = value;
        });
    </script>

    <script>
        document.getElementById('inputPassword').addEventListener('input', function() {
            const password = this.value;

            // Length requirement
            const lengthValid = password.length >= 8;
            document.getElementById('length-icon').textContent = lengthValid ? '✔' : '✘';
            document.getElementById('length-icon').className = lengthValid ? 'icon valid' : 'icon invalid';

            // Uppercase requirement
            const uppercaseValid = /[A-Z]/.test(password);
            document.getElementById('uppercase-icon').textContent = uppercaseValid ? '✔' : '✘';
            document.getElementById('uppercase-icon').className = uppercaseValid ? 'icon valid' : 'icon invalid';

            // Lowercase requirement
            const lowercaseValid = /[a-z]/.test(password);
            document.getElementById('lowercase-icon').textContent = lowercaseValid ? '✔' : '✘';
            document.getElementById('lowercase-icon').className = lowercaseValid ? 'icon valid' : 'icon invalid';

            // Number requirement
            const numberValid = /[0-9]/.test(password);
            document.getElementById('number-icon').textContent = numberValid ? '✔' : '✘';
            document.getElementById('number-icon').className = numberValid ? 'icon valid' : 'icon invalid';

            // Special character requirement
            const specialCharValid = /[@#$%^&+=_!]/.test(password);
            document.getElementById('special-icon').textContent = specialCharValid ? '✔' : '✘';
            document.getElementById('special-icon').className = specialCharValid ? 'icon valid' : 'icon invalid';
        });
    </script>

</body>

</html>