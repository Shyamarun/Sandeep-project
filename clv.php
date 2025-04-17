<?php
session_start();
$faculty_id = $_SESSION['faculty_id'];
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
            background-image: url('st.jpg');
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
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
            border: 2px solid #007bff;
            /* Solid border for a distinct frame */
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
            /* Bootstrap's default border color */
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            /* More opaque blue for header background */
            color: white;
            /* White text color for headers */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <h2> </h2>
            <table class="table table-bordered">
                <div class="container mt-5">
                    <h1 class="mb-4">Video Upload</h1>
                    <!-- Upload Form -->
                    <form action="clvupload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <select name="subject" class="form-control" required>
                                <option value="">Select Subject</option>
                                <?php
                                include 'sql_conn.php';

                                // Fetch distinct subjects from master_faculty
                                $sql = "SELECT DISTINCT subject_name FROM master_faculty WHERE faculty_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $faculty_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['subject_name']) . "'>" . htmlspecialchars($row['subject_name']) . "</option>";
                                }

                                $stmt->close();
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lesson">Lesson:</label>
                            <input type="text" class="form-control" name="lesson" required>
                        </div>

                        <div class="form-group">
                            <label for="topic">Topic:</label>
                            <input type="text" class="form-control" name="topic" required>
                        </div>

                        <div class="form-group">
                            <label for="video">Choose a video:</label>
                            <input type="file" class="form-control-file" name="video" accept="video/*" required>
                        </div>

                        <button type="submit" class="btn btn-primary" name="upload">Upload</button>
                    </form>

                    
                        <h2 class="mt-5">Uploaded Videos</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Lesson</th>
                                    <th>Topic</th>
                                    <th>Video</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'sql_conn.php';

                                // Query to get subjects based on faculty_id
                                $sqlSubjects = "SELECT subject_name FROM master_faculty WHERE faculty_id = ?";
                                $stmtSubjects = $conn->prepare($sqlSubjects);
                                $stmtSubjects->bind_param("s", $faculty_id);
                                $stmtSubjects->execute();
                                $subjectsResult = $stmtSubjects->get_result();
                                $subjects = array();
                                while ($row = mysqli_fetch_assoc($subjectsResult)) {
                                    $subjects[] = $row['subject_name'];
                                }

                                // Query to display data from clv_uploads
                                if (count($subjects) > 0) {
                                    $placeholders = implode(',', array_fill(0, count($subjects), '?'));
                                    $sqlUploads = "SELECT subject, lesson, topic, video_path, video_id FROM clv_videos WHERE subject IN ($placeholders)";
                                    $stmtUploads = $conn->prepare($sqlUploads);
                                    $stmtUploads->bind_param(str_repeat('s', count($subjects)), ...$subjects);
                                    $stmtUploads->execute();
                                    $resultUploads = $stmtUploads->get_result();

                                    // Display the videos
                                    while ($row = mysqli_fetch_assoc($resultUploads)) {
                                        echo "<tr>";
                                        echo "<td>{$row['subject']}</td>";
                                        echo "<td>{$row['lesson']}</td>";
                                        echo "<td>{$row['topic']}</td>";
                                        echo "<td><video class='img-fluid' width='320' height='240' controls><source src='{$row['video_path']}' type='video/mp4'></video></td>";
                                        echo "<td><a href='clv_delete.php?id={$row['video_id']}' class='btn btn-danger'>Delete</a></td>";
                                        echo "</tr>";
                                    }
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