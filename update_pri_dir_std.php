<?php
include 'sql_conn.php';
$reg_num = $_POST['reg_num'] ?? '';
$status = $_POST['status'] ?? '';
$stage = $_POST['stage'] ?? '';
$id = $_POST['id'] ?? '';
// Update the permission_student table
$stmt = $conn->prepare("UPDATE permission_student SET status = ?, stage = ? WHERE reg_num = ? AND id = ?");
$stmt->bind_param("sssi", $status, $stage, $reg_num, $id);
$stmt->execute();
?>
