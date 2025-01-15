<?php
// Include database connection
include 'db_connect.php';

// Get learner ID from the request
$learner_id = $_GET['learner_id'];

// Validate the data
if (empty($learner_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing learner_id']);
    exit();
}

// Fetch booking history
$sql = "SELECT b.id, u.username AS tutor_name, b.status, b.booking_date 
        FROM bookings b
        JOIN users u ON b.tutor_id = u.id
        WHERE b.learner_id = ? 
        ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $learner_id);
$stmt->execute();

$result = $stmt->get_result();
$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode(['status' => 'success', 'bookings' => $bookings]);

// Close the connection
$stmt->close();
$conn->close();
?>
