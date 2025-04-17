<?php
include 'sql_conn.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $videoTitle = $_POST['videoTitle'];
    $category = $_POST['category'];

    $uploadDir = "uploads/Admin/{$category}/"; // Use double quotes for variable interpolation
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $uploadFile = $uploadDir . basename($_FILES['videoFile']['name']);
    $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);


    // Check if the file is a video
    if ($fileType === 'mp4' || $fileType === 'webm' || $fileType === 'ogg') {


        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['videoFile']['tmp_name'], $uploadFile)) {
            // Insert video details into the database
            $fileName = $uploadDir.$_FILES['videoFile']['name'];
            $query = "INSERT INTO external_abilities (title, category, file_name) VALUES (?, ?, ?)";

            // Using prepared statement to prevent SQL injection
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $videoTitle, $category, $fileName);

            if ($stmt->execute()) {
                echo "<script>alert('Video uploaded successfully');window.location.href='upload_external_abilities.php';</script>";
            } else {
                echo "<script>alert('Video upload unsuccessful');window.location.href='upload_external_abilities.php';</script>";            }

            $stmt->close();
        } else {
         echo "<script>alert('Error moving files');window.location.href='upload_external_abilities.php';</script>";
        }
    } else {
        echo "<script>alert('Please insert videos only');window.location.href='upload_external_abilities.php';</script>";
    }
}
