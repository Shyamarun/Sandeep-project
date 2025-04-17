<?php
include 'sql_conn.php';
if (isset($_POST['submit'])) {
    session_start();
    $class_id = $_SESSION['class_id'];
    $table_name = "Attendance_" . $class_id;
    $current_date = date("Y-m-d");

    // Fetch all registration numbers from $table_name for the given class_id
    $sql_reg_nums = "SELECT reg_num FROM $table_name WHERE class_id = '$class_id' AND attendance_status='Absent' AND date='$current_date'";
    $result_reg_nums = $conn->query($sql_reg_nums);

    // Iterate through all registration numbers
    while ($row_reg_nums = $result_reg_nums->fetch_assoc()) {
        $reg_num = $row_reg_nums['reg_num'];

        // If the checkbox is checked, set attendance_status to 'Present'; otherwise, set it to 'Absent'
        $attendance_status = isset($_POST['attendance'][$reg_num]) ? 'Present' : 'Absent';

        // Use UPDATE instead of INSERT and correct the syntax
        $update_sql = "UPDATE $table_name SET attendance_status='$attendance_status', submission_time=NOW() WHERE reg_num='$reg_num' AND date='$current_date'";
        if ($conn->query($update_sql) === FALSE) {
            echo "Error updating attendance record: " . $conn->error;
        }
    }

    echo "Attendance updated successfully!";
}

$conn->close();
?>
