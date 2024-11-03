<?php
session_start();
include("../../_conn/connection.php");
include("../../_includes/styles.php");
include("../../_includes/scripts.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claimed Documents</title>
    <style>
        .table-container {
            width: 80%;
            margin: 0 auto;
            margin-bottom: 20px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
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
    </style>
</head>

<body>

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
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../../adminui/dashboard.php">
                    Dashboard
                </a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="be_studentacc.php">
                    Student
                </a>
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
        $claimedQuery = "SELECT * FROM ezdrequesttbl WHERE status = 'claimed'";
        $claimedResult = mysqli_query($conn, $claimedQuery);

        if (!$claimedResult) {
            die("Query failed: " . mysqli_error($conn));
        }


        $numRows = mysqli_num_rows($claimedResult);
        echo '<div class="flex flex-col items-center mt-2 mb-1">
                <p class="font-bold text-[26px]">' . htmlspecialchars($numRows) . '</p>
                <h2 class="font-medium text-[18px]">Number of claimed documents</h2>
             </div>';

        if ($numRows > 0) {
            echo '<div class="table-container">';
            // echo '<h2>Claimed Documents</h2>';
            echo '<table class="table table-hover" id="documentTableClaimed">
                    <thead>
                        <tr class="table-apple-green/  /">
                           
                            <th scope="col">Full Name</th>
                            <th scope="col">Grade Level</th>
                            <th scope="col">Requested Document</th>
                            <th scope="col">Request Date</th>
                            <th scope="col">Claim Date</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($claimedRow = mysqli_fetch_assoc($claimedResult)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($claimedRow['fullName']) . '</td>';
                echo '<td>' . htmlspecialchars($claimedRow['gradelvl']) . '</td>';
                echo '<td>' . htmlspecialchars($claimedRow['reqDoc']) . '</td>';
                echo '<td>' . htmlspecialchars(date('F d, Y', strtotime($claimedRow['reqDate']))) . '</td>';
                echo '<td>' . htmlspecialchars(date('F d, Y h:m A', strtotime($claimedRow['claimDate']))) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></div>';
        } else {
            echo '<div class="table-container"><p>No claimed documents found.</p></div>';
        }

        mysqli_close($conn);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }

    ?>
    <script>
        $(document).ready(function() {
            $('#documentTableClaimed').DataTable();
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

</body>

</html>