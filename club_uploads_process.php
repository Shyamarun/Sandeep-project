<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_num=$_SESSION['reg_num'];
    $projectName = $_POST["projectName"];
    $projectCode = $_POST["projectCode"];
    $description = $_POST["description"];

    // Create a directory path based on user name and project name
    $uploadDir = "uploads/Club/{$reg_num}/{$projectCode}/";

    // Create the directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $targetFile = $uploadDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (file_exists($targetFile)) {
        echo "<script>alert('Sorry, file already exists.');</script>";
        $uploadOk = 0;
    }

    if ($_FILES["file"]["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

    $allowedFormats = array("jpg", "jpeg", "png", "gif", "pdf", "mp4");
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG, GIF, PDF, and MP4 files are allowed.');</script>";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            echo "<script>alert('The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.');</script>";
            include 'sql_conn.php';

            $sql = "INSERT INTO club_uploads (projectName, projectCode, description, filePath,reg_num) 
                    VALUES ('$projectName', '$projectCode', '$description', '$targetFile','$reg_num')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Data inserted successfully.');window.location.href='club_uploads_home.php';</script>";
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . $conn->error."');window.location.href='club_uploads_home.php';</script>";
            }

            $conn->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');window.location.href='club_uploads_home.php';</script>";
        }
    }
}
