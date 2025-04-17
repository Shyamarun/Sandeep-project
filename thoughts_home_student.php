<?php
include 'sql_conn.php';
session_start();
$reg_num = $_SESSION['reg_num'];
    // Modify the query to retrieve multiple videos
    $query = "SELECT reg_num, title, description,video_path FROM thoughts ORDER BY RAND() LIMIT 5";
    $statement = $conn->prepare($query);

    if (!$statement) {
        echo "<script>alert('Query error: " . $conn->error."');window.location.href='student.php';</script>";
    }

    // Execute the query
    $statement->execute();

    // Get the result set
    $result = $statement->get_result();

    if ($result === false) {
        echo "<script>alert('Query error: " . $conn->error."');window.location.href='student.php';</script>";
    }

    $videos = [];

    // Fetch videos if there are any
    while ($row = $result->fetch_assoc()) {
        $videos[] = [
            'reg_num' => $row['reg_num'],
            'title' => $row['title'],
            'description' => $row['description'],
            'video_path' => $row['video_path']
        ];
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Videos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Include Hammer.js for touch events -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        #video-wrapper {
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: hidden;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            /* Enable touch-scrolling on iOS devices */
        }

        .video-card {
            flex: 0 0 100%;
            box-sizing: border-box;
            position: relative;
            scroll-snap-align: start;
        }

        .video-card video {
            width: 100vw;
            /* Set video width to page width */
            height: 100vh;
            /* Fullscreen on laptop screens */
            object-fit: cover;
        }

        .video-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px;
            box-sizing: border-box;
        }

        .button-container {
            position: absolute;
            top: 5%;
            right: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .button-container button {
            font-size: 24px;
            margin-bottom: 10px;
            background-color: #333;
            border: none;
            color: white;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }

        .button-container-left {
            position: absolute;
            top: 5%;
            left: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .button-container-left button {
            font-size: 24px;
            margin-bottom: 10px;
            background-color: #333;
            border: none;
            color: white;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }

        .comment-section {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            display: none;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-secondary {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-primary,
        .btn-secondary,
        .btn-info {
            color: #fff;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div id="video-wrapper">
                    <?php foreach ($videos as $video) : ?>
                        <div class="video-card">
                            <video class="w-100" controls autoplay loop muted playsinline webkit-playsinline controlsList="nodownload nofullscreen noremoteplayback" disablepictureinpicture>
                                <source src="<?php echo $video['video_path']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="button-container">
                                <!--button class="like-btn" onclick="toggleLike()">
                                    <i class="heart-icon fas fa-heart"></i>
                                </button>
                                <button class="comment-btn" onclick="toggleComments()">
                                    <i class="fas fa-comment"></i>
                                </button>
                                <button class="share-btn" onclick="shareVideo()">
                                    <i class="fas fa-share"></i>
                                </button-->
                                <button class="scroll-btn" onclick="scrollToNextVideo()">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class="video-info">
                                <h4 id="video_title"><?php echo $video['title']; ?></h4>
                                <p id="video_description"><?php echo $video['description']; ?></p>
                                <p>Uploaded by: <span id="video_username"><?php echo $video['reg_num']; ?></span></p>
                            </div>
                            <div class="button-container-left">
                                <button class="prev-btn" onclick="scrollToPrevVideo()">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <!--div class="comment-section">
                                <h5>Comments</h5>
                                <div class="comments" id="comments"></div>
                                <textarea class="form-control mt-2" placeholder="Add a comment"></textarea>
                                <button class="btn btn-primary mt-2" onclick="postComment()">Post Comment</button>
                            </div-->
                        </div>
                    <?php endforeach; ?>
                </div>
                <nav class="navbar fixed-bottom navbar-light bg-light">
                    <a class="navbar-brand" href="thoughts_upload_page.php?reg_num=<?php echo $reg_num; ?>"><i class="fas fa-film"></i>Add Thoughts</a>
                    <a class="navbar-brand" href="thoughts_profile.php?reg_num=<?php echo $reg_num; ?>"><i class="fas fa-user"></i> Profile</a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        'use strict';

        // Hide all video controls
        var videoElements = document.querySelectorAll('video');
        videoElements.forEach(function(video) {
            video.controls = false;
        });

        var currentVideoIndex = 0;
        var videoWrapper = document.getElementById('video-wrapper');
        var videos = document.querySelectorAll('.video-card');

        // Initialize Hammer.js for touch events
        var hammer = new Hammer(videoWrapper);
        hammer.get('swipe').set({
            direction: Hammer.DIRECTION_HORIZONTAL
        });

        // Update current video index based on swipe events
        hammer.on('swipe', function(event) {
            if (event.direction === Hammer.DIRECTION_LEFT) {
                scrollToNextVideo();
            } else if (event.direction === Hammer.DIRECTION_RIGHT) {
                scrollToPrevVideo();
            }
        });

        // Update video info based on current video index
        function updateVideoInfo() {
            videos.forEach(function(video, index) {
                if (index === currentVideoIndex) {
                    video.querySelector('.video-info').style.opacity = 1;
                    video.querySelector('.button-container').style.opacity = 1;
                    video.querySelector('.button-container-left').style.opacity = 1;
                } else {
                    video.querySelector('.video-info').style.opacity = 0;
                    video.querySelector('.button-container').style.opacity = 0;
                    video.querySelector('.button-container-left').style.opacity = 0;
                }
            });
        }

        function toggleLike() {
            alert('Video Liked!');
        }

        function toggleComments() {
            var commentsContainer = videos[currentVideoIndex].querySelector('.comment-section');
            commentsContainer.style.display = commentsContainer.style.display === 'none' ? 'block' : 'none';
        }

        function shareVideo() {
            alert('Share functionality to be implemented.');
        }

        function postComment() {
            var commentInput = videos[currentVideoIndex].querySelector('.comment-section textarea').value;
            var commentsDiv = videos[currentVideoIndex].querySelector('.comment-section .comments');
            commentsDiv.innerHTML += '<p>' + commentInput + '</p>';
            videos[currentVideoIndex].querySelector('.comment-section textarea').value = '';
        }

        function scrollToNextVideo() {
            if (currentVideoIndex < videos.length - 1) {
                currentVideoIndex++;
                videoWrapper.scrollLeft = currentVideoIndex * window.innerWidth;
                updateVideoInfo();
            }
        }

        function scrollToPrevVideo() {
            if (currentVideoIndex > 0) {
                currentVideoIndex--;
                videoWrapper.scrollLeft = currentVideoIndex * window.innerWidth;
                updateVideoInfo();
            }
        }
    </script>
</body>

</html>