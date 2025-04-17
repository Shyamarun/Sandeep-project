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
                <h2 class="text-center">File Upload</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file">Choose an Image:</label>
                        <input type="file" name="file" id="file" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" name="upload" class="btn btn-primary">Upload File</button>
                </form>

                <?php
                include 'sql_conn.php';
                session_start();
                $class_id = $_SESSION['class_id'];
                if (isset($_POST['upload'])) {
                    $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
                    // Check if a file was selected for upload
                    if (isset($_FILES['file'])) {
                        $uploadDir = 'uploads/teacher_info' . '/' . $class_id;
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $uploadFile = $uploadDir . '/' . basename($_FILES['file']['name']);

                        // Move the uploaded file to the desired directory
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                            // Insert file details into the database
                            $fileName = $_FILES['file']['name']; // You may need to sanitize this input
                            $filePath = $uploadFile; // You may need to store the full path or modify as needed

                            // Replace 'time_table' with your actual table name
                            $query = "INSERT INTO teacher_info (file_name, file_path,class_id) VALUES ('$fileName', '$filePath','$class_id')";
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                echo 'Error: Failed to insert file details into the database';
                            }
                        } else {
                            echo 'Error: Failed to move uploaded file';
                        }
                    } else {
                        echo 'Error: No file selected for upload';
                    }
                }

                // File Display Logic
                $query = "SELECT * FROM teacher_info WHERE class_id='$class_id'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    $fileId = $row['id'];
                    $fileName = $row['file_name'];
                    $filePath = $row['file_path'];

                    echo '<div class="file-box">';
                    echo '<img src="' . $filePath . '" />';
                    echo '<p>File Name: ' . $fileName . '</p>';
                    echo '<button class="btn btn-danger" onclick="deleteFile(' . $fileId . ')">Delete</button>';
                    echo '</div>';
                }
                ?>

                <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                <script>
                    // JavaScript or AJAX function for file deletion
                    function deleteFile(fileId) {
                        if (confirm('Are you sure you want to delete this file?')) {
                            $.ajax({
                                type: 'POST',
                                url: 'teacher_contact_info_delete.php',
                                data: {
                                    fileId: fileId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    // Log the response for debugging
                                    console.log(response);

                                    // Handle the response
                                    if (response.success) {
                                        // Reload the page or update the file list on the page
                                        location.reload();
                                    } else {
                                        alert('Error: ' + response.error);
                                    }
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    // Log the AJAX request failure for debugging
                                    console.error('AJAX request failed:', textStatus, errorThrown);
                                    console.log('File ID:', fileId);
                                    console.log('Response:', xhr.responseText); // Log the full response for detailed debugging
                                }
                            });
                        }
                    }
                </script>
            </div>
        </div>
    </div>

</body>

</html>