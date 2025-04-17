<?php
session_start();
if (isset($_FILES['video'])) {
    include 'sql_conn.php';

    // Get form data
    $reg_num = $_POST['reg_num'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    // Handle video upload
    $uploadDirectory = "uploads/thoughts/{$reg_num}/";
    if (!file_exists($uploadDirectory)) {
        // Create directory if not exist
        mkdir($uploadDirectory, 0777, true);
    }

    $videoFileName = basename($_FILES["video"]["name"]);
    $videoTargetPath = $uploadDirectory . $videoFileName;

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $videoTargetPath)) {
        // File uploaded successfully, insert data into the database
        $stmt = $conn->prepare("INSERT INTO thoughts (title, description, video_path, reg_num) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $videoTargetPath, $reg_num);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error storing data in the database: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading the video.']);
    }

    // Close the database connection
    $conn->close();
}
