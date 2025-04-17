<?php
include 'sql_conn.php';
session_start();
// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_num = $_POST['reg_num'];
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $bio = mysqli_real_escape_string($conn, $_POST["bio"]);

    // Handle profile picture upload
    $targetDir = "uploads/thoughts/{$reg_num}/";
    if (!file_exists($targetDir)) {
        // Create directory if not exist
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]);

    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
        // Fetch existing values
        $selectSQL = "SELECT bio, profile_photo FROM thoughts_profile WHERE reg_num = '$reg_num'";
        $result = $conn->query($selectSQL);

        // Check if the query was successful
        if ($result) {
            $row = $result->fetch_assoc();
            $existingBio = $row['bio'];
            $existingProfilePhoto = $row['profile_photo'];
        } else {
            // Handle the error or provide default values
            $existingBio = 'Default Bio';
            $existingProfilePhoto = 'Default Profile Photo';
        }

        // Update or insert into the database
        $sql = "INSERT INTO thoughts_profile (username, profile_photo, bio, reg_num) 
        VALUES ('$username', '$targetFile', '$bio', '$reg_num') 
        ON DUPLICATE KEY UPDATE bio = '$existingBio', profile_photo = '$existingProfilePhoto'";

        if ($conn->query($sql) === TRUE) {
            echo "Profile updated successfully";
        } else {
            echo "Error updating profile: " . $conn->error;
        }
    } else {
        echo "File upload failed. Check directory permissions and file path.";
    }
} else {
    echo "Invalid request method";
}
