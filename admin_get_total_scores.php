<?php
include 'db_connect.php';

// Query to calculate total scores for each user and rank them
$query = "
    SELECT 
        users.username, 
        SUM(quiz_scores.score) AS total_score
    FROM quiz_scores
    INNER JOIN users ON quiz_scores.user_id = users.id
    GROUP BY quiz_scores.user_id
    ORDER BY total_score DESC, users.username ASC
";

$result = $conn->query($query);

// Prepare response
$scores = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scores[] = $row;
    }
}

// Output as JSON
echo json_encode($scores);

// Close connection
$conn->close();
?>
