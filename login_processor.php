<?php
session_start(); // Start the session at the beginning of the script

include 'sql_conn.php';

// Fetch user input
$user_id = $_POST['user_id'];
$user_pass = $_POST['user_pass'];

// Function to perform the login check
function checkLogin($conn, $query, $user_id, $user_pass, $redirect, $paramType = "ss")
{
    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramType, $user_id, $user_pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Store user data in session
        $_SESSION['user_data'] = $row;
        $_SESSION['logged_in'] = true;
        header("Location: $redirect");
        exit();
    }
}

// Check Student
checkLogin($conn, "SELECT reg_num, class_id FROM stdreg WHERE reg_num = ? AND user_pass = ?", $user_id, $user_pass, "student.php");

// Check Teacher
//checkLogin($conn, "SELECT class_id FROM t_auth WHERE class_id = ? AND class_id_pass = ?", $user_id, $user_pass, "teacher_home_page.php", "si");

// Check Parent
checkLogin($conn, "SELECT user_id FROM parent_reg WHERE user_id = ? AND user_pass = ?", $user_id, $user_pass, "parent.php");

// Check HOD
checkLogin($conn, "SELECT user_id FROM hod_registration WHERE user_id = ? AND user_pass = ?", $user_id, $user_pass, "auth_matrix_home_hod.php");

// Check Principal/Director
checkLogin($conn, "SELECT user_id FROM pri_dir_registration WHERE user_id = ? AND password = ?", $user_id, $user_pass, "auth_matrix_home_page.php");

//Check Faculty
checkLogin($conn, "SELECT faculty_id FROM master_faculty WHERE faculty_id = ? AND password = ?", $user_id, $user_pass, "individual_fac.php");

//Check Examination Cell
checkLogin($conn, "SELECT userID FROM staff_details WHERE userID = ? AND password = ?", $user_id, $user_pass, "insert_results.php");

//Check Office Staff
checkLogin($conn, "SELECT userID FROM admin_details WHERE userID = ? AND password = ?", $user_id, $user_pass, "admin_home.php");
// If credentials do not match any user
echo "<script>alert('Invalid credentials. Please try again.');window.location.href='login_page.php';</script>";
