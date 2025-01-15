<?php
include 'db_connect.php';

// Fetch quiz progress with user details, ordered by quiz_level_completed
$query = "
    SELECT users.username, quiz_progress.quiz_level_completed, quiz_progress.updated_at
    FROM quiz_progress
    INNER JOIN users ON quiz_progress.user_id = users.id
    ORDER BY quiz_progress.quiz_level_completed DESC, quiz_progress.updated_at ASC
";

$result = $conn->query($query);

// Prepare response
$leaders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaders[] = $row;
    }
}

// Output as JSON
echo json_encode($leaders);

// Close connection
$conn->close();
?>
