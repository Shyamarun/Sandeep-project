<?php
// Check if the form is submitted
if (isset($_POST['upload'])) {
    // Include database connection
    session_start();
    include 'sql_conn.php';
    $faculty_id = $_SESSION['faculty_id'];
    $subject = strtoupper($_POST['subject']);
    $lesson = $_POST['lesson'];
    $topic = $_POST['topic'];

    // Retrieve all class_ids where subject_name matches
    $classIdsQuery = "SELECT class_id FROM master_sub WHERE subject_name = '$subject'";
    $classIdsResult = $conn->query($classIdsQuery);
    $allInsertionsSuccessful = true; // To keep track of insertions

    if ($classIdsResult->num_rows > 0) {
        while ($row = $classIdsResult->fetch_assoc()) {
            $class_id = $row['class_id'];

            // File upload
            $target_dir = "uploads/CLV/{$class_id}/{$subject}/{$lesson}/{$topic}/";
            $video_path = $target_dir . basename($_FILES["video"]["name"]);

            // Create the directory if it does not exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Move uploaded file to the destination directory
            if (move_uploaded_file($_FILES["video"]["tmp_name"], $video_path)) {
                // Insert data into the database
                $query = "INSERT INTO clv_videos (class_id, subject, lesson, topic, video_path) VALUES ('$class_id', '$subject', '$lesson', '$topic', '$video_path')";

                // Prepare and execute the statement
                $stmt = $conn->prepare($query);
                if (!$stmt->execute()) {
                    // If any insertion fails
                    $allInsertionsSuccessful = false;
                    echo "<script>alert('Error: " . $stmt->error."');</script>";
                    break; // Stop the loop if an error occurs
                }
                $stmt->close();
            } else {
                // Display an error message for file upload failure
                $allInsertionsSuccessful = false;
                echo "<script>alert('Error moving video file to the destination directory.');</script>";
                break; // Stop the loop if an error occurs
            }
        }
    } else {
        echo "<script>alert('No class ids found for the subject.');window.location.href = 'clv.php';</script>";
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect or show messages after loop completion
    if ($allInsertionsSuccessful) {
        echo "<script>alert('All records inserted successfully');window.location.href = 'clv.php';</script>";
    } else {
        echo "<script>alert('Error inserting records');window.location.href = 'clv.php';</script>";
    }
}
// Redirect back to the index page if the form is not submitted
?>
