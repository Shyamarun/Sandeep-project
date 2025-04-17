<?php
session_start();
$class_id = $_SESSION['class_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('clv.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 10px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
        }

        table {
            background: transparent;
            /* Transparent background for the table */
        }

        th,
        td {
            color: #333;
            /* Dark text for readability */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table thead th {
            max-width: 60px;
            background-color: rgba(0, 123, 255, 0.7);
            /* More opaque blue for header background */
            color: white;
            /* White text color for headers */
        }

        #head {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }

        .table th {
            max-width: 60px;
            /* Decrease the max-width to a suitable value */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
    </style>
</head>

<body>
    <div class="container mt-5" >
        <!-- Add the form here -->
        <form method="post" action="clv_display_std_process.php">
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="subject" placeholder="Enter Subject" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="lesson" placeholder="Enter Lesson name" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="topic" placeholder="Enter topic name" required>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="table-container">
        <center>
            <h2>Existing Records</h2>
        </center>
        
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th><center>Subject</th>
                    <th><center>Lesson</th>
                    <th><center>Topic</th>
                    <th><center>Video</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'sql_conn.php';

                $query = "SELECT * FROM clv_videos WHERE class_id='$class_id'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td><center>{$row['subject']}</td>";
                    echo "<td><center>{$row['lesson']}</td>";
                    echo "<td><center>{$row['topic']}</td>";
                    echo "<td><center><video class='img-fluid' width='320' height='240' controls><source src='{$row['video_path']}' type='video/mp4'></video></td>";
                    echo "</tr>";
                }

                // Close database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>