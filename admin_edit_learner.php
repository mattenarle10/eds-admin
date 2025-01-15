<?php
// Include the database connection
include('db_connect.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if data is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // SQL query to update the learner data
    $query = "UPDATE users SET username = ?, email = ? WHERE id = ? AND role = 'learner'";

    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameters and execute the query
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $email, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => 'Learner updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating learner']);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

// Close the database connection
mysqli_close($conn);
?>
