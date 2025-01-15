<?php
// db_connect.php includes the database connection
include('db_connect.php');

// Set header for JSON response
header('Content-Type: application/json');

// Check if the required data exists
if (isset($_GET['user_id']) && isset($_GET['quiz_number'])) {
    $user_id = intval($_GET['user_id']);
    $quiz_number = intval($_GET['quiz_number']);

    // Prepare SQL query to fetch the specific quiz score
    $sql = "SELECT score FROM quiz_scores WHERE user_id = ? AND quiz_number = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $user_id, $quiz_number);

        // Execute the query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                // Respond with the specific quiz score
                echo json_encode([
                    'success' => true,
                    'message' => 'Quiz score retrieved successfully',
                    'quiz_number' => $quiz_number,
                    'score' => $row['score']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No score found for the specified quiz number'
                ]);
            }
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
