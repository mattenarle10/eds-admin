<?php
include 'db_connect.php'; // Include the database connection
session_start(); // Start the session

$response = array(); // Initialize response array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from POST request
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Sanitize input
    $password = $_POST['password'];

    // Query to find user by email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify the hashed password
        if (password_verify($password, $row['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Prepare success response
            $response['error'] = false;
            $response['message'] = 'Login successful';
            $response['user'] = array(
                'userId' => $row['id'], // Consistent naming convention
                'username' => $row['username'],
                'email' => $row['email'],
                'role' => $row['role'],
            );
        } else {
            // Password does not match
            $response['error'] = true;
            $response['message'] = 'Invalid password';
        }
    } else {
        // User not found
        $response['error'] = true;
        $response['message'] = 'User not found';
    }
} else {
    // Handle incorrect request method
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
mysqli_close($conn);
?>
