<?php
include 'sql_conn.php';
$reg_num = $_POST['reg_num'] ?? '';
$id = $_POST['id'] ?? '';
// Get clg_code, course, branch from stdreg table
$stmt = $conn->prepare("SELECT clg_code, course, branch FROM stdreg WHERE reg_num = ?");
$stmt->bind_param("s", $reg_num);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$recipient = $result['clg_code'] . $result['course'] .$result['branch'].'HOD';

// Update the permission_student table to forward request
$updateStmt = $conn->prepare("UPDATE permission_student SET recipient = ?, status = 'Waiting', stage = 'Request Forwarded to HOD by Class Incharge' WHERE reg_num = ? AND id = ?");
$updateStmt->bind_param("ssi", $recipient, $reg_num,$id);
$updateStmt->execute();
?>
