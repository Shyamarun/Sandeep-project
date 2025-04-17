<?php
include "sql_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_num=$_POST['reg_num'];
    $projectCode = $_POST["projectCode"];
    $status = $_POST["status"];

    // Update status_of_request in club_responses
    $updateSql = "UPDATE club_responses SET status_of_request = '$status' WHERE projectCode = '$projectCode' AND responder_reg_num='$reg_num'";

    if ($conn->query($updateSql) === TRUE) {
        echo "<script>alert('Status updated successfully!');window.location.href='club_responses.php';</script>";
    } else {
        echo "<script>alert('Error updating status: " . $conn->error."');window.location.href='club_responses.php';</script>";
    }
}

$conn->close();
