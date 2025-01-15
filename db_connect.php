<?php
// db_connect.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elderly_db"; // Ensure this matches your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // This will stop execution and output an error message if there is a connection issue.
}

// Remove or comment out the following line to prevent unwanted output
// echo "Connected successfully";  // Remove this line
?>