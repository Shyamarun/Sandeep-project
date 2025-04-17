<?php
include 'sql_conn.php'; // Include your SQL connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $mobileNumber = $_POST['mobileNumber'];
    $regNum = $_POST['reg_num'];
    $password = $_POST['password'];
    $rePassword = $_POST['rePassword'];

    if ($password !== $rePassword) {
        echo "<script>alert('Passwords do not match.');</script>";
        return; // Exit if passwords do not match
    }

    // Check if a record with the given reg_num exists
    $query = "SELECT * FROM stdreg WHERE reg_num = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $regNum);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Record exists, proceed to update
        $updateQuery = "INSERT INTO parent_reg (user_id, full_name, mobile_number, user_pass) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name = VALUES(full_name), mobile_number = VALUES(mobile_number), user_pass = VALUES(user_pass)";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ssss", $regNum, $fullName, $mobileNumber, $password);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "<script>alert('Parent details updated successfully.');window.location.href='parent_reg.php';</script>";
        } else {
            echo "<script>alert('No updates were made.');window.location.href='parent_reg.php';</script>";
        }
        $updateStmt->close();
    } else {
        echo "<script>alert('No record found with the provided Roll Number.');window.location.href='parent_reg.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
