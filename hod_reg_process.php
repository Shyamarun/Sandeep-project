<?php
include 'sql_conn.php'; // Include your SQL connection file here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clgName = $_POST['collegeName'];
    $clgCode = strtoupper(str_replace('.', '', $_POST['collegeCode']));
    $stream = strtoupper(str_replace('.', '', $_POST['stream']));
    $branch = strtoupper(str_replace('.', '', $_POST['branch']));
    $userId = strtoupper($clgCode . $stream . $branch . 'HOD');
    $name = $_POST['name'];
    $phoneNum = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $userPass = $_POST['password'];

    // Process file upload
    $uploadDir = 'uploads/profile/' . $userId . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($_FILES['profile_photo']['name']);
    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
        $photoPath = $uploadFile;
    } else {
        echo "<script>alert('File upload error.');</script>";
        $photoPath = ''; // Handle the case where the file is not uploaded
    }

    // SQL to insert data including file path
    $sql = "INSERT INTO HOD_registration (clg_name, clg_code, stream, branch, user_id, name, phone_num, email, user_pass, photo_path,sub_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,NOW())";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssssss", $clgName, $clgCode, $stream, $branch, $userId, $name, $phoneNum, $email, $userPass, $photoPath);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Registration Successful. Your User ID is: $userId');window.location.href='hod_reg.php';</script>";
        } else {
            echo "<script>alert('Registration Unsuccessfull');window.location.href='hod_reg.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Registration Unsuccessfull');window.location.href='hod_reg.php';</script>";
    }
}
$conn->close();
?>
