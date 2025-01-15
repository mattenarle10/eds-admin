<?php
// Include database connection
include('db_connect.php');

// Set the response header to JSON
header('Content-Type: application/json');

// Query to fetch tutors with availability "Available" from the joined tables
$query = "
    SELECT u.id, u.username, tp.expertise, tp.availability
    FROM users u
    JOIN tutor_profiles tp ON u.id = tp.user_id
    WHERE tp.availability = 'available' AND u.role = 'tutor'";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if tutors were found
if (mysqli_num_rows($result) > 0) {
    $tutors = [];

    // Fetch all tutors
    while ($row = mysqli_fetch_assoc($result)) {
        $tutors[] = $row;
    }

    // Return the list of tutors as a JSON response
    echo json_encode(['status' => 'success', 'tutors' => $tutors]);
} else {
    // No tutors available
    echo json_encode(['status' => 'error', 'message' => 'No available tutors']);
}

// Close the database connection
mysqli_close($conn);
?>
