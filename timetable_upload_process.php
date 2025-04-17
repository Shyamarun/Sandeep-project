<?php
include 'sql_conn.php'; // Includes the database connection file//
session_start();
$class_id = $_SESSION['class_id'];
$day = ''; // The day for which data is being sent
$subjects = [];
$faculty_ids = [];

foreach($_POST as $key => $value) {
    if(strpos($key, 'subject_') === 0) {
        $day = explode('_', $key)[1];
        $subjects = $value;
    } else if(strpos($key, 'faculty_id_') === 0) {
        $faculty_ids = $value;
    }
}

for($period = 0; $period < count($subjects); $period++) {
    $subject = $subjects[$period];
    $faculty_id = $faculty_ids[$period];

    // Validate subject and faculty_id
    $validationSql = "SELECT * FROM master_faculty WHERE subject_name = '$subject' AND faculty_id = '$faculty_id'";
    $result = $conn->query($validationSql);

    if ($result && $result->num_rows > 0) {
        // If valid, insert data into timetable
        $insertSql = "INSERT INTO timetable (class_id, day, period, subject, faculty_id) VALUES ('$class_id', '$day', $period + 1, '$subject', '$faculty_id')";

        if (!$conn->query($insertSql)) {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
    } else {
        // If validation fails
        echo "alert('$subject is not linked with $faculty_id');";
    }
}
echo "Data for $day inserted successfully";
$conn->close();
?>
