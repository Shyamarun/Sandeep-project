<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'sql_conn.php'; // Ensure this points to the correct file for your database connection

if (isset($_GET['course']) && isset($_GET['collegeCode'])) {
    $course = $conn->real_escape_string($_GET['course']);
    $collegeCode = $conn->real_escape_string($_GET['collegeCode']);

    $course = strtoupper(str_replace('.', '', $course));
    $collegeCode = strtoupper(str_replace('.', '', $collegeCode));

    $sql = "SELECT DISTINCT subject
            FROM subjects 
            WHERE course = ? AND collegeCode = ? 
            ORDER BY subject ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $course, $collegeCode);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row['subject'];
    }

    header('Content-Type: application/json');
    echo json_encode($subjects);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Course or College Code not specified"]);
}
?>
