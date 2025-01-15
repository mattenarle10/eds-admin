<?php
// Include the database connection
include('db_connect.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // SQL query to delete the booking
    $query = "DELETE FROM bookings WHERE id = $booking_id";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => 'Booking deleted successfully']);
    } else {
        echo json_encode(['error' => 'Error deleting booking']);
    }
} else {
    echo json_encode(['error' => 'Booking ID not specified']);
}

// Close the database connection
mysqli_close($conn);
?>
