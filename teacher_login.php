<?php
include 'sql_conn.php';

// Get input from the HTML form
$class_id = strtoupper($_POST['class_id']);
$std_pass = strtoupper($_POST['std_pass']);


// Query to check if the input exists in the MySQL table
$query = "SELECT * FROM t_auth WHERE class_id = '$class_id' AND class_id_pass='$std_pass'";
$result = mysqli_query($conn, $query);

// Check if there's a match
if (mysqli_num_rows($result) > 0) {
    $redirect_url = "teacher_home_page.php?class_id=" . urlencode($class_id);
    header("Location: $redirect_url");
    exit();
} else {
    echo '<script>';
    echo 'alert("Wrong Credentials");';
    echo 'setTimeout(function() {';
    echo '  window.location.href = "login_page.php";';
    echo '}, 10);';
    echo '</script>';
    exit();
    }

// Close the MySQL connection
$conn->close();
?>