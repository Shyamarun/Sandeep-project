<?php
session_start();
include 'sql_conn.php'; // Assuming this file sets up a MySQLi connection as $conn

// Create permission_student table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS permission_student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reg_num VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    semester INT NOT NULL,
    sem_year int NOT NULL,
    class_id VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    stage VARCHAR(255) NOT NULL DEFAULT 'Sent to class incharge',
    status VARCHAR(255) NOT NULL DEFAULT 'Waiting',
    recipient VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

$conn->query($createTableSQL);


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_num = $_SESSION['reg_num'];
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

    // Get class_id from stdreg table where reg_num matches with $reg_num
    $stmt = $conn->prepare("SELECT full_name,semester,sem_year,class_id FROM stdreg WHERE reg_num = ?");
    $stmt->bind_param("s", $reg_num);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $class_id = $result ? $result['class_id'] : '';
    $full_name = $result ? $result['full_name'] : '';
    $semester = $result ? $result['semester'] : '';
    $sem_year = $result ? $result['sem_year'] : '';
    // Insert data into permission_student table
    $insertSQL = "INSERT INTO permission_student (reg_num,full_name,semester,sem_year,class_id, subject, body, stage, status, recipient) VALUES (?,?,?,?,?, ?, ?, 'Sent to class incharge', 'Waiting', ?)";
    $insertStmt = $conn->prepare($insertSQL);
    $insertStmt->bind_param("ssiissss", $reg_num,$full_name,$semester,$sem_year, $class_id, $subject, $body, $class_id);
    $insertStmt->execute();

    echo "<script>alert('Permission request submitted successfully.');window.location.href='permission_student.php';</script>";
}
?>