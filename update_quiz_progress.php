<?php
// Include the database connection
include('db_connect.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Function to send JSON response
function sendResponse($message, $data = null) {
    echo json_encode(['message' => $message, 'data' => $data]);
    exit;
}

// Read raw POST data and decode JSON
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate and extract data from JSON
$user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
$quiz_level_completed = isset($data['quiz_level_completed']) ? intval($data['quiz_level_completed']) : 0;

// Log received values
error_log("Received user_id: $user_id, quiz_level_completed: $quiz_level_completed");

// Validate user_id
if ($user_id <= 0) {
    sendResponse('Invalid user ID');
}

// Prepare SQL query to check if the user exists
$sql = "SELECT id, quiz_level_completed FROM quiz_progress WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    sendResponse('Database query preparation failed');
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

// Determine whether to update or insert based on user existence
if ($stmt->num_rows > 0) {
    // User exists, fetch their progress
    $stmt->bind_result($existing_id, $existing_level);
    $stmt->fetch();  // Fetch the existing user's data

    // Log the current and incoming levels
    error_log("Current level in database: $existing_level, Submitted level: $quiz_level_completed");

    // Increment level by 1, but do not exceed 6
    $new_level = min($existing_level + 1, 6);

    if ($existing_level >= 6) {
        sendResponse('User has already completed the maximum level', ['current_level' => $existing_level]);
    }

    $query = "
        UPDATE quiz_progress
        SET quiz_level_completed = ?, 
            updated_at = CURRENT_TIMESTAMP
        WHERE user_id = ?";

    // Log the query and update level
    error_log("Updating level. Current: $existing_level, New: $new_level");

    // Prepare and execute the update query
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        sendResponse('Failed to prepare update query');
    }
    $stmt->bind_param("ii", $new_level, $user_id);
    if ($stmt->execute()) {
        sendResponse('Level updated successfully', ['new_level' => $new_level]);
    } else {
        sendResponse('Failed to update level');
    }

} else {
    // User does not exist, insert new record with default level 1
    $new_level = 1;  // Default level is 1

    $query = "
        INSERT INTO quiz_progress (user_id, quiz_level_completed, updated_at)
        VALUES (?, ?, CURRENT_TIMESTAMP)";

    // Log the new insert action
    error_log("Inserting new progress for user_id: $user_id with default level 1");

    // Prepare and execute the insert query
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        sendResponse('Failed to prepare insert query');
    }
    $stmt->bind_param("ii", $user_id, $new_level);  // Insert with level 1
    if ($stmt->execute()) {
        sendResponse('Progress created successfully with level 1');
    } else {
        sendResponse('Failed to save progress');
    }
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>
