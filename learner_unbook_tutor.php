<?php
// Database connection
include 'db_connect.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $learner_id = $_POST['learner_id'];

    // Check if the learner has an active booking
    $sql = "SELECT * FROM bookings WHERE learner_id = ? AND status = 'confirmed'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $learner_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // There is an active booking, proceed with unbooking
        $booking = $result->fetch_assoc();
        $booking_id = $booking['id'];

        // Update the booking status to 'rejected' (or 'pending' depending on your logic)
        $sql_update = "UPDATE bookings SET status = 'rejected' WHERE id = ?";
        $update_stmt = $conn->prepare($sql_update);
        $update_stmt->bind_param("i", $booking_id);
        if ($update_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Tutor successfully unbooked."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to unbook the tutor."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No active booking found for this learner."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
