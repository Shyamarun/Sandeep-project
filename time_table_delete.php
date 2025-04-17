<?php
include 'sql_conn.php';

if (isset($_POST['fileId'])) {
    $fileId = $_POST['fileId']; // Sanitize and validate input as needed

    $query = "DELETE FROM time_table WHERE id = $fileId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to delete the file']);
    }
} else {
    echo json_encode(['error' => 'File ID not provided']);
}
