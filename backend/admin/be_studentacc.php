<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .table-container {
            width: 80%;
            margin: 0 auto;
            margin-bottom: 20px;
        }

        .table-container th {
            background-color: #8db600;
            color: #FFFFFF;
        }

        .button-group {
            gap: 1px;
        }

        .button-group button {
            margin: 0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            margin-left: 0 !important;
        }

        div.dataTables_wrapper div.dataTables_length select {
            width: 70% !important;
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
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../../adminui/dashboard.php">Dashboard</a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="claimed_history.php">Claimed History</a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" id="btnLogout" type="button">Logout</a>
            </li>
        </ul>
    </nav>

    <?php
    try {
        include("../../_conn/connection.php");
        include("../../_includes/styles.php");
        include("../../_includes/scripts.php");

        $gradeLevels = range(7, 12);



        echo '<div class="container-fluid">'; // Ensure there's a container for proper alignment
        if ($_SESSION['adminType'] == 'super admin') {
            echo '<div class="d-flex justify-content-end mb-3 mt-3" style="margin-right: 9rem;">'; // Use Flexbox to align the button to the right
            echo '<a href="be_addstudentaccount.php" class="btn btn-success">Add Student</a>';
            echo '</div>';
        }

        foreach ($gradeLevels as $gradeLevel) {
            $sql = "SELECT studentId, CONCAT(firstname, ' ', middlename, ' ', lastname, ' ', suffix) AS fullname, phoneNumber, emailAddress, can_login, is_verified
                   FROM student_tbl WHERE gradeLevel = $gradeLevel ORDER BY lastname ASC, firstname ASC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '<hr style="width: 80%; margin: 0 auto;">'; // Center the HR
                echo '<div class="table-container mt-5">';
                echo '<h2>Grade ' . htmlspecialchars($gradeLevel) . '</h2>';
                echo '<table class="table table-hover" id="documentTableStudent' . htmlspecialchars($gradeLevel) . '">
                       <thead>
                           <tr>
                               <th>Student ID</th>
                               <th>Student Name</th>
                               <th>Phone Number</th>
                               <th>Email</th>
                               <th>Actions</th>
                           </tr>
                       </thead>
                       <tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                   <td>' . htmlspecialchars($row['studentId']) . '</td>
                   <td>' . htmlspecialchars($row['fullname']) . '</td>
                   <td>' . htmlspecialchars($row['phoneNumber']) . '</td>
                   <td>' . htmlspecialchars($row['emailAddress']) . '<br>';

                    $isVerified = isset($row['is_verified']) ? $row['is_verified'] : 0; // Default to 0 if not set
                    $badgeClass = $isVerified ? 'badge bg-success text-white' : 'badge bg-danger text-white'; // Class for badge color
                    $badgeText = $isVerified ? 'Verified' : 'Not Verified'; // Text for badge

                    // Render the badge for verification status directly under the email address
                    echo '<span class="' . $badgeClass . '">' . $badgeText . '</span>';

                    echo '</td>
                   <td style="text-align: center">
                       <div class="button-group">';

                    if ($isVerified) { // Check if the user can log in
                        echo '<button class="btn btn-outline-info" ' . ($row['can_login'] ? 'hidden' : '') . ' 
                                   onclick="location.href=\'be_verifyaccount.php?studentId=' . htmlspecialchars($row['studentId']) . '\'">
                                   Verify
                                 </button>';
                    }

                    echo '      <button class="btn btn-outline-primary" id="btnAccessAccount" 
                           data-id="' . htmlspecialchars($row['studentId']) . '" 
                           data-fullname="' . htmlspecialchars($row['fullname']) . '" 
                           data-phone="' . htmlspecialchars($row['phoneNumber']) . '" 
                           data-email="' . htmlspecialchars($row['emailAddress']) . '">View Account Details</button>
                   <form method="GET" action="../be_delete.php" style="display:inline;">
                       <input type="hidden" name="studentId" value="' . htmlspecialchars($row['studentId']) . '">
                       <button type="submit" class="btn btn-outline-danger" name="btnDelete">Delete</button>
                   </form>
                   </div>
               </td>
           </tr>';
                }
                echo '</tbody></table></div>';
            } else {
                echo '<div class="table-container mt-3"><p>No students found in grade ' . htmlspecialchars($gradeLevel) . '.</p></div>';
            }

            mysqli_free_result($result);
        }
        echo '</div>';

        mysqli_close($conn);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>

    <!-- DataTables Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Student Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Student ID:</strong> <span id="modalStudentId"></span></p>
                    <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                    <p><strong>Phone Number:</strong> <span id="modalPhone"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        });
    </script>
</body>

</html>