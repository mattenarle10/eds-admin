<?php
// total-users.php

require_once 'db_connect.php'; // Ensure this path is correct

// Check for a valid connection
if ($conn->connect_error) {
    echo json_encode(['error' => true, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Query to fetch the total number of users
$query = "SELECT COUNT(*) AS total_users FROM users"; // Adjust the table name if needed
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(['totalUsers' => $row['total_users']]);
} else {
    echo json_encode(['error' => true, 'message' => 'Failed to fetch user count']);
}

$conn->close();
?>
