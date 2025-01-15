<?php
include 'db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user_id from POST data
    $userId = $_POST['user_id'];

    // Check if user_id is set
    if (!isset($userId) || empty($userId)) {
        $response['error'] = true;
        $response['message'] = 'User ID is missing.';
        echo json_encode($response);
        exit;
    }

    // Log received user_id
    error_log("Received user_id: " . $userId);

    // SQL query to update session
    $sql = "UPDATE users SET session_active = 0 WHERE id = '$userId'";

    // Log the SQL query
    error_log("SQL Query: " . $sql);

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        $response['error'] = false;
        $response['message'] = 'Logout successful.';
    } else {
        // Log the MySQL error
        error_log("MySQL Error: " . mysqli_error($conn));

        $response['error'] = true;
        $response['message'] = 'Failed to log out. Please try again.';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);

// Close the connection
mysqli_close($conn);
