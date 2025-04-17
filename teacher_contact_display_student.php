<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload and Display</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .file-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">

                <?php
                include 'sql_conn.php';
                $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;
                // File Display Logic
                $query = "SELECT * FROM teacher_info WHERE class_id='$class_id'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    $fileName = $row['file_name'];
                    $filePath = $row['file_path'];

                    echo '<div class="file-box">';
                    echo '<img src="' . $filePath . '" />';
                    echo '<p>File Name: ' . $fileName . '</p>';
                    echo '</div>';
                }
                ?>

                <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            </div>
        </div>
    </div>

</body>

</html>