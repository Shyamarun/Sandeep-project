<?php
include 'sql_conn.php';
session_start();
// Fetch class_id from AJAX request
$class_id = $_SESSION['class_id'];
$new_class_id='';
// Fetch student data from stdreg table
$sql = "SELECT reg_num, course, clg_code, branch, sem_year, semester, section FROM stdreg WHERE class_id = '$class_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reg_num = $row['reg_num'];
        $course = $row['course'];
        $sem_year = $row['sem_year'];
        $semester = $row['semester'];

        // Logic to update semester and year based on course and current sem_year/semester
        if (in_array($course, ['BTECH', 'BPHARMACY'])) {
            $max_year = 4;
        } elseif (in_array($course, ['DIPLOMA', 'DEGREE'])) {
            $max_year = 3;
        }

        if ($semester == 1) {
            $new_semester = 2;
            $new_year = $sem_year;
        } elseif ($semester == 2 && $sem_year < $max_year) {
            $new_semester = 1;
            $new_year = $sem_year + 1;
        }

        // Update query
        $updateSql = "UPDATE stdreg SET sem_year = '$new_year', semester = '$new_semester' WHERE reg_num = '$reg_num'";
        $conn->query($updateSql);

        // Update class_id
        $new_class_id = $row['clg_code'].$row['course'].$row['branch'].$new_year.$new_semester.$row['section'];
        $updateClassIdSql = "UPDATE stdreg SET class_id = '$new_class_id' WHERE reg_num = '$reg_num'";
        $conn->query($updateClassIdSql);
    }
    $random_number = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $class_id_pass = $new_class_id . $random_number;
    $sqlCheckExistence = "SELECT COUNT(*) AS count FROM t_auth WHERE class_id = ? OR class_id_pass = ?";
        $stmtCheckExistence = $conn->prepare($sqlCheckExistence);
        $stmtCheckExistence->bind_param("ss", $new_class_id, $class_id_pass);
        $stmtCheckExistence->execute();
        $resultExistence = $stmtCheckExistence->get_result();
        $rowExistence = $resultExistence->fetch_assoc();
        $countExistence = $rowExistence['count'];
        if ($countExistence == 0) {
            // $class_id and $class_id_pass do not exist, insert them into t_auth
            $sqlInsert = "INSERT INTO t_auth (class_id, class_id_pass) VALUES (?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ss", $new_class_id, $class_id_pass);
            $stmtInsert->execute();
        }
        $stmtInsert->close();
        $conn->close();
    echo "<script>alert('Promotion completed successfully.');window.location.href='promote_home.php';</script>";
} else {
    echo "<script>alert('No records found');window.location.href='promote_home.php';</script>";
}
?>
