<?php
// Include the database connection
include('db_connect.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the learner ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete the learner
    $query = "DELETE FROM users WHERE id = ? AND role = 'learner'";

    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameters and execute the query
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => 'Learner deleted successfully']);
        } else {
            echo json_encode(['error' => 'Error deleting learner']);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo json_encode(['error' => 'Learner ID not provided']);
}

// Close the database connection
mysqli_close($conn);
?>
