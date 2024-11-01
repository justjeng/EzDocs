<?php
session_start();
include("../../_conn/connection.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../../adminui/dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $adminType = mysqli_real_escape_string($conn, $_POST['adminType']);

    if ($password == $confirm_password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $insertSql = "INSERT INTO ezdadmintbl (name, email, password, adminType) 
                      VALUES ('$name', '$email', '$hashedPassword', '$adminType')";

        if (mysqli_query($conn, $insertSql)) {  // Change $updateSql to $insertSql here
            $_SESSION['success'] = "Account created successfully!";
            header("Location: be_adminaccountadd.php");
            exit();
        } else {
            $_SESSION['error'] = "Error creating account: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Passwords do not match.";
    }
    mysqli_close($conn);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    include("../../_includes/styles.php");
    include("../../_includes/scripts.php");
    ?>
    <style>
        .container {
            position: relative;
            z-index: 2;
            overflow-y: auto;
            /* Add this property */
            padding: 10px;
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
    <?php if (isset($_SESSION['verifiedSuccess'])) { ?>
        <script>
            Swal.fire(
                'Verified!',
                `Account verified successfully.`,
                'success'
            )
        </script>
    <?php unset($_SESSION['verifiedSuccess']);
    } ?>
    <nav class="flex flex-row items-center justify-between px-10 py-4 bg-emerald-900">
        <h1 class="font-bold text-[26px] text-white">EZDocs</h1>
        <ul class="flex flex-row gap-x-4 !p-0 !m-0 list-none">
            <?php if ($_SESSION['adminType'] == 'super admin') { ?>
                <li>
                    <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="be_adminaccounts.php">
                        Admin Accounts
                    </a>
                </li>
                <li>
                    <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="be_dataanalytics.php">
                        Data Analytics
                    </a>
                </li>
            <?php } ?>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="be_studentacc.php">
                    Student
                </a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../../adminui/dashboard.php">Dashboard</a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="claimed_history.php">Claimed History</a>
            </li>
            <li>
                <button class="block text-white text-[17px] font-regular hover:no-underline px-3" id="btnLogout">
                    Logout
                </button>
            </li>
        </ul>
    </nav>

    <div class="container-fluid flex flex-col items-center justify-center w-full mt-3 mb-3">

        <div class="form-container shadow-lg">

            <div class="mb-4">
                <h1 class="text-[32px] !text-left">Add Admin Account</h1>
                <p class="text-[14px] text-gray-600">
                Kindly fill up the form to create a admin account.
                </p>
            </div>

            <?php

            if (isset($_GET['errorMsg'])) {
                echo "<div class='py-4 px-2 !bg-red-200 !text-red-700 rounded mb-4'>
                        <p class='!m-0 text-center font-medium'>" . $_GET['errorMsg'] . "</p>
                      </div>";
            }

            ?>
            <?php if (isset($_SESSION['success'])) { ?>

                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '<?= $_SESSION['success'] ?>',
                        confirmButtonText: 'OK'
                    });
                </script>


                <?php
                unset($_SESSION['success']);
                unset($_SESSION['formData']); ?>

            <?php } else if (isset($_SESSION['error'])) { ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '<?= $_SESSION['error'] ?>',
                        confirmButtonText: 'OK'
                    });
                </script>

                <?php
                unset($_SESSION['error']);
                unset($_SESSION['formData']); ?>

            <?php } ?>
            <form class="w-full max-w-[650px]" method="POST" action="">

                <p class="text-[14px] font-medium text-sky-600 mt-4 mb-2">Account Details</p>
                <div class="grid grid-cols-2 gap-x-3">

                    <input type="hidden" class="form-control" id="id" aria-describedby="id"
                        name="name" required>

                    <div class="mb-3 col-span-2">
                        <label for="inputFirstname" class="form-label">Name</label>
                        <input type="text" class="form-control" id="id" aria-describedby="id"
                            name="name" required>
                    </div>
                    <div class="mb-3 col-span-2">
                        <label for="inputGradeLevel" class="form-label">Admin Type</label>
                        <select class="form-control" name="adminType" id="inputGradeLevel" required>
                            <option disabled selected></option>
                            <option value="admin">Admin</option>
                            <option value="super admin">Super Admin</option>
                        </select>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-x-3">
                    <div class="mb-3 col-span-2">
                        <label for="inputEmailAddress" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="inputEmailAddress" aria-describedby="emailAddress"
                            name="email" required>
                    </div>
                  
                    <div class="mb-3 col-span-1">
                        <label for="inputPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="inputPassword" name="password" required>
                    </div>

                    <div class="mb-3 col-span-1">
                        <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" required>
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
                <button type="submit" class="btn btn-primary w-full py-2 mt-2" name="btnUpdateAccount">Add Account</button>
                <a class="btn btn-danger py-2 mt-2 w-full text-center block" href="be_adminaccounts.php">Cancel</a>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            <?php foreach ($gradeLevels as $gradeLevel) { ?>
                $('#documentTableStudent<?php echo $gradeLevel; ?>').DataTable();
            <?php } ?>

            $('.button-group button[name="btnDelete"]').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "DELETE STUDENT ACCOUNT",
                    text: "Are you sure you want to delete this student account and record?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../be_delete.php?studentId=" + $(this).closest('form').find('input[name="studentId"]').val();
                    }
                });
            });

            $(document).on('click', '#btnAccessAccount', function() {
                $('#modalStudentId').text($(this).data('id'));
                $('#modalFullName').text($(this).data('fullname'));
                $('#modalPhone').text($(this).data('phone'));
                $('#modalEmail').text($(this).data('email'));
                $('#studentModal').modal('show');
            });
        });
    </script>

    <script>
        $('#btnLogout').click(function(e) {
            Swal.fire({
                title: "SIGN OUT",
                text: "Are you sure you want to logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "be_adminlogout.php";
                }
            });
        });
    </script>
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