<?php
include 'db_connect.php';

// Initialize response array
$response = [
    'total_users' => 0,
    'total_learners' => 0,
    'total_tutors' => 0,
    'total_bookings' => 0,
];

// Query to count total users
$total_users_query = "SELECT COUNT(*) AS total_users FROM users";
$total_users_result = $conn->query($total_users_query);
if ($total_users_result && $row = $total_users_result->fetch_assoc()) {
    $response['total_users'] = $row['total_users'];
}

// Query to count total learners
$total_learners_query = "SELECT COUNT(*) AS total_learners FROM users WHERE role = 'learner'";
$total_learners_result = $conn->query($total_learners_query);
if ($total_learners_result && $row = $total_learners_result->fetch_assoc()) {
    $response['total_learners'] = $row['total_learners'];
}

// Query to count total tutors
$total_tutors_query = "SELECT COUNT(*) AS total_tutors FROM users WHERE role = 'tutor'";
$total_tutors_result = $conn->query($total_tutors_query);
if ($total_tutors_result && $row = $total_tutors_result->fetch_assoc()) {
    $response['total_tutors'] = $row['total_tutors'];
}

// Query to count total bookings
$total_bookings_query = "SELECT COUNT(*) AS total_bookings FROM bookings";
$total_bookings_result = $conn->query($total_bookings_query);
if ($total_bookings_result && $row = $total_bookings_result->fetch_assoc()) {
    $response['total_bookings'] = $row['total_bookings'];
}

// Output response as JSON
echo json_encode($response);

// Close connection
$conn->close();
?>
