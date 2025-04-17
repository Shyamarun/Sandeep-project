<?php

// Make sure to establish your database connection before this part of the code
include 'sql_conn.php';

if (isset($_POST["submit"])) {
    // Assuming the uploaded file is available in $_FILES['file']
    if ($_FILES['file']['error'] == 0) {
        $fileName = $_FILES['file']['tmp_name'];
        $handle = fopen($fileName, "r");

        // Skip the header row
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Check if all the expected array keys are set
            if (count($data) < 16) {
                // Handle the error for missing columns
                continue;
            }
            // Process the input data
            $clg_name = $data[0];
            $clg_code = strtoupper($data[1]);
            $course = strtoupper(str_replace('.', '', $data[2]));
            $full_name = $data[3];
            $reg_num = strtoupper($data[4]);
            $sem_year = (int)$data[5]; // Cast to integer as per your assumption
            $semester = (int)$data[6]; // Cast to integer as per your assumption
            $branch = strtoupper($data[7]);
            $section = strtoupper($data[8]);
            $class_id = strtoupper($clg_code . $course . $branch . $sem_year . $semester . $section);
            $phone_num = $data[9];
            $parent_name = $data[10];
            $parent_phone_num = $data[11];
            $email = $data[12];
            $blood_g = $data[13];
            $aadhar_num = $data[14]; // Use aadhar_num for user_pass, parent_pass, and cnf_pass
            $pan_num = $data[15];
            $profile_photo = NULL; // Set profile_photo to NULL

            // Prepare the INSERT statement
            $stmt = $conn->prepare("INSERT INTO stdreg (clg_name, clg_code, course, full_name, reg_num, sem_year, semester, branch, section, class_id, phone_num, parent_name, parent_phone_num, email, blood_g, user_pass, cnf_pass, aadhar_num, pan_num, profile_photo, sub_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

            // Bind parameters
            $stmt->bind_param("sssssiisssssssssssss", $clg_name, $clg_code, $course, $full_name, $reg_num, $sem_year, $semester, $branch, $section, $class_id, $phone_num, $parent_name, $parent_phone_num, $email, $blood_g, $aadhar_num, $aadhar_num, $aadhar_num, $pan_num, $profile_photo);

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
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        fclose($handle);

        // Redirect after successful import
        echo "<script>alert('CSV file has been successfully imported.'); window.location.href='admin_home.php';</script>";
    } else {
        // Handle file upload error
        echo "<script>alert('Error uploading file.'); window.location.href='admin_home.php';</script>";
    }
    $stmt->close();
    $stmtCheckExistence->close();
    $stmtInsert->close();
    $conn->close();
}
