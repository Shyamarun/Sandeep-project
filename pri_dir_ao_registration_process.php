<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'sql_conn.php';

    $collegeName = $_POST["collegeName"];
    $collegeCode = strtoupper(str_replace('.', '', $_POST['collegeCode']));
    $course = strtoupper(str_replace('.', '', $_POST['course']));
    $fullName = $_POST["fullName"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $designation = $_POST["designation"];
    $userId = strtoupper($collegeCode . $course . $designation);

    // Validate password match
    if ($password != $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit;
    }

    // Process the uploaded profile photo
    $targetDir = "uploads/profile/{$userId}/";
    $profilePhoto = $targetDir . basename($_FILES["profilePhoto"]["name"]);

    // Create the directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (!move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $profilePhoto)) {
        echo "<script>alert('Error uploading profile photo.');</script>";
        exit; // Stop further processing if file upload fails
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO pri_dir_registration (collegeName, collegeCode, course, fullName, contact, email, designation, password, user_id, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $collegeName, $collegeCode, $course, $fullName, $contact, $email, $designation, $password, $userId, $profilePhoto);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!');window.location.href='pri_dir_ao_register.php';</script>";
    } else {
        echo "<script>alert('Error inserting data into the database.');window.location.href='pri_dir_ao_register.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Please submit the form');</script>";
}
