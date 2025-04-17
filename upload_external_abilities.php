<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Video Gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* Add your custom styles here */
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3>Upload Video</h3>
                <form action="upload_extabli_process.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="videoTitle">Video Title:</label>
                        <input type="text" class="form-control" id="videoTitle" name="videoTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="videoFile">Choose Video File:</label>
                        <input type="file" class="form-control-file" id="videoFile" name="videoFile" accept="video/*" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Select Category:</label>
                        <select id="category" class="form-control" name="category" required>
                            <option value="groupA">Group A</option>
                            <option value="groupB">Group B</option>
                            <option value="groupC">Group C</option>
                            <option value="groupD">Group D</option>
                            <option value="Computer science">Computer science</option>
                            <option value="Basic Medical Description">Basic Medical Description</option>
                            <option value="Basic Law points">Basic Law points</option>
                            <option value="Business School">Business School</option>
                            <option value="Chess">Chess</option>
                            <option value="Caroms">Caroms</option>
                            <option value="Puzzle Solving">Puzzle Solving</option>
                            <option value="Karate">Karate</option>
                            <option value="Singing">Singing</option>
                            <option value="Dancing">Dancing</option>
                            <option value="Musical Instruments">Musical Instruments</option>
                            <option value="Drawing And Art">Drawing And Art</option>
                            <option value="Photography">Photography</option>
                            <option value="Video Edeting">Video Edeting</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Video</button>
                </form>

                <h3>Selected Videos</h3>
                <div id="videoContainer">
                    <?php include 'display_extabli_videos.php'; ?>
                </div>
                <hr>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function deleteVideo(videoId) {
            console.log('Deleting video with ID:', videoId);

            if (confirm('Are you sure you want to delete this video?')) {
                $.ajax({
                    type: 'POST',
                    url: 'delete_external_abilities.php',
                    data: {
                        action: 'deleteVideo',
                        videoId: videoId
                    },
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Add this line
                    dataType: 'json',
                    // Inside the success callback of your AJAX request
                    success: function(response) {
                        console.log('Delete response:', response);

                        if (response.success !== undefined) {
                            if (response.success) {
                                // Reload the page or update the video list on the page
                                location.reload();
                            } else {
                                alert('Error: Deletion failed');
                                window.location.href = 'upload_external_abilities.php';
                            }
                        } else {
                            alert('Error: Invalid response structure');
                            window.location.href = 'upload_external_abilities.php';
                        }
                    },
                });
            }
        }
    </script>

</body>

</html>