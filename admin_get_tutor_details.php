<?php
// Include the database connection file
include 'db_connect.php';

// SQL Query to fetch tutor details (excluding sensitive fields)
$sql = "
    SELECT 
        users.id AS user_id,
        users.username,
        users.email,
        tutor_profiles.id AS profile_id,
        tutor_profiles.bio,
        tutor_profiles.expertise,
        tutor_profiles.availability
    FROM 
        users
    LEFT JOIN 
        tutor_profiles 
    ON 
        users.id = tutor_profiles.user_id
    WHERE 
        users.role = 'tutor'
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Prepare an array to store tutor details
    $tutors = [];
    while ($row = $result->fetch_assoc()) {
        $tutors[] = $row;
    }

    // Output as JSON
    header('Content-Type: application/json');
    echo json_encode($tutors, JSON_PRETTY_PRINT);
} else {
    echo "No tutors found.";
}

// Close the connection
$conn->close();
?>
