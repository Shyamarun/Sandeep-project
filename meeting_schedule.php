<?php
include 'sql_conn.php';
session_start();
// Get values from the form
$meetingLink = $_POST['meetingLink'];
$meetingDate = $_POST['meetingDate'];
$meetingTime = $_POST['meetingTime'];
$class_id = $_SESSION['class_id'];

// SQL query to insert data into the meetings table
$sql = "INSERT INTO meetings (class_id, meeting_link, meeting_date, meeting_time) 
        VALUES ('$class_id', '$meetingLink', '$meetingDate', '$meetingTime')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Meeting details added successfully');window.location.href='meeting_schedule_home.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
