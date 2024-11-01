<?php
include("../../_conn/connection.php");
include("../../_conn/adminsession.php");

// Query to count users per grade level
$studentQuery = "SELECT gradeLevel, COUNT(*) as count FROM student_tbl GROUP BY gradeLevel";
$studentResult = $conn->query($studentQuery);
$studentData = [];

while ($row = $studentResult->fetch_assoc()) {
    $studentData[] = $row;
}

// Prepare data for JavaScript
$gradeLevels = [];
$userCounts = [];

foreach ($studentData as $data) {
    $gradeLevels[] = $data['gradeLevel']; // Adjusted to match database column case
    $userCounts[] = $data['count'];
}

// Query to count students per grade level
$gradelvlQuery = "SELECT gradelvl, COUNT(*) as count FROM ezdrequesttbl GROUP BY gradelvl";
$gradelvlResult = $conn->query($gradelvlQuery);
$gradelvlData = [];
while ($row = $gradelvlResult->fetch_assoc()) {
    $gradelvlData[] = $row;
}

// Query to count each request document type
$reqDocQuery = "SELECT reqDoc, COUNT(*) as count FROM ezdrequesttbl GROUP BY reqDoc";
$reqDocResult = $conn->query($reqDocQuery);
$reqDocData = [];
while ($row = $reqDocResult->fetch_assoc()) {
    $reqDocData[] = $row;
}

// Query to count each status
$statusQuery = "SELECT status, COUNT(*) as count FROM ezdrequesttbl GROUP BY status";
$statusResult = $conn->query($statusQuery);
$statusData = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusData[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analytics</title>
    <?php
    include("../../_includes/styles.php");
    include("../../_includes/scripts.php");
    ?>


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
                <a class="block text-white text-[17px] font-regular hover:no-underline px-3" href="claimed_history.php">
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

    <div class="container mt-5 mb-5">
        <div class="row ">
            <div class="col-md-6">
                <div id="studentChart" style="width: 600px; margin: auto; margin-top: 30px;"></div>
            </div>
            <div class="col-md-6">
                <!-- Divs for each chart -->
                <div id="gradelvlChart" style="width: 600px; margin: auto; margin-top: 30px;"></div>
            </div>


            <hr>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div id="reqDocChart" style="width: 600px; margin: auto; margin-top: 30px;"></div>
            </div>
            <div class="col-md-6">
                <div id="statusChart" style="width: 600px; margin: auto; margin-top: 30px;"></div>
            </div>


        </div>
    </div>


    <!-- Pass data to JavaScript -->
    <script>
        // Convert PHP data to JavaScript arrays for each chart
        const gradelvlData = <?php echo json_encode($gradelvlData); ?>;
        const reqDocData = <?php echo json_encode($reqDocData); ?>;
        const statusData = <?php echo json_encode($statusData); ?>;

        // Data for the Grade Level Bar Chart
        const gradeLevels = gradelvlData.map(item => item.gradelvl);
        const gradeCounts = gradelvlData.map(item => parseInt(item.count));

        var gradelvlChartOptions = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Students per Grade',
                data: gradeCounts
            }],
            xaxis: {
                categories: gradeLevels
            },
            title: {
                text: 'Count of Students Requests per Grade Level'
            }
        };
        var gradelvlChart = new ApexCharts(document.querySelector("#gradelvlChart"), gradelvlChartOptions);
        gradelvlChart.render();

        // Data for the Request Document Pie Chart
        const reqDocs = reqDocData.map(item => item.reqDoc);
        const reqDocCounts = reqDocData.map(item => parseInt(item.count));

        var reqDocChartOptions = {
            chart: {
                type: 'pie',
                height: 350,
                toolbar: {
                    show: true,
                    tools: {
                        download: true, // Enables built-in download for PNG and SVG

                    }
                }
            },
            series: reqDocCounts,
            labels: reqDocs,
            title: {
                text: 'Count of Request Documents'
            }
        };
        var reqDocChart = new ApexCharts(document.querySelector("#reqDocChart"), reqDocChartOptions);
        reqDocChart.render();

        // Data for the Status Pie Chart
        const statuses = statusData.map(item => item.status);
        const statusCounts = statusData.map(item => parseInt(item.count));

        var statusChartOptions = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: true,
                    tools: {
                        download: true, // Enables built-in download for PNG and SVG

                    }
                }
            },
            series: statusCounts,
            labels: statuses,
            title: {
                text: 'Count of Request Status'
            }
        };
        var statusChart = new ApexCharts(document.querySelector("#statusChart"), statusChartOptions);
        statusChart.render();

        // PHP data to JavaScript
        const s_gradeLevels = <?php echo json_encode($gradeLevels); ?>;
        const userCounts = <?php echo json_encode($userCounts); ?>;

        // ApexCharts options
        const studentChartOptions = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Number of Users',
                data: userCounts
            }],
            xaxis: {
                categories: s_gradeLevels,
                title: {
                    text: 'Grade Level'
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Users'
                }
            },
            title: {
                text: 'Number of System Users per Grade Level',
                align: 'center'
            },
            colors: ['#008FFB']
        };

        // Render the chart
        const studentChart = new ApexCharts(document.querySelector("#studentChart"), studentChartOptions);
        studentChart.render();
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