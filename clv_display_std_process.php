<?php
session_start();
$class_id=$_SESSION['class_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <!-- Display videos based on the form input -->
    <div class="container mt-5">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Lesson</th>
                    <th>Topic</th>
                    <th>Video</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display videos based on form input
                $subject = $_POST['subject'];
                $lesson = $_POST['lesson'];
                $topic = $_POST['topic'];

                include 'sql_conn.php';

                $query = "SELECT * FROM clv_videos WHERE class_id='$class_id' AND subject='$subject' AND lesson='$lesson' AND topic='$topic'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['subject']}</td>";
                    echo "<td>{$row['lesson']}</td>";
                    echo "<td>{$row['topic']}</td>";
                    echo "<td><video class='img-fluid' width='320' height='240' controls><source src='{$row['video_path']}' type='video/mp4'></video></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>