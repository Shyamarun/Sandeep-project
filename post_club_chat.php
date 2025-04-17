<?php
// Include database connection
session_start();
include 'sql_conn.php';

$projectCode = $_POST['projectCode'];
$reg_num = $_SESSION['reg_num'];
$chat = $_POST['chat'];

// Insert chat message into the database
$insert_query = "INSERT INTO club_chat_table (projectCode, reg_num, chat) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("sss", $projectCode, $reg_num, $chat);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>alert('Message sent.');window.location.href='club_community_chat_page.php';</script>";
} else {
    echo "<script>alert('Error sending message.');window.location.href='club_community_chat_page.php';</script>";
}

$conn->close();
