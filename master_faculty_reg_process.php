<?php
include 'sql_conn.php'; // Ensure this points to the correct file for your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $regulation = strtoupper($_POST['regulation']);
    $clg_code = strtoupper($_POST['clg_code']);
    $facultyName = $conn->real_escape_string($_POST['facultyName']);
    $contactNumber = $conn->real_escape_string($_POST['contactNumber']);
    $whatsappNumber = $conn->real_escape_string($_POST['whatsappNumber']);
    $contactEmail = $conn->real_escape_string($_POST['contactEmail']);
    $stream = strtoupper(str_replace('.', '', $_POST['stream']));
    $subjectTeaching = isset($_POST['subjectTeaching']) ? $_POST['subjectTeaching'] : [];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Password validation
    if ($password !== $confirmPassword) {
        echo 'Passwords do not match.';
        exit();
    }

    // Handle profile photo upload
    $uploadDir = 'uploads/faculty/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $profilePhotoPath = '';

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $tmpName = $_FILES['profile_photo']['tmp_name'];
        $photoName = basename($_FILES['profile_photo']['name']);
        $uploadPath = $uploadDir . $photoName;

        // Move uploaded file to destination
        move_uploaded_file($tmpName, $uploadPath);

        // Set the profile photo path for database insertion
        $profilePhotoPath = $uploadPath;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO master_faculty (regulation, clg_code, facultyName, contactNumber, whatsappNumber, contactEmail, stream, subject_name, subject_code, subject_abb, password, confirmPassword, faculty_id, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Transaction start
    $conn->begin_transaction();

    // Loop through each subject and insert it along with the faculty info
    foreach ($subjectTeaching as $subject_name) {
        $subject_name = strtoupper($conn->real_escape_string($subject_name));
        $stream = strtoupper(str_replace('.', '', $stream));

        // Abbreviation logic
        if (preg_match('/\d/', $subject_name, $matches)) {
            $parts = preg_split('/[^A-Za-z]/', $subject_name, -1, PREG_SPLIT_NO_EMPTY);
            $subject_abb = '';
            if (count($parts) > 0) {
                $subject_abb .= substr($parts[0], 0, 1);
            }
            $subject_abb .= $matches[0];
        } else {
            $words = explode(' ', $subject_name);
            if (count($words) > 1) {
                $subject_abb = substr($words[0], 0, 1) . substr($words[1], 0, 1);
            } else {
                $subject_abb = substr($subject_name, 0, 3);
            }
        }

        $subject_abb = strtoupper($subject_abb);
        $subject_code = strtoupper($regulation . $stream . $subject_abb);

        // Bind and execute
        if (!$stmt->bind_param("ssssssssssssss", $regulation, $clg_code, $facultyName, $contactNumber, $whatsappNumber, $contactEmail, $stream, $subject_name, $subject_code, $subject_abb, $password, $confirmPassword, $contactEmail, $profilePhotoPath)) {
            echo 'Binding parameters failed: ' . htmlspecialchars($stmt->error);
            $conn->rollback();
            exit();
        }

        if (!$stmt->execute()) {
            echo 'Execute failed: ' . htmlspecialchars($stmt->error);
            $conn->rollback();
            exit();
        }
    }

    // Commit the transaction
    $conn->commit();
    echo "<script>alert('Registration Succesfull');window.location.href='master_faculty_reg.php'</script>";

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
