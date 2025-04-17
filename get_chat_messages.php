<?php
// Include database connection
include 'sql_conn.php';
session_start();
$projectCode = $_GET['projectCode'];

// Fetch chat messages from the database
$query = "SELECT * FROM club_chat_table WHERE projectCode=? ORDER BY post_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $projectCode);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<p><strong>' . htmlspecialchars($row['reg_num']) . ':</strong> ' . htmlspecialchars($row['chat']) . '</p>';
}

$conn->close();
