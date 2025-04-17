<?php
include 'sql_conn.php';
session_start();
if (isset($_POST['submit'])) {
    $class_id = $_SESSION['class_id'];

    $table_name = "Attendance_" . $class_id;
    $create_table_sql = "CREATE TABLE IF NOT EXISTS $table_name (
                            reg_num VARCHAR(20),
                            class_id VARCHAR(20),
                            attendance_status VARCHAR(10),
                            date DATE,
                            submission_time DATETIME,
                            PRIMARY KEY (reg_num, date)
                        )";
    if ($conn->query($create_table_sql) === FALSE) {
        echo "<script>alert('Error creating table: " . $conn->error.");window.location.href='individual_faculty_attendance.php';</script>";
    }

    // Create or update Attendance_Summary table
    $create_summary_table_sql = "CREATE TABLE IF NOT EXISTS Attendance_Summary (
                                    date DATE,
                                    class_id VARCHAR(20),
                                    total_students INT,
                                    present_students INT,
                                    absent_students INT,
                                    PRIMARY KEY (date, class_id)
                                )";
    if ($conn->query($create_summary_table_sql) === FALSE) {
         echo "<script>alert('Error creating table: " . $conn->error.");window.location.href='individual_faculty_attendance.php';</script>";

    }

    $current_date = date("Y-m-d");

    // Fetch all registration numbers from stdreg for the given class_id
    $sql_reg_nums = "SELECT reg_num FROM stdreg WHERE class_id = '$class_id'";
    $result_reg_nums = $conn->query($sql_reg_nums);

    $total_students = $result_reg_nums->num_rows;
    $present_students = 0;
    $absent_students = 0;

    // Iterate through all registration numbers
    while ($row_reg_nums = $result_reg_nums->fetch_assoc()) {
        $reg_num = $row_reg_nums['reg_num'];

        // If the checkbox is checked, set attendance_status to 'Present'; otherwise, set it to 'Absent'
        $attendance_status = isset($_POST['attendance'][$reg_num]) ? 'Present' : 'Absent';

        $insert_sql = "INSERT INTO $table_name (reg_num, class_id, attendance_status, date, submission_time)
                       VALUES ('$reg_num', '$class_id', '$attendance_status', '$current_date', NOW())
                       ON DUPLICATE KEY UPDATE attendance_status = '$attendance_status', submission_time = NOW()";
        if ($conn->query($insert_sql) === FALSE) {
            echo "<script>alert('Error inserting attendance" . $conn->error.");window.location.href='individual_faculty_attendance.php';</script>";

        }

        // Count present and absent students
        if ($attendance_status === 'Present') {
            $present_students++;
        } else {
            $absent_students++;
        }
    }

    // Insert data into Attendance_Summary table
    $insert_summary_sql = "INSERT INTO Attendance_Summary (date, class_id, total_students, present_students, absent_students)
                           VALUES ('$current_date', '$class_id', '$total_students', '$present_students', '$absent_students')
                           ON DUPLICATE KEY UPDATE total_students = '$total_students', present_students = '$present_students', absent_students = '$absent_students'";
    if ($conn->query($insert_summary_sql) === FALSE) {
        echo "<script>alert('Error creating attendance summary " . $conn->error.");window.location.href='individual_faculty_attendance.php';</script>";

    }

    echo "<script>alert('Attendance updated successfully');window.location.href='attd_home.php';</script>";

}

$conn->close();
