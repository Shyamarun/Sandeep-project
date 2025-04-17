<?php
session_start();
include 'sql_conn.php';
$faculty_id = $_SESSION['faculty_id']; // Assuming this file sets up a MySQLi connection as $conn

$createTableSQL = "
CREATE TABLE IF NOT EXISTS permission_teacher (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id VARCHAR(255) NOT NULL,
    facultyName VARCHAR(255) NOT NULL,
    contactNumber VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    stage VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'Waiting',
    recipient VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

$conn->query($createTableSQL);
$stmt = $conn->prepare("SELECT facultyName,contactNumber,clg_code,stream FROM master_faculty WHERE faculty_id = ?");
$stmt->bind_param("s", $faculty_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $designation = $_POST["designation"];
    $subject = $conn->real_escape_string($_POST['subject']);
    $body = $conn->real_escape_string($_POST['body']);
    $body = str_replace("rnl", "\n", $body); // Replace 'rnl' with a proper newline character
    $body = str_replace("\r\n", "\n", $body); // Convert Windows newlines to Unix style if needed
    $body = str_replace("\r", "", $body); // Remove carriage returns if they are not needed
    $body = str_replace("\\", "", $body); // This removes all backslashes
    $body = str_replace("\n", " ", $body); 
    $body = str_replace("\r", " ", $body);
    $body = str_replace("\t", " ", $body);
    $body = str_replace("\v", " ", $body);
    $body = str_replace("\f", " ", $body);
    $body = str_replace("\0", " ", $body);
    $body = str_replace("\x0B", " ", $body);
    $body = str_replace("\x1A", " ", $body);
    $body = str_replace("rnl", " ", $body); // This removes all backslashes
    $recipient = $result['clg_code'] . $result['stream'] . $designation;
    $facultyName = $result['facultyName'];
    $contactNumber = $result['contactNumber'];
    $stage = "Sent to " . $designation;
    // Insert data into permission_student table
    $insertSQL = "INSERT INTO permission_teacher (faculty_id,facultyName, contactNumber,subject, body, stage, status, recipient) VALUES (?, ?, ?,? ,?,?, 'Waiting', ?)"; 
    $insertStmt = $conn->prepare($insertSQL);
    $insertStmt->bind_param("sssssss", $faculty_id, $facultyName,$contactNumber,$subject, $body,$stage,$recipient);
    $insertStmt->execute();

    echo "<script>alert('Permission request submitted successfully.');window.location.href='permission_ind_fac.php';</script>";
}
?>