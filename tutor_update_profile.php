<?php
// Include database connection
include('db_connect.php');

// Ensure POST parameters are set
if (isset($_POST['userId'], $_POST['bio'], $_POST['expertise'], $_POST['availability'])) {
    // Get data from POST request
    $userId = $_POST['userId'];
    $bio = $_POST['bio'];
    $expertise = $_POST['expertise'];
    $availability = $_POST['availability'];

    // Clear any previous output (important for JSON responses)
    ob_clean();

    // Validate availability input
    $allowedAvailability = ['available', 'not available'];
    if (!in_array($availability, $allowedAvailability)) {
        echo json_encode(["status" => "error", "message" => "Invalid availability value."]);
        exit;
    }

    // Sanitize input to prevent SQL injection
    $bio = htmlspecialchars($bio, ENT_QUOTES, 'UTF-8');
    $expertise = htmlspecialchars($expertise, ENT_QUOTES, 'UTF-8');

    // Check if the user already has a profile
    $query = "SELECT * FROM tutor_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing profile
        $updateQuery = "UPDATE tutor_profiles SET bio = ?, expertise = ?, availability = ? WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssi", $bio, $expertise, $availability, $userId);
        
        if ($updateStmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Profile updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update profile."]);
        }
    } else {
        // Create new profile
        $insertQuery = "INSERT INTO tutor_profiles (user_id, bio, expertise, availability) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("isss", $userId, $bio, $expertise, $availability);
        
        if ($insertStmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Profile created successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to create profile."]);
        }
    }

    // Close statements and connection
    $stmt->close();
    if (isset($updateStmt)) $updateStmt->close();
    if (isset($insertStmt)) $insertStmt->close();
    $conn->close();
} else {
    // Handle missing parameters
    echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
}
?>
