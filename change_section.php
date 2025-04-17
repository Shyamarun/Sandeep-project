<?php
include 'sql_conn.php'; // Include your SQL connection file
session_start();
$class_id = $_SESSION['class_id'] ?? ''; // Retrieve class_id
$new_sections = $_POST['new_sections'] ?? []; // Array of reg_num => new_section
$new_class_id = ''; // New class_id
// Function to apply promotion logic
function promoteStudent($conn, $reg_num, $course, $sem_year, $semester, $section, $clg_code, $branch) {
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
    } else {
        return; // No promotion needed
    }

    // Update query
    $updateSql = "UPDATE stdreg SET sem_year = ?, semester = ? WHERE reg_num = ?";
    $updateClassIdSql = "UPDATE stdreg SET class_id = ? WHERE reg_num = ?";

    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param("iis", $new_year, $new_semester, $reg_num);
        $stmt->execute();
        $stmt->close();
    }

    // Update class_id
    $new_class_id = $clg_code . $course . $branch . $new_year . $new_semester . $section;
    if ($stmt = $conn->prepare($updateClassIdSql)) {
        $stmt->bind_param("ss", $new_class_id, $reg_num);
        $stmt->execute();
        $stmt->close();
    }
    return $new_class_id; // Return the new class_id
}

// Update the section for each student and then promote them
foreach ($new_sections as $reg_num => $new_section) {
    // Update the section
    $updateSectionSql = "UPDATE stdreg SET section = ? WHERE reg_num = ? AND class_id = ?";
    if ($stmt = $conn->prepare($updateSectionSql)) {
        $stmt->bind_param("sss", $new_section, $reg_num, $class_id);
        $stmt->execute();
        $stmt->close();
    }

    // Fetch additional data required for promotion
    $fetchSql = "SELECT course, clg_code, branch, sem_year, semester, section FROM stdreg WHERE reg_num = ?";
    if ($stmt = $conn->prepare($fetchSql)) {
        $stmt->bind_param("s", $reg_num);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Apply promotion logic
            $new_class_id = promoteStudent($conn, $reg_num, $row['course'], $row['sem_year'], $row['semester'], $new_section, $row['clg_code'], $row['branch']);
        }
        $stmt->close();
    }
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
echo "<script>alert('Promotion and Changing section successful'); window.location.href='promote_home.php';</script>";
exit();
?>
