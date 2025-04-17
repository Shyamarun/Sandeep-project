<?php
session_start();
$class_id=$_POST['class_id'];
$reg_num=$_POST['reg_num']; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Image/PDF Viewer</title>
</head>

<body>

    <div class="container mt-5">
        <form action="upload_think_diff.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="reg_num" id="reg_num" class="form-control" value="<?php echo $reg_num; ?>" required>
            <input type="hidden" name="class_id" id="class_id" class="form-control" value="<?php echo $class_id; ?>" required>
            <div class="form-group">
                <label for="file">Upload Image/PDF:</label>
                <input type="file" name="file" id="file" class="form-control-file" accept=".pdf, .png, .jpg, .jpeg,.mp4"
                    required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <!-- Display uploaded images or PDFs -->
        <div class="mt-5">
            <!-- Display uploaded images or PDFs -->
            <div class="mt-5">
                <!-- Display images/PDFs here -->
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>