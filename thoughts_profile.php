<?php
session_start();
$reg_num = isset($_GET['reg_num']) ? $_GET['reg_num'] : die("Invalid request");

include 'sql_conn.php';

// Retrieve user videos
$videosSql = "SELECT reg_num,title, description, video_path FROM thoughts WHERE reg_num='$reg_num'";
$videosResult = $conn->query($videosSql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Videos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <!--div class="text-center">
            <h2><?php echo $videoRow['reg_num']; ?></h2>
        </div-->

        <hr>

        <div class="row justify-content-center">
            <?php
            if ($videosResult->num_rows > 0) {
                while ($videoRow = $videosResult->fetch_assoc()) {
                    $title = $videoRow['title'];
                    $description = $videoRow['description'];
                    $videoPath = $videoRow['video_path'];
            ?>

                    <div class="card m-3" style="width: 18rem;">
                        <video controls class="card-img-top">
                            <source src="<?php echo $videoPath; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $title; ?></h5>
                            <p class="card-text"><?php echo $description; ?></p>
                        </div>
                    </div>

            <?php
                }
            } else {
                echo '<p class="text-center">No videos found for this user.</p>';
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>

</html>