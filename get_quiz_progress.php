<?php
// Include the database connection
include('db_connect.php'); // Ensure this file sets up $conn correctly

// Set the content type to JSON
header('Content-Type: application/json; charset=UTF-8');

// Enable error reporting for debugging during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Prepare a response array
$response = ['success' => false];

// Get user ID from query parameters
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Check if user ID is valid
if ($user_id > 0) {
    // Check database connection
    if ($conn) {
        // Prepare SQL query to fetch progress for the user
        $sql = "SELECT quiz_level_completed FROM quiz_progress WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // User exists, fetch data
                $stmt->bind_result($quiz_level_completed);
                $stmt->fetch();

                // Prepare successful response
                $response = [
                    'success' => true,
                    'quiz_level_completed' => $quiz_level_completed ?? 1, // Default to 1 if null
                ];
            } else {
                // No progress found for this user
                $response['message'] = 'No progress found for this user';
            }

            $stmt->close();
        } else {
            $response['message'] = 'Database query preparation failed';
        }
    } else {
        $response['message'] = 'Database connection failed';
    }
} else {
    $response['message'] = 'Invalid user ID';
}

// Close the database connection
if ($conn) {
    $conn->close();
}

// Output the JSON response
echo json_encode($response);
?>
