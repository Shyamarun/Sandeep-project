<?php
// password_reset_processor.php
include 'sql_conn.php'; // Make sure you have this file set up with your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the email exists in your database
    // Assuming you have a users table with an email column
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, generate token and expiration date
        $token = bin2hex(random_bytes(50)); // Generate a secure random token
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

        // Store the token and expiration date in the database
        $insertStmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $insertStmt->bind_param("sss", $email, $token, $expires_at);
        $insertStmt->execute();

        // Send email to user with password reset link (You need to configure mail settings)
        $resetLink = "http://yourwebsite.com/reset_password_form.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Please click on the following link to reset your password: " . $resetLink;
        $headers = "From: noreply@yourwebsite.com";
        mail($email, $subject, $message, $headers);

        echo "If your email exists in our database, you will receive a password reset link shortly.";
    } else {
        // No user found with that email address
        echo "If your email exists in our database, you will receive a password reset link shortly.";
    }
    $conn->close();
}
?>
