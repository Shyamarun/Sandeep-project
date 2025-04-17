<?php
// Include database connection
include 'sql_conn.php';

// Check if video ID is provided in the URL
if (isset($_GET['id'])) {
    $video_id = $_GET['id'];

    // Delete video from the database
    $query = "DELETE FROM clv_videos WHERE video_id = $video_id";
    mysqli_query($conn, $query);
}

// Redirect back to the index page
echo "<script>alert('Deletion Successful');window.location.href = 'clv.php';</script>"
?>
