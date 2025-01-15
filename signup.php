<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elderly_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => true, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'learner';
    $username = $_POST['username'] ?? '';

    if (empty($email) || empty($password) || empty($username)) {
        echo json_encode(["error" => true, "message" => "Email, username, and password are required"]);
        exit();
    }

    // Check if the username already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo json_encode(["error" => true, "message" => "Username already exists"]);
        $check_stmt->close();
        exit();
    }

    $check_stmt->close();

    // Hash the password and insert into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo json_encode(["error" => false, "message" => "Signup successful!"]);
    } else {
        echo json_encode(["error" => true, "message" => "Failed to sign up"]);
    }

    $stmt->close();
}

$conn->close();
?>
