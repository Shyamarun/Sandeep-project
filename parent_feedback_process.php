<?php
session_start();
include 'sql_conn.php';
$reg_num = $_SESSION['reg_num'];
$class_id = $_SESSION['class_id'];
$feedback = $_POST['feedback'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO parent_feedback (reg_num, class_id, feedback) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $reg_num, $class_id, $feedback);

// Execute and close
$stmt->execute();
echo "<script>alert('Feedback recorded successfully.');window.location.href='parent_feedback.php';</script>";

$stmt->close();
$conn->close();
