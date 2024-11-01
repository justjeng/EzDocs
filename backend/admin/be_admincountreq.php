<?php
try {
    include("../_conn/connection.php");

    // Query to get total document requests
    $totalQuery = "SELECT COUNT(*) AS total_requests FROM ezdrequesttbl";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalRequests = $totalRow['total_requests'];

    // Query to get pending document requests
    $pendingQuery = "SELECT COUNT(*) AS pending_requests FROM ezdrequesttbl WHERE status = 'Pending'";
    $pendingResult = mysqli_query($conn, $pendingQuery);
    $pendingRow = mysqli_fetch_assoc($pendingResult);
    $pendingRequests = $pendingRow['pending_requests'];

    // Query to get processing document requests
    $processingQuery = "SELECT COUNT(*) AS processing_requests FROM ezdrequesttbl WHERE status = 'Processing'";
    $processingResult = mysqli_query($conn, $processingQuery);
    $processingRow = mysqli_fetch_assoc($processingResult);
    $processingRequests = $processingRow['processing_requests'];

    // Query to get ready document requests
    $readyQuery = "SELECT COUNT(*) AS ready_requests FROM ezdrequesttbl WHERE status = 'Ready'";
    $readyResult = mysqli_query($conn, $readyQuery);
    $readyRow = mysqli_fetch_assoc($readyResult);
    $readyRequests = $readyRow['ready_requests'];

    // Query to get claimed document requests
    $claimedQuery = "SELECT COUNT(*) AS claimed_requests FROM ezdrequesttbl WHERE status = 'Claimed'";
    $claimedResult = mysqli_query($conn, $claimedQuery);
    $claimedRow = mysqli_fetch_assoc($claimedResult);
    $claimedRequests = $claimedRow['claimed_requests'];

    // Echo the results
    echo '<div class="flex flex-row items-start gap-8">
            <div class="flex flex-col items-center">
                <p class="font-bold text-[26px]">' . htmlspecialchars($totalRequests) . '</p>
                <h2 class="font-medium text-[18px]">Total Requests</h2>
            </div>
            <div class="flex flex-col items-center">
                <p class="font-bold text-[26px]">' . htmlspecialchars($pendingRequests) . '</p>
                <h2 class="font-medium text-[18px]">Pending Requests</h2>
            </div>
             <div class="flex flex-col items-center">
                <p class="font-bold text-[26px]">' . htmlspecialchars($processingRequests) . '</p>
                <h2 class="font-medium text-[18px]">Processing Requests</h2>
            </div>

            <div class="flex flex-col items-center">
                <p class="font-bold text-[26px]">' . htmlspecialchars($readyRequests) . '</p>
                <h2 class="font-medium text-[18px]">Ready for claim Requests</h2>
            </div>

            <div class="flex flex-col items-center">
                <p class="font-bold text-[26px]">' . htmlspecialchars($claimedRequests) . '</p>
                <h2 class="font-medium text-[18px]">Claimed Requests</h2>
            </div>
          </div>';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
