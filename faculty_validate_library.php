<?php
// Assuming you have a method to connect to your database
include 'sql_conn.php';

$faculty_id = $_POST['faculty_id'];
$password = $_POST['password'];

// SQL to check the existence of faculty_id and password
$sql = "SELECT * FROM master_faculty WHERE faculty_id = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $faculty_id, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: upload_files_library.php?faculty_id=$faculty_id");
    exit();
} else {
    echo "Invalid Faculty ID or Password";
    // Optionally, redirect back to login page or show error
}

// Close connection
$stmt->close();
$conn->close();
?>
