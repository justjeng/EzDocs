<?php
session_start();
include("../_conn/connection.php");
// Get and sanitize the studentId from the URL
$studentId = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;
$studentDetails = null;

if ($studentId) {
    // Query to retrieve student details
    $query = "SELECT id,studentLRN, fullName, gradelvl, reqDoc, reqDate, claimDate FROM ezdrequesttbl WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $studentId); // Bind $studentId as an integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch student data as an associative array
            $studentDetails = $result->fetch_assoc();
        }
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Document</title>
    <?php
    include("../_includes/styles.php");
    include("../_includes/scripts.php");
    ?>
</head>

<body>

    <nav class="flex flex-row items-center justify-between px-10 py-4 bg-emerald-900">
        <h1 class="font-bold text-[26px] text-white">EZDocs</h1>
        <ul class="flex flex-row gap-x-4 !p-0 !m-0 list-none">
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="dashboard.php">
                    Dashboard
                </a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../backend/admin/claimed_history.php">
                    Claimed History
                </a>
            </li>
            <li>
                <button class="block text-white text-[17px] font-regular hover:no-underline px-3" id="btnLogout">
                    Logout
                </button>
            </li>
        </ul>
    </nav>
    <div class="container pt-5">

        <form method="POST" class="shadow-md rounded p-3 w-full max-w-[500px] m-auto" action="../backend/admin/be_admineditreq.php">
            <h1 class="text-[32px] !text-left">Welcome to EzDocs</h1>
            <p class="text-[14px] text-gray-600 mb-4">
                Update student requested document.
            </p>
            <?php

            if (isset($_GET['errorMsg'])) {
                echo "<div class='py-4 px-2 !bg-red-200 !text-red-700 rounded mb-4'>
                        <p class='!m-0 text-center font-medium'>" . $_GET['errorMsg'] . "</p>
                      </div>";
            }

            ?>
            <input class="form-control" type="text" name="id"
                value="<?= $studentDetails['id']; ?>" readonly>
            <div class="grid grid-cols-2 gap-x-2">
                <div class="col-span-2 mb-2">
                    <label>Student ID No.</label>
                    <input class="form-control" type="text" name="studentLRN"
                        value="<?php echo htmlspecialchars($studentDetails['studentLRN'] ?? ''); ?>" readonly>
                </div>

                <div class="col-span-2 mb-2">
                    <label>Student Name</label>
                    <input class="form-control" type="text" name="studentName"
                        value="<?php echo htmlspecialchars(($studentDetails['fullName'] ?? '')); ?>" readonly>
                </div>

                <div class="col-span-2 mb-2">
                    <label>Grade Level</label>
                    <?= $studentDetails['gradelvl'] ?>
                    <select name="gradelvl" class="form-control">
                        <option disabled>-- Select option --</option>
                        <option value="Grade 7" <?= ($studentDetails['gradelvl'] == 'Grade 7' ? 'selected' : ''); ?>>Grade 7</option>
                        <option value="Grade 8" <?= ($studentDetails['gradelvl'] == 'Grade 8' ? 'selected' : ''); ?>>Grade 8</option>
                        <option value="Grade 9" <?= ($studentDetails['gradelvl'] == 'Grade 9' ? 'selected' : ''); ?>>Grade 9</option>
                        <option value="Grade 10" <?= ($studentDetails['gradelvl'] == 'Grade 10' ? 'selected' : ''); ?>>Grade 10</option>
                        <option value="Grade 11" <?= ($studentDetails['gradelvl'] == 'Grade 11' ? 'selected' : ''); ?>>Grade 11</option>
                        <option value="Grade 12" <?= ($studentDetails['gradelvl'] == 'Grade 12' ? 'selected' : ''); ?>>Grade 12</option>
                    </select>
                </div>
                <div class="col-span-2 mb-2">
                    <label>Document Request</label>
                    <select name="reqDoc" class="form-control">
                        <option disabled>-- Select option --</option>
                        <option value="Certificate of Enrollment" <?= ($studentDetails['reqDoc'] == 'Certificate of Enrollment' ? 'selected' : ''); ?>>Certificate of Enrollment</option>
                        <option value="Certificate of Good Moral" <?= ($studentDetails['reqDoc'] == 'Certificate of Good Moral' ? 'selected' : ''); ?>>Certificate of Good Moral</option>
                        <option value="Form 137" <?= ($studentDetails['reqDoc'] == 'Form 137' ? 'selected' : ''); ?>>Form 137 (SF10)</option>
                        <option value="Form 138" <?= ($studentDetails['reqDoc'] == 'Form 138' ? 'selected' : ''); ?>>Form 138 (SF9)</option>
                        <option value="Diploma" <?= ($studentDetails['reqDoc'] == 'Diploma' ? 'selected' : ''); ?>>Diploma</option>
                    </select>
                </div>

                <div class="col-span-2 mb-2">
                    <label>Request Date</label>
                    <input class="form-control" type="date" name="reqDate" value="<?= $studentDetails['reqDate'] ?>" readonly>
                </div>
                <!-- <div class="col-span-2 mb-2">
                    <label>Claim Date</label>
                    <input class="form-control" type="date" name="claimDate" value="<?= $studentDetails['claimDate'] ?>">
                </div> -->
            </div>

            <button class="btn btn-primary py-2 mt-2 w-full" type="submit" name="btneditdoc">Save</button>

        </form>
    </div>

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
                    window.location.href = "backend/be_logout.php";
                }
            });
        });
    </script>
</body>

</html>