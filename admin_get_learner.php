<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('db_connect.php');

// Set content type to JSON
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// SQL query to fetch the learners
$query = "SELECT id, username, email, role FROM users WHERE role = 'learner'";
$result = mysqli_query($conn, $query);

$learners = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Add learner data to the array
        $learners[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'role' => $row['role']
        ];
    }
} else {
    echo json_encode(['error' => 'Error fetching data']);
    exit; // Ensure no further code is executed
}

// Return the learners data as JSON
echo json_encode($learners);

// Close the database connection
mysqli_close($conn);
?>
