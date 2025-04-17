<?php
include 'sql_conn.php';
$sem_year = 0;
$semester = 0;
$clg_name = $_POST['clg_name'];
$clg_code = strtoupper($_POST['clg_code']);
$course = strtoupper($_POST['course']);
$full_name = $_POST['full_name'];
$reg_num = strtoupper($_POST['reg_num']);
$sem_year = $_POST['sem_year'];
$semester = $_POST['semester'];
$branch = strtoupper($_POST['branch']);
$section = $_POST['section'];
$phone_num = $_POST['phone_num'];
$parent_name = $_POST['parent_name'];
$parent_phone_num = $_POST['parent_phone_num'];
$email = $_POST['email'];
$blood_g = $_POST['blood_g'];
$user_pass = $_POST['user_pass'];
$cnf_pass = $_POST['cnf_pass'];
$aadhar_num = $_POST['aadhar_num'];
$pan_num = $_POST['pan_num'];
// Remove '.' from $course if present
$course = str_replace('.', '', $course);

// Concatenate variables and convert to uppercase
$class_id = strtoupper($clg_code . $course . $branch . $sem_year . $semester . $section);
$random_number = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
$class_id_pass = $class_id . $random_number;


$profile_photo_path = '';
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
    $allowed_exts = array("jpg", "jpeg", "png");
    $file_name = $_FILES['profile_photo']['name'];
    $file_tmp = $_FILES['profile_photo']['tmp_name'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed_exts)) {
        $upload_dir = "uploads/profile/{$reg_num}";
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true)) {
            echo "<script>alert('Error: Unable to create directory');</script>";
        }

        $safe_file_name = preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($file_name, PATHINFO_FILENAME)) . "." . $ext;
        $profile_photo_path = "$upload_dir/$safe_file_name";

        if (!move_uploaded_file($file_tmp, $profile_photo_path)) {
            echo "<script>alert('Error: Unable to upload file.');</script>";
        }
    } else {
        echo "<script>alert('Invalid File extension');</script>";
    }
} else {
    echo "<script>alert('File upload error');</script>";
}

if ($user_pass === $cnf_pass) {
    $sql = "INSERT INTO stdreg (clg_name, clg_code, course, full_name, reg_num, sem_year, semester, branch, section, class_id, phone_num, parent_name, parent_phone_num, email, blood_g, user_pass, cnf_pass, aadhar_num, pan_num, profile_photo, sub_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiisssssssssssss", $clg_name, $clg_code, $course, $full_name, $reg_num, $sem_year, $semester, $branch, $section, $class_id, $phone_num, $parent_name, $parent_phone_num, $email, $blood_g, $user_pass, $cnf_pass, $aadhar_num, $pan_num, $profile_photo_path);

    if ($stmt->execute()) {
        // Check if $class_id and $class_id_pass exist in t_auth
        $sqlCheckExistence = "SELECT COUNT(*) AS count FROM t_auth WHERE class_id = ? OR class_id_pass = ?";
        $stmtCheckExistence = $conn->prepare($sqlCheckExistence);
        $stmtCheckExistence->bind_param("ss", $class_id, $class_id_pass);
        $stmtCheckExistence->execute();
        $resultExistence = $stmtCheckExistence->get_result();
        $rowExistence = $resultExistence->fetch_assoc();
        $countExistence = $rowExistence['count'];

        $sqlCheckFee = "SELECT COUNT(*) AS count FROM fees WHERE reg_num = ?";
        $stmtCheckFee = $conn->prepare($sqlCheckFee);
        $stmtCheckFee->bind_param("s", $reg_num);
        $stmtCheckFee->execute();
        $resultFee = $stmtCheckFee->get_result();
        $rowFee = $resultFee->fetch_assoc();
        $countFee = $rowFee['count'];

        $sqlCheckCertificate = "SELECT COUNT(*) AS count FROM certificates WHERE reg_num = ?";
        $stmtCheckCertificate = $conn->prepare($sqlCheckCertificate);
        $stmtCheckCertificate->bind_param("s", $reg_num);
        $stmtCheckCertificate->execute();
        $resultCertificate = $stmtCheckCertificate->get_result();
        $rowCertificate = $resultCertificate->fetch_assoc();
        $countCertificate = $rowCertificate['count'];

        $sqlCheckTeacherInfo = "SELECT COUNT(*) AS count FROM teacher_info WHERE class_id = ?";
        $stmtCheckTeacherInfo = $conn->prepare($sqlCheckTeacherInfo);
        $stmtCheckTeacherInfo->bind_param("s", $class_id);
        $stmtCheckTeacherInfo->execute();
        $resultTeacherInfo = $stmtCheckTeacherInfo->get_result();
        $rowTeacherInfo = $resultTeacherInfo->fetch_assoc();
        $countTeacherInfo = $rowTeacherInfo['count'];

        if ($countExistence == 0) {
            // $class_id and $class_id_pass do not exist, insert them into t_auth
            $sqlInsert = "INSERT INTO t_auth (class_id, class_id_pass) VALUES (?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ss", $class_id, $class_id_pass);
            $stmtInsert->execute();
        }

        if ($countFee == 0) {
            $sqlFee = "INSERT INTO fees (reg_num, class_id) VALUES (?, ?)";
            $stmtFee = $conn->prepare($sqlFee);
            $stmtFee->bind_param("ss", $reg_num, $class_id);
            $stmtFee->execute();
        }

        if ($countCertificate == 0) {
            $sqlCertificates = "INSERT INTO certificates (reg_num, class_id) VALUES (?, ?)";
            $stmtCertificates = $conn->prepare($sqlCertificates);
            $stmtCertificates->bind_param("ss", $reg_num, $class_id);
            $stmtCertificates->execute();
        }

        if ($countTeacherInfo == 0) {
            $sqlTeacherInfo = "INSERT INTO teacher_info (class_id) VALUES (?)";
            $stmtTeacherInfo = $conn->prepare($sqlTeacherInfo);
            $stmtTeacherInfo->bind_param("s", $class_id);
            $stmtTeacherInfo->execute();
        }
        echo "<script>alert('Registration Successful');window.location.href ='create_account_student.html';</script>";
        exit();
    } else {
        echo "<script>alert('Registration Failed');window.location.href='create_account_student.html';</script>";
    }
} else {
    echo "<script>alert('Registration Unsuccessful');window.location.href='create_account_student.html';</script>";
}

$stmt->close();
$stmtCheckExistence->close();
$stmtInsert->close();
$conn->close();
