<?php
// db_connect.php includes the database connection
include('db_connect.php');

// Set header for JSON response
header('Content-Type: application/json');

// Read input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Check if the required data exists
if (isset($input_data['user_id'], $input_data['quiz_number'], $input_data['score'])) {
    $user_id = $input_data['user_id'];
    $quiz_number = $input_data['quiz_number'];
    $score = $input_data['score'];

    // Prepare SQL query to update the score for the specified quiz number
    $sql = "INSERT INTO quiz_scores (user_id, quiz_number, score) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE score = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiii", $user_id, $quiz_number, $score, $score);
        
        // Execute the query
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Quiz score updated successfully',
                'total_score' => $score
            ]);
        } else {
            // Log error to PHP error log
            error_log("MySQL Error: " . $stmt->error);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to execute query'
            ]);
        }

        $stmt->close();
    } else {
        // Log error to PHP error log if statement preparation fails
        error_log("MySQL Prepare Error: " . $conn->error);
        echo json_encode([
            'success' => false,
            'message' => 'Database query preparation failed'
        ]);
    }
} else {
    // Missing required data
    echo json_encode([
        'success' => false,
        'message' => 'Invalid data provided'
    ]);
}

// Close the database connection
$conn->close();
?>
