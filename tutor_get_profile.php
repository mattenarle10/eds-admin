<?php
// Include database connection
include('db_connect.php');

// Ensure the required parameter is provided
if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Log the received userId for debugging
    error_log("Received userId: " . $userId);

    // Clear previous output for clean JSON responses
    ob_clean();

    // Query to fetch the tutor's profile
    $query = "SELECT bio, expertise, availability FROM tutor_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch profile details
        $profile = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $profile]);
    } else {
        // Profile not found
        echo json_encode(["status" => "error", "message" => "Profile wasn't set up yet."]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Handle missing parameters
    echo json_encode(["status" => "error", "message" => "Missing required parameter: userId."]);
}
?>
