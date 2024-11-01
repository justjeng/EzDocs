<?php
session_start();
include("../../_conn/connection.php");

header('Content-Type: application/json'); // Set header for JSON response

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

// Check for database connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Validate input
if ($id && $status) {
    // Sanitize inputs
    $id = mysqli_real_escape_string($conn, $id);
    $status = mysqli_real_escape_string($conn, $status);

    $validStatuses = ['pending', 'processing', 'ready', 'claimed'];
    if (in_array($status, $validStatuses)) {

        if ($status == 'claimed') {
            // Update query
            $updateQuery = "UPDATE ezdrequesttbl SET status= ?, claimDate = now() WHERE id=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $status, $id);
        } else {
            // Update query
            $updateQuery = "UPDATE ezdrequesttbl SET status=? WHERE id=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $status, $id);
        }
        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID and status are required.']);
}

// Close the database connection
$conn->close();
