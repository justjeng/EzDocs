<?php
session_start();
include_once("_conn/connection.php");

if (!isset($_SESSION['studentId'])) {
    header("Location: index.php");
    exit();
}

$studentId = $_SESSION['studentId'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $emailAddress = mysqli_real_escape_string($conn, $_POST['emailAddress']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $gradeLevel = mysqli_real_escape_string($conn, $_POST['gradeLevel']);

    // Handle file upload
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        $photoName = $_FILES['profilePhoto']['name'];
        $photoTmpName = $_FILES['profilePhoto']['tmp_name'];
        $photoSize = $_FILES['profilePhoto']['size'];
        $photoError = $_FILES['profilePhoto']['error'];
        $photoType = $_FILES['profilePhoto']['type'];

        // Check file type and size (e.g., max 2MB)
        $allowed = ['jpg', 'jpeg', 'png'];
        $fileExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowed) && $photoSize <= 2 * 1024 * 1024) {
            $newPhotoName = "profile_" . $studentId . "." . $fileExt;
            $photoPath = "uploads/" . $newPhotoName;

            if (move_uploaded_file($photoTmpName, $photoPath)) {
                // Save the path to the database
                $profilePhotoSql = ", profile_photo='$photoPath'";
            } else {
                $_SESSION['error'] = "Error uploading the file.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type or size.";
        }
    } else {
        $profilePhotoSql = ""; // No photo update
    }

    // Update the database
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $updateSql = "UPDATE student_tbl SET 
                        firstname='$firstname',
                        middlename='$middlename',
                        lastname='$lastname',
                        suffix='$suffix',
                        phoneNumber='$phoneNumber',
                        emailAddress='$emailAddress',
                        password='$hashedPassword',
                        gradeLevel='$gradeLevel'
                        $profilePhotoSql
                      WHERE studentId='$studentId'";
    } else {
        $updateSql = "UPDATE student_tbl SET 
                        firstname='$firstname',
                        middlename='$middlename',
                        lastname='$lastname',
                        suffix='$suffix',
                        phoneNumber='$phoneNumber',
                        emailAddress='$emailAddress',
                        gradeLevel='$gradeLevel'
                        $profilePhotoSql
                      WHERE studentId='$studentId'";
    }

    if (mysqli_query($conn, $updateSql)) {
        $_SESSION['success'] = "Information updated successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating information: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

$studentSql = "SELECT * FROM student_tbl WHERE studentId='$studentId'";
$result = mysqli_query($conn, $studentSql);
$student = mysqli_fetch_assoc($result);
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Information</title>
    <?php include("_includes/styles.php"); ?>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
        }

        form input,
        form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #FFC107;
            color: white;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #FFA07A;
        }

        .box {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }

        /* Header Styles */
        nav {
            background-color: #8BC34A;
            /* Mint green */
            padding: 20px;
            text-align: center;
        }

        nav h1 {
            color: #FFFFFF;
            font-size: 24px;
            margin-bottom: 10px;
        }


        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        nav li {
            margin-right: 20px;
        }

        nav a {
            color: #FFFFFF;
            text-decoration: none;
        }


        /* Desktop navbar styles */
        .desktop-nav {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2.5rem;
            background-color: #064e3b;
        }

        /* Mobile navbar styles */
        .mobile-nav {
            display: none;
            flex-direction: column;
            background-color: #064e3b;
            padding: 1rem;
        }

        .mobile-nav h1 {
            font-size: 26px;
            color: white;
            font-weight: bold;
        }

        .mobile-nav ul {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }

        .mobile-nav li {
            margin: 0.5rem 0;
        }

        .mobile-nav a,
        .mobile-nav button {
            color: white;
            text-decoration: none;
            font-size: 17px;
            font-weight: normal;
            padding: 0.5rem;
            display: block;
        }

        .mobile-nav a:hover,
        .mobile-nav button:hover {
            text-decoration: none;
        }

        /* Show mobile nav on smaller screens */
        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }

            .mobile-nav {
                display: flex;
            }
        }

        /* Show desktop nav on larger screens */
        @media (min-width: 769px) {
            .desktop-nav {
                display: flex;
            }

            .mobile-nav {
                display: none;
            }
        }
    </style>
</head>
<?php
include("_includes/styles.php");
include("_includes/scripts.php");
?>

<body>
    <!-- Desktop Navigation -->
    <nav class="desktop-nav px-10 py-4 bg-emerald-900">
        <h1 class="font-bold text-[26px] text-white">EZDocs</h1>
        <ul class="flex flex-row gap-x-4 !p-0 !m-0 list-none">
            <li>
                <a class="block text-white text-sm md:text-base font-regular hover:no-underline px-3" href="index.php">
                    Dashboard
                </a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="claim/claim_history.php">
                    Claimed History
                </a>
            </li>
            <li>
                <button class="block text-white text-[17px] font-regular hover:no-underline px-3 btnLogout" id="btnLogout">
                    Logout
                </button>
            </li>
        </ul>
    </nav>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <h1><u>EZDocs</u></h1>
        <ul>
            <li>
                <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="claim/claim_history.php">Claimed History</a>
            </li>
            <li>
                <button class="btnLogout">Logout</button>
            </li>
        </ul>
    </nav>


    <div class="container">
        <h1>Edit Your Information</h1>
        <?php
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <div class="box">
            <form method="POST" action=""  enctype="multipart/form-data">

                <label for="profilePhoto">Profile Photo:</label>
                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*">

                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" required>

                <label for="middlename">Middle Name:</label>
                <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($student['middlename']); ?>">

                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" required>

                <label for="suffix">Suffix:</label>
                <input type="text" id="suffix" name="suffix" value="<?php echo htmlspecialchars($student['suffix']); ?>">

                <label for="phoneNumber">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($student['phoneNumber']); ?>" required>

                <label for="emailAddress">Email Address:</label>
                <input type="email" id="emailAddress" name="emailAddress" value="<?php echo htmlspecialchars($student['emailAddress']); ?>" required>

                <label for="gradeLevel">Grade Level:</label>
                <select id="gradeLevel" name="gradeLevel" required>
                    <?php
                    for ($i = 7; $i <= 12; $i++) {
                        $selected = $student['gradeLevel'] == $i ? 'selected' : '';
                        echo "<option value='$i' $selected>Grade $i</option>";
                    }
                    ?>
                </select>

                <label for="password">New Password (leave blank if not changing):</label>
                <input type="password" id="password" name="password">

                <input type="submit" value="Update Information">
            </form>
        </div>
    </div>

    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.min.js"></script>
    <script>
        $('.btnLogout').click(function(e) {
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
                    window.location.href = "backend/be_logout.php";
                }
            });
        });
    </script>
</body>

</html>