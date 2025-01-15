<?php
// get_tutor_details.php
include 'db_connect.php'; // Include your database connection file

// Check if user_id is provided in the GET request
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Prepare the SQL query to fetch the tutor details by user_id
    $query = "SELECT bio, expertise, availability FROM tutor_profiles WHERE user_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind the user_id parameter
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();

        // Check if a tutor was found
        if ($result->num_rows > 0) {
            // Fetch the tutor's details
            $row = $result->fetch_assoc();
            echo json_encode([
                'error' => false,
                'bio' => $row['bio'],
                'expertise' => $row['expertise'],
                'availability' => $row['availability']
            ]);
        } else {
            // No tutor found with this user_id
            echo json_encode(['error' => true, 'message' => 'No tutor details found.']);
        }

        $stmt->close();
    } else {
        // SQL query preparation error
        echo json_encode(['error' => true, 'message' => 'Failed to prepare the SQL query.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // If user_id is not provided
    echo json_encode(['error' => true, 'message' => 'User ID is missing.']);
}
?>
