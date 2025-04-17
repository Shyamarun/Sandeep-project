<?php
include 'sql_conn.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteVideo') {
    $videoId = $_POST['videoId'];

    // Perform the deletion in the database
    $deleteQuery = "DELETE FROM external_abilities WHERE id = $videoId";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'error' => mysqli_error($conn)];
    }

    // Send JSON-encoded response
    header('Content-Type: application/json');
    echo json_encode($response);
    echo "<script>alert('Video Deleted Successfully.'); window.location.href='upload_external_abilities.php';</script>";
} else {
    // Invalid request
    header('HTTP/1.1 400 Bad Request');
    echo "<script>alert('Invalid request.'); window.location.href='upload_external_abilities.php';</script>";
    exit;
}
