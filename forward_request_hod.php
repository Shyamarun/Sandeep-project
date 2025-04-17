<?php
include 'sql_conn.php';
$reg_num = $_POST['reg_num'] ?? '';
$id = $_POST['id'] ?? '';
$forwardTo = $_POST['forwardTo'] ?? '';
$forwardTo_name = '';
// Get clg_code, course, branch from stdreg table
$stmt = $conn->prepare("SELECT clg_code, course FROM stdreg WHERE reg_num = ?");
$stmt->bind_param("s", $reg_num);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($forwardTo == 'PRI') {
    $forwardTo_name = 'Principal';
} else if ($forwardTo == 'DIR') {
    $forwardTo_name = 'Director';
} else if ($forwardTo == 'VPRI') {
    $forwardTo_name = 'Vice Principal';
} else if ($forwardTo == 'AO') {
    $forwardTo_name = 'AO';
}else{
    $forwardTo_name = '';
}

$recipient = $result['clg_code'] . $result['course'] . $forwardTo;
$stage = 'Request Forwarded by HOD to ' . $forwardTo_name;
// Update the permission_student table to forward request
$updateStmt = $conn->prepare("UPDATE permission_student SET recipient = ?, status = 'Waiting', stage = ? WHERE reg_num = ? AND id = ?");
$updateStmt->bind_param("sssi", $recipient, $stage,$reg_num,$id);
$updateStmt->execute();
?>
