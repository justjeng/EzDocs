<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Includes -->
    <?php
    include("../_conn/adminsession.php");
    include("../_includes/styles.php");
    include("../_includes/scripts.php");
    ?>

    <link rel="stylesheet" href="https://code.jquery.com/jquery-3.6.0.min.js">
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#documentTableStudent').DataTable();
        });
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Select all dropdowns with class "status-dropdown"
            const statusDropdowns = document.querySelectorAll('.status-dropdown');

            statusDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', function() {
                    const selectedStatus = this.value; // Get selected value
                    const requestId = this.getAttribute('data-id'); // Get associated request ID

                    // Display SweetAlert confirmation
                    Swal.fire({
                        title: 'Update Status',
                        text: `Are you sure you want to change the status to ${selectedStatus}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update it!',
                        cancelButtonText: 'No, cancel!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // AJAX request to update the status in the database
                            fetch('../backend/admin/update_status.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        id: requestId,
                                        status: selectedStatus
                                    }),
                                })
                                .then(response => {
                                    // console.log('Response received:', response); // Log the response
                                    return response.json(); // Parse JSON
                                })
                                .then(data => {
                                    // console.log('Data received:', data); // Log the parsed data
                                    if (data.success) {
                                        // Display success message and reload
                                        Swal.fire(
                                            'Updated!',
                                            `The status has been updated to ${selectedStatus}.`,
                                            'success'
                                        ).then(() => {
                                            // Update the UI
                                            const statusElement = document.getElementById(`status-${requestId}`);
                                            if (statusElement) {
                                                statusElement.querySelector('p').innerText = selectedStatus;
                                            } else {
                                                // console.warn(`Element with id status-${requestId} not found.`);
                                            }
                                            location.reload(); // Reload the page
                                        });
                                    } else {
                                        // Handle the error case
                                        Swal.fire(
                                            'Error!',
                                            'There was an error updating the status.',
                                            'error'
                                        );
                                    }
                                })
                                // since claimed status can't be fetched (because it is in different table). 
                                // i use catch error as success message when updating status to claimed.
                                .catch(error => {
                                    console.error('Error during fetch:', error);
                                    // Show error message
                                    Swal.fire(
                                        'Error!',
                                        'There was an error updating the status.',
                                        'error'
                                    ).then(() => {
                                        // Reload the page after error
                                        location.reload(); // Reload the page on error
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>


    <style>
        /* Global Styles */
        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            background-color: #F7F7F7;
            color: #333333;
        }

        /* Header Styles */
        nav {
            background-color: #8BC34A;
            padding: 20px;
            text-align: center;
        }

        nav h1 {
            color: #FFFFFF;
            font-size: 24px;
            margin-bottom: 10px;
        }

        /* Navigation Styles */
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

        /* Main Content Styles */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .rounded {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .shadow-lg {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .mt-2 {
            margin-top: 20px;
        }

        .px-3 {
            padding-left: 20px;
            padding-right: 20px;
        }

        .py-5 {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .bg-white {
            background-color: #FFFFFF;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #8BC34A;
            color: #FFFFFF;
        }

        /* Button Styles */
        .btn {
            background-color: #FFC107;
            color: #FFFFFF;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #FFA07A;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['updateSuccess'])) { ?>
        <script>
            Swal.fire(
                'Updated!',
                `Request updated successfully.`,
                'success'
            )
        </script>
    <?php unset($_SESSION['updateSuccess']);
    } ?>

    <?php if (isset($_SESSION['msgSuccess'])) { ?>
        <script>
            Swal.fire(
                'Success!',
                `<?= $_SESSION['msgSuccess'] ?>`,
                'success'
            )
        </script>
    <?php unset($_SESSION['msgSuccess']);
    } ?>

    <?php if (isset($_SESSION['msgError'])) { ?>
        <script>
            Swal.fire(
                'Error!',
                `<?= $_SESSION['msgError'] ?>`,
                'success'
            )
        </script>
    <?php unset($_SESSION['msgError']);
    } ?>
    <nav class="flex flex-row items-center justify-between px-10 py-4 bg-emerald-900">
        <h1 class="font-bold text-[26px] text-white">EZDocs</h1>
        <ul class="flex flex-row gap-x-4 !p-0 !m-0 list-none">
            <?php if ($_SESSION['adminType'] == 'super admin') { ?>
                <li>
                    <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../backend/admin/be_adminaccounts.php">
                        Admin Accounts
                    </a>
                </li>
                <li>
                    <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../backend/admin/be_dataanalytics.php">
                        Data Analytics
                    </a>
                </li>
            <?php } ?>
            <li>
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="../backend/admin/be_studentacc.php">
                    Student
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
        <div class="flex flex-row items-center justify-between ">
            <h1 class="text-[32px] font-bold">Hi there, <br><?php echo $_SESSION['name']; ?></h1>
            <div class="flex flex-col gap-5">
                <?php include('../backend/admin/be_admincountreq.php'); ?>
            </div>
        </div>

        <div class="rounded shadow-lg mt-2 px-3 py-5">
            <?php include_once('../backend/admin/be_admindashtable.php'); ?>
        </div>
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
                    window.location.href = "../backend/admin/be_adminlogout.php";
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all delete links with the class 'delete-link'
            document.querySelectorAll('.delete-link').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link click behavior

                    const id = this.getAttribute('data-id'); // Get student LRN from data-id

                    // SweetAlert confirmation
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will delete the request. You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the delete link if confirmed
                            window.location.href = '../backend/admin/be_requestdelete.php?id=' + id;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>