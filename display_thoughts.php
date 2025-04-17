<?php
include 'sql_conn.php';
try {
    // Modify the query to retrieve multiple videos
    $query = "SELECT id, title, description, username, video_path FROM thoughts ORDER BY RAND() LIMIT 5";
    $statement = $conn->prepare($query);

    if (!$statement) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    $statement->execute();
    $result = $statement->get_result();

    if ($result === false) {
        throw new Exception("Query error: " . $conn->error);
    }

    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'username' => $row['username'],
            'video_path' => $row['video_path']
        ];
    }

    $conn->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Videos</title>
    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Using Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        #myVideo {
            width: 100%;
            /* Adjust as needed */
            max-height: 400px;
            /* Adjust as needed */
        }

        .video-info {
            margin-top: 20px;
            /* Add margin to separate video and info */
        }

        .button-container {
            display: flex;
            gap: 10px;
        }

        .comment-section {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            display: none;
            /* initially hide the comment section */
        }

        /* Customize the button colors if needed */
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="col-md-8">
                    <video id="myVideo" class="w-100" controls autoplay loop muted>
                        <source src="<?php echo $videos[0]['video_path']; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-info">
                    <h4 id="video_title"><?php echo $videos[0]['title']; ?></h4>
                    <p id="video_description"><?php echo $videos[0]['description']; ?></p>
                    <p>Uploaded by: <span id="video_username"><?php echo $videos[0]['username']; ?></span></p>
                    <div class="button-container">
                        <button class="like-btn btn btn-outline-danger" onclick="toggleLike()">
                            <i class="heart-icon fas fa-heart"></i> Like
                        </button>
                        <button class="comment-btn btn btn-outline-info" onclick="toggleComments()">Comment</button>
                        <button class="share-btn btn btn-outline-secondary" onclick="shareVideo()">
                            <i class="fas fa-share"></i> Share
                        </button>
                    </div>
                </div>
                <div id="commentsContainer" style="display: none;">
                    <h5>Comments</h5>
                    <!-- Display comments here -->
                    <div id="comments"></div>
                    <textarea id="commentInput" class="form-control mt-2" placeholder="Add a comment"></textarea>
                    <button class="btn btn-primary mt-2" onclick="postComment()">Post Comment</button>
                </div>
            </div>
        </div>


        <script>
            'use strict';
            document.getElementById('myVideo').removeAttribute('controls');

            var videos = <?php echo json_encode($videos); ?>;
            var currentVideoIndex = 0;
            var isLiked = false;
            var likeCount = 0; // Initialize like count

            function playCurrentVideo() {
                var video = document.getElementById('myVideo');
                video.src = videos[currentVideoIndex]['video_path'];
                video.load();
                video.play();

                // Update video information
                document.getElementById('video_title').innerText = videos[currentVideoIndex]['title'];
                document.getElementById('video_description').innerText = videos[currentVideoIndex]['description'];
                document.getElementById('video_username').innerText = videos[currentVideoIndex]['username'];

                // Update like button text and count based on current like status
                updateLikeButtonText();
                updateLikeCount();
            }

            function updateLikeButtonText() {
                var likeButton = document.querySelector('.like-btn');
                likeButton.innerText = isLiked ? 'Dislike' : 'Like';
            }

            function updateLikeCount() {
                var likeCountElement = document.getElementById('likeCount');
                likeCountElement.innerText = likeCount;
            }

            // Handle wheel event for video switching
            document.addEventListener('wheel', function(event) {
                // Adjust the sensitivity by changing the value (e.g., 50)
                var sensitivity = 50;

                if (event.deltaY > sensitivity) {
                    playNextVideo();
                } else if (event.deltaY < -sensitivity) {
                    playPreviousVideo();
                }
            });

            function playNextVideo() {
                currentVideoIndex = (currentVideoIndex + 1) % videos.length;
                isLiked = false; // Reset like status when switching to the next video
                likeCount = 0; // Reset like count for the next video
                playCurrentVideo();
            }

            function playPreviousVideo() {
                currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
                isLiked = false; // Reset like status when switching to the previous video
                likeCount = 0; // Reset like count for the previous video
                playCurrentVideo();
            }

            function toggleLike() {
                isLiked = !isLiked;
                // Update the like count based on the like status
                likeCount = isLiked ? likeCount + 1 : likeCount - 1;
                // You can implement your actual like/dislike logic here (e.g., update a database)
                // For now, we'll just show an alert to demonstrate the toggle functionality
                alert(isLiked ? 'Video Liked!' : 'Video Disliked!');
                updateLikeButtonText();
                updateLikeCount();
            }

            function toggleComments() {
                var commentsContainer = document.getElementById('commentsContainer');
                commentsContainer.style.display = commentsContainer.style.display === 'none' ? 'block' : 'none';
            }

            function shareVideo() {
                // You can customize the sharing message based on your requirements
                var shareMessage = encodeURIComponent("Check out this video!");

                // WhatsApp sharing
                var whatsappLink = `https://wa.me/?text=${shareMessage}`;
                openNewWindow(whatsappLink);

                // Email sharing
                var emailLink = `mailto:?subject=Check%20out%20this%20video&body=${shareMessage}`;
                openNewWindow(emailLink);

                // SMS sharing
                var smsLink = `sms:?&body=${shareMessage}`;
                openNewWindow(smsLink);
            }

            function openNewWindow(link) {
                // Open a new window or tab with the specified link
                window.open(link, "_blank");
            }

            function postComment() {
                var commentInput = document.getElementById('commentInput').value;
                var commentsDiv = document.getElementById('comments');
                commentsDiv.innerHTML += '<p>' + commentInput + '</p>';
                document.getElementById('commentInput').value = '';
            }
        </script>

</body>

</html>