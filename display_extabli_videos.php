<?php
include 'sql_conn.php';
session_start();
// Check the connection
$query = "SELECT * FROM external_abilities";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $videoId = $row['id'];
        $videoTitle = $row['title'];
        $videoCategory = $row['category'];
        $videoFileName = $row['file_name'];

        // Display video details with a delete option
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $videoTitle . '</h5>';
        echo '<p class="card-text">Category: ' . $videoCategory . '</p>';
        echo '<video width="320" height="240" controls>';
        echo '<source src="' . $videoFileName . '" type="video/mp4">';
        echo 'Your browser does not support the video tag.';
        echo '</video>';
        echo '<form method="post" class="delete-form" id="deleteForm' . $videoId . '">';
        echo '<input type="hidden" name="action" value="deleteVideo">';
        echo '<input type="hidden" name="videoId" value="' . $videoId . '">';
        echo '<button type="button" class="btn btn-danger" onclick="deleteVideo(' . $videoId . ')">Delete</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<script>alert('Error fetching videos');window.location.href='upload_external_abilities.php';</script>";
}

// Rest of the code remains unchanged
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function deleteVideo(videoId) {
        if (confirm('Are you sure you want to delete this video?')) {
            $.ajax({
                type: 'POST',
                url: 'delete_external_abilities.php', // Update this to the correct path
                data: {
                    action: 'deleteVideo',
                    videoId: videoId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Reload the page or update the video list on the page
                        location.reload();
                    } else {
                        alert('Error: ' + response.error);
                        window.location.href = 'upload_external_abilities.php';
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' ' + errorThrown);
                    window.location.href = 'upload_external_abilities.php';
                }
            });
        }
    }
</script>