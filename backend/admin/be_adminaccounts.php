<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Accounts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
    <?php if (isset($_SESSION['success'])) { ?>
        <script>
            Swal.fire(
                'Success!',
                `<?= $_SESSION['success'] ?>`,
                'success'
            );
        </script>
    <?php unset($_SESSION['success']);
    } ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <script>
            Swal.fire(
                'Error!',
                `<?= $_SESSION['error'] ?>`,
                'error'
            );
        </script>
    <?php unset($_SESSION['error']);
    } ?>


    <nav class="flex flex-row items-center justify-between px-10 py-4 bg-emerald-900">
        <h1 class="font-bold text-[26px] text-white">EZDocs</h1>
        <ul class="flex flex-row gap-x-4 !p-0 !m-0 list-none">
            <?php if ($_SESSION['adminType'] == 'super admin') { ?>
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
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="be_studentacc.php">
                    Student
                </a>
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

    <?php
    try {
        include("../../_conn/connection.php");
        include("../../_includes/styles.php");
        include("../../_includes/scripts.php");

        echo '<div class="container-fluid">'; // Ensure there's a container for proper alignment
        if ($_SESSION['adminType'] == 'super admin') {
            echo '<div class="d-flex justify-content-end mb-3 mt-3" style="margin-right: 9rem;">'; // Use Flexbox to align the button to the right
            echo '<a href="be_adminaccountadd.php" class="btn btn-success">Add Account</a>';
            echo '</div>';
        }

        $sql = "SELECT * FROM ezdadmintbl ORDER BY adminType DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<hr style="width: 80%; margin: 0 auto;">'; // Center the HR
            echo '<div class="table-container mt-5">';
            echo '<h2>Admin Accounts</h2>';
            echo '<table class="table table-hover" id="documentTableAdmin">
                       <thead>
                           <tr>
                               <th>ID</th>
                               <th>Name</th>
                               <th>Email</th>
                               <th>Admin Type</th>
                               <th>Actions</th>
                           </tr>
                       </thead>
                       <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                   <td>' . htmlspecialchars($row['id']) . '</td>
                   <td>' . htmlspecialchars($row['name']) . '</td>
                   <td>' . htmlspecialchars($row['email']) . '</td>
                   <td>' . htmlspecialchars($row['adminType']) . '<br>';
                echo '</td>
                   <td style="text-align: center">
                        <div class="button-group">
                            <a href="be_adminaccountedit.php?id=' . $row['id'] . '" class="btn btn-outline-info">Edit</a>
                           <a href="#" class="btn btn-outline-danger btnDelete" data-id="' . $row['id'] . '">Delete</a>
                        </div>
                    </td>';
            }
            echo '</tbody></table></div>';
        } else {
            echo '<div class="table-container mt-3"><p>No students found in grade ' . htmlspecialchars($gradeLevel) . '.</p></div>';
        }

        mysqli_free_result($result);
        echo '</div>';
        mysqli_close($conn);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#documentTableAdmin').DataTable();

            $(document).on('click', '.btnDelete', function(e) {
                e.preventDefault(); // Prevent default button action

                const adminId = $(this).data('id'); // Get the admin ID from the data-id attribute

                Swal.fire({
                    title: "DELETE ADMIN ACCOUNT",
                    text: "Are you sure you want to delete this admin account?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the delete action with the ID
                        window.location.href = 'be_admindelete.php?id=' + adminId + '&btnDelete=true';
                    }
                });
            });


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
    </script>
</body>

</html>