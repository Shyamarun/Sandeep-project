<?php
include 'show_notification.php';
include 'sql_conn.php';

// Check if user_id and user_pass are set and not empty
if (isset($_POST['user_id']) && isset($_POST['user_pass']) && !empty($_POST['user_id']) && !empty($_POST['user_pass'])) {
    $user_id = strtoupper($_POST['user_id']);
    $user_pass = $_POST['user_pass'];

    // Prepare SQL based on user_id prefix
    if (strpos($user_id, 'HOD') !== false) {
        $stmt = $conn->prepare("SELECT * FROM HOD_registration WHERE user_id = ? AND user_pass = ?");
    } elseif (strpos($user_id, 'DIR') !== false || strpos($user_id, 'PRI') !== false || strpos($user_id, 'VPRI') !== false || strpos($user_id, 'AO') !== false) {
        $stmt = $conn->prepare("SELECT * FROM pri_dir_registration WHERE user_id = ? AND password = ?");
    }

    // Bind parameters and execute
    if (isset($stmt)) {
        $stmt->bind_param("ss", $user_id, $user_pass);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there's a match
        if ($result->num_rows > 0) {
            // Redirect based on the user_id prefix
            if (strpos($user_id, 'HOD') !== false) {
                // Redirect to auth_matrix_home_hod.php for HODs
                header("Location: auth_matrix_home_hod.php?user_id=" . urlencode($user_id));
            } else {
                // Redirect to auth_matrix_home_page.php for other users
                header("Location: auth_matrix_home_page.php?user_id=" . urlencode($user_id));
            }
            exit();
        } else {
            echo redirectToPage("Password mismatch", "login_page.php");
        }
    } else {
        echo redirectToPage("Invalid User ID", "login_page.php");
    }
} else {
    echo redirectToPage("User ID or Password not provided", "login_page.php");
}

// Check if $conn is a valid MySQLi object before closing the connection
if ($conn instanceof mysqli) {
    mysqli_close($conn);
}
?>
