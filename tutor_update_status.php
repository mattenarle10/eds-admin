<?php
header('Content-Type: application/json');
include('db_connect.php'); // Include your database connection file

// Get the booking_id and new status from the request
$booking_id = $_POST['booking_id']; // Booking ID passed from the Flutter app
$new_status = $_POST['new_status']; // New status (confirmed, pending, rejected) passed from Flutter

// Validate the status to ensure it is one of the allowed values
$valid_statuses = ['confirmed', 'pending', 'rejected'];
if (!in_array($new_status, $valid_statuses)) {
    echo json_encode(['error' => 'Invalid status value']);
    exit();
}

// Update the status of the booking in the database
$query = "UPDATE bookings SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $new_status, $booking_id); // Bind the new status and booking_id
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Return success response if update was successful
    echo json_encode(['success' => 'Booking status updated successfully']);
} else {
    // If no rows were affected, return an error
    echo json_encode(['error' => 'Failed to update booking status']);
}
?>
