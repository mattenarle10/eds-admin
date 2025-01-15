<?php
// Include the database connection
include('db_connect.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");


// SQL query to fetch the data from bookings
$query = "SELECT id, learner_id, tutor_id, status, booking_date FROM bookings";
$result = mysqli_query($conn, $query);

$bookings = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $learner_id = $row['learner_id'];
        $tutor_id = $row['tutor_id'];
        $status = $row['status'];
        $booking_date = $row['booking_date'];

        // Fetch the learner's name
        $learner_query = "SELECT username FROM users WHERE id = $learner_id AND role = 'learner'";
        $learner_result = mysqli_query($conn, $learner_query);
        $learner_name = mysqli_fetch_assoc($learner_result)['username'];

        // Fetch the tutor's name
        $tutor_query = "SELECT username FROM users WHERE id = $tutor_id AND role = 'tutor'";
        $tutor_result = mysqli_query($conn, $tutor_query);
        $tutor_name = mysqli_fetch_assoc($tutor_result)['username'];

        // Add the booking to the array
        $bookings[] = [
            'id' => $row['id'],
            'learner_name' => $learner_name,
            'tutor_name' => $tutor_name,
            'status' => $status,
            'booking_date' => $booking_date
        ];
    }
} else {
    echo json_encode(['error' => 'Error fetching data']);
}

// Return the bookings data as JSON
echo json_encode($bookings);

// Close the database connection
mysqli_close($conn);
?>
