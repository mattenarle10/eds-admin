<?php
header('Content-Type: application/json');
include('db_connect.php'); // Include your database connection file

// Get userId from the URL (passed from Flutter)
$userId = $_GET['userId']; // Use 'userId' passed from the Flutter app

// Fetch tutor details using the userId
$query = "SELECT id FROM users WHERE id = ? AND role = 'tutor' LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId); // Bind the userId to the query
$stmt->execute();
$result = $stmt->get_result();

// Check if a tutor was found
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $tutor_id = $user['id']; // Get the tutor's id

    // Fetch bookings for this tutor
    $bookingQuery = "SELECT * FROM bookings WHERE tutor_id = ?";
    $bookingStmt = $conn->prepare($bookingQuery);
    $bookingStmt->bind_param('i', $tutor_id); // Bind the tutor_id to the query
    $bookingStmt->execute();
    $bookingResult = $bookingStmt->get_result();

    $bookings = [];
    while ($row = $bookingResult->fetch_assoc()) {
        // Get learner's username based on the learner_id in the booking
        $learnerId = $row['learner_id']; // Assuming 'learner_id' is a field in your 'bookings' table

        // Fetch learner's username
        $learnerQuery = "SELECT username FROM users WHERE id = ?";
        $learnerStmt = $conn->prepare($learnerQuery);
        $learnerStmt->bind_param('i', $learnerId); // Bind the learner_id to the query
        $learnerStmt->execute();
        $learnerResult = $learnerStmt->get_result();

        if ($learnerResult->num_rows > 0) {
            $learner = $learnerResult->fetch_assoc();
            $row['learner_username'] = $learner['username']; // Add learner's username to the booking row
        } else {
            $row['learner_username'] = 'Unknown'; // Default if no username found
        }

        // Add the booking with the learner's username to the bookings array
        $bookings[] = $row;
    }

    echo json_encode($bookings); // Return the bookings as JSON
} else {
    // If no tutor found, return an error message
    echo json_encode(['error' => 'Tutor not found or invalid userId']);
}
?>
