<?php
session_start();
include("../_conn/connection.php");
include("../_includes/styles.php");
include("../_includes/scripts.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claimed Documents</title>
    <style>
        table.dataTable {
            width: 80%;
            table-layout: auto;
            /* This will ensure columns adjust to fit content */
        }

        .table-responsive {
            width: 90%;
            margin: 0 auto;
            margin-bottom: 20px;
        }

        .table-responsive table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-responsive th,
        .table-responsive td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table-responsive th {
            background-color: #8db600;
            color: #FFFFFF;
        }

        .button-group {
            gap: 1px;
        }

        .button-group button {
            margin: 0;
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

<body>

    <!-- Desktop Navigation -->
    <nav class="desktop-nav px-10 py-4 bg-emerald-900">
        <h1 class="font-bold text-[26px] text-white">EZDocs</h1>
        <ul class="flex flex-row gap-x-4 !p-0 !m-0 list-none">
            <li>
                <a class="block text-white text-sm md:text-base font-regular hover:no-underline px-3" href="../index.php">
                    Dashboard
                </a>
            </li>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="claim_history.php">
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
                <a href="../index.php">Dashboard</a>
            </li>
            <li>
                <a href="claim_history.php">Claimed History</a>
            </li>
            <li>
                <button class="btnLogout">Logout</button>
            </li>
        </ul>
    </nav>

    <?php
    try {
        $claimedQuery = "SELECT * FROM ezdrequesttbl WHERE studentLRN = " . $_SESSION['studentId'] . " AND  status = 'claimed' ";
        $claimedResult = mysqli_query($conn, $claimedQuery);

        if (!$claimedResult) {
            die("Query failed: " . mysqli_error($conn));
        }

        $numRows = mysqli_num_rows($claimedResult);
        echo '<div class="flex flex-col items-center mt-2 mb-3">
                <p class="font-bold text-[26px]">' . htmlspecialchars($numRows) . '</p>
                <h2 class="font-medium text-[18px]">Number of claimed documents</h2>
             </div>';

        if ($numRows > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-hover" id="documentTableClaimed">
                    <thead>
                        <tr>
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
                echo '<td>' . htmlspecialchars($claimedRow['reqDate']) . '</td>';
                echo '<td>' . date('Y-m-d H:i:s') . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }

        mysqli_close($conn);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>

    <script>
        $(document).ready(function() {
            $('#documentTableClaimed').DataTable({
                responsive: true
            });
        });
    </script>

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
                    window.location.href = "be_adminlogout.php";
                }
            });
        });
    </script>

</body>

</html>