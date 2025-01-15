<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the tutor ID from the request
    $tutor_id = isset($_POST['tutor_id']) ? intval($_POST['tutor_id']) : 0;

    if ($tutor_id > 0) {
        // Delete query for both users and tutor_profiles
        $delete_tutor_profile = $conn->prepare("DELETE FROM tutor_profiles WHERE user_id = ?");
        $delete_tutor_profile->bind_param("i", $tutor_id);
        $delete_tutor_profile->execute();

        $delete_user = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_user->bind_param("i", $tutor_id);
        $delete_user->execute();

        if ($delete_tutor_profile->affected_rows > 0 || $delete_user->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Tutor deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete tutor."]);
        }

        $delete_tutor_profile->close();
        $delete_user->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid tutor ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
