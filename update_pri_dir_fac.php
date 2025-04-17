<?php
include 'sql_conn.php';
$faculty_id = $_POST['faculty_id'] ?? '';
$status = $_POST['status'] ?? '';
$stage = $_POST['stage'] ?? '';
$id = $_POST['id'] ?? '';
// Update the permission_teacher table
$stmt = $conn->prepare("UPDATE permission_teacher SET status = ?, stage = ? WHERE faculty_id = ? AND id = ?");
$stmt->bind_param("sssi", $status, $stage, $faculty_id, $id);
$stmt->execute();
?>
