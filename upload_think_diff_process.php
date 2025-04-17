<?php
include 'sql_conn.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username, description, and other form data
    $username = $conn->real_escape_string($_POST["username"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $reg_num=$_SESSION['reg_num'];
    // Process file upload (you may need to adapt this part based on your requirements)
    $targetDir = "uploads/Think_Different/{$username}/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    // Create the directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777,
            true
        ); // You can adjust the permission mode as needed
    }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {

        // Store information in the database
        $sql = "INSERT INTO file_uploads (username, description, file_path,reg_num) VALUES ('$username', '$description', '$targetFile','$reg_num')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record added to database successfully');window.location.href='upload_think_diff_home.php';</script>";
        } else {
            echo "<script>alert('Error adding record to database: " . $conn->error.");window.location.href='upload_think_diff_home.php';</script>";
        }
    } else {
        // Error handling for file upload
        echo "<script>alert('Error uploading file');window.location.href='upload_think_diff_home.php';</script>";
    }
} else {
    // Handle invalid request method
    echo "<script>alert('Invalid request method');window.location.href='upload_think_diff_home.php';</script>";
}

// Close the database connection
$conn->close();
