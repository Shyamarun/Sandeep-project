<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Boxes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add your custom styles here */
        .project-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            cursor: pointer;
            overflow: hidden;
            /* Ensure overflow doesn't affect layout */
        }

        .project-box img {
            max-width: 100%;
            /* Set maximum width to 100% of its container */
            height: auto;
            /* Ensure the image retains its aspect ratio */
            display: block;
            /* Remove extra space below the image */
            margin: 0 auto;
            /* Center the image within the container */
        }

        .navbar {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <?php
            session_start();
            include 'sql_conn.php';
            $reg_num = $_SESSION['reg_num'];

            // Fetch data from the database
            $sql = "SELECT id, file_path, description FROM file_uploads WHERE reg_num='$reg_num'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];

                    echo '<div class="col-md-3 project-box">';
                    echo getFileDisplay($row['file_path']);
                    echo '<p>' . $row['description'] . '</p>';

                    // Add button to display comments
                    echo '<button class="btn btn-primary" data-toggle="modal" data-target="#commentsModal' . $id . '">View Comments</button>';

                    // Modal structure
                    echo '<div class="modal fade" id="commentsModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel' . $id . '" aria-hidden="true">';
                    echo '<div class="modal-dialog modal-dialog-scrollable" role="document">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h5 class="modal-title" id="commentsModalLabel' . $id . '">Comments</h5>';
                    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                    echo '</div>';
                    echo '<div class="modal-body">';

                    // Fetch and display comments here
                    $commentsSql = "SELECT * FROM think_diff_comments WHERE id=?";
                    $stmt = $conn->prepare($commentsSql);
                    $stmt->bind_param("i", $id_param);

                    $id_param = $id; // Set the parameter for the prepared statement

                    if ($stmt->execute()) {
                        $commentResult = $stmt->get_result();

                        while ($commentRow = $commentResult->fetch_assoc()) {
                            echo '<p>ID: ' . $commentRow['id'] . ' - ' . $commentRow['description'] . '</p>';
                        }
                    }

                    $stmt->close();

                    // Add more styling or information as needed
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo "<script>alert('No results');window.location.href='retrieve_think_diff.php';</script>";
            }

            $conn->close();

            // Function definition outside the loop
            function getFileDisplay($filePath)
            {
                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                if ($fileExtension === 'pdf') {
                    return '<embed src="' . $filePath . '" type="application/pdf" width="100%" height="200px" />';
                } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                    return '<img src="' . $filePath . '" alt="Project Image" class="img-fluid">';
                } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                    return '
                            <video controls class="img-fluid">
                            <source src="' . $filePath . '" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>';
                } else {
                    return '<p>Unsupported file type</p>';
                }
            }
            ?>

        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>