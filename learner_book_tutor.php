<?php
// Include database connection
include 'db_connect.php';

// Get POST data
$learner_id = $_POST['learner_id'];
$tutor_id = $_POST['tutor_id'];

// Validate the data
if (empty($learner_id) || empty($tutor_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit();
}

// Check if both learner and tutor exist in the users table
$sql_check_learner = "SELECT id FROM users WHERE id = ? AND role = 'learner'";
$sql_check_tutor = "SELECT id FROM users WHERE id = ? AND role = 'tutor'";

// Prepare and bind for learner check
$stmt_check_learner = $conn->prepare($sql_check_learner);
$stmt_check_learner->bind_param("i", $learner_id);
$stmt_check_learner->execute();
$result_learner = $stmt_check_learner->get_result();

// Prepare and bind for tutor check
$stmt_check_tutor = $conn->prepare($sql_check_tutor);
$stmt_check_tutor->bind_param("i", $tutor_id);
$stmt_check_tutor->execute();
$result_tutor = $stmt_check_tutor->get_result();

// Validate if learner and tutor exist in the users table
if ($result_learner->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Learner not found or invalid']);
    exit();
}

if ($result_tutor->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Tutor not found or invalid']);
    exit();
}

// Check if there's already an existing booking with the same learner and tutor
$sql_check_booking = "SELECT id FROM bookings WHERE learner_id = ? AND tutor_id = ? AND (status = 'pending' OR status = 'confirmed')";
$stmt_check_booking = $conn->prepare($sql_check_booking);
$stmt_check_booking->bind_param("ii", $learner_id, $tutor_id);
$stmt_check_booking->execute();
$result_booking = $stmt_check_booking->get_result();

// If a booking already exists, return an error
if ($result_booking->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already booked this tutor']);
    exit();
}

// Insert booking into the database
$sql = "INSERT INTO bookings (learner_id, tutor_id, status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $learner_id, $tutor_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Booking successful']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Booking failed']);
}

// Close the statements and connection
$stmt->close();
$stmt_check_learner->close();
$stmt_check_tutor->close();
$stmt_check_booking->close();
$conn->close();
?>
