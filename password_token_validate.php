<?php
// reset_password_processor.php
include 'sql_conn.php'; // Ensure this is your connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password']) && isset($_GET['token'])) {
    $password = $_POST['password'];
    $token = $_GET['token'];

    // Verify the token's validity and expiration
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];

        // Update the user's password
        // Ensure you hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        $updateStmt->execute();

        // Optionally, delete the token from password_resets table to prevent reuse
        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $deleteStmt->bind_param("s", $token);
        $deleteStmt->execute();

        echo "Your password has been updated successfully.";
    } else {
        echo "This token is invalid or has expired.";
    }
    $conn->close();
}
?>
