<?php
include 'sql_conn.php'; // Includes the database connection file
include 'show_notification.php';
session_start();
$class_id = $_SESSION['class_id'];
$day = $_POST['day'];
$period = $_POST['period'];
$subject = $_POST['subject'];
$faculty_id = $_POST['faculty_id'];

// Validate subject and faculty_id
$validationSql = "SELECT * FROM master_faculty WHERE subject_name = '$subject' AND faculty_id = '$faculty_id'";
$result = $conn->query($validationSql);

if ($result && $result->num_rows > 0) {
    // Update timetable if validation is successful
    $updateSql = "UPDATE timetable SET subject = '$subject', faculty_id = '$faculty_id' WHERE class_id = '$class_id' AND day = '$day' AND period = $period";

    if ($conn->query($updateSql)) {
        echo "<script>alert('Timetable updated successfully');window.location.href='timetable_update.php?class_id=" . $class_id . "'</script>";
    } else {
        echo "Error: " . $updateSql . "<br>" . $conn->error;
    }
} else {
    echo "<script>alert('$subject is not linked with $faculty_id');window.location.href='timetable_update.php?class_id=" . $class_id . "'</script>";
}

$conn->close();
