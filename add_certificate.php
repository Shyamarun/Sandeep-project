<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Certificate</title>
    <!-- Bootstrap CSS Link (replace with your Bootstrap CDN or local file) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add your custom CSS styling here if needed */
        body {
            margin: 20px;
        }

        form {
            max-width: 600px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        textarea {
            height: 100px;
            /* Adjust the height as needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Add Certificate</h2>
        <form action="add_certificate1.php?reg_num=<?php echo $_GET['reg_num']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="class_id" id="class_id" value="<?php echo $_SESSION['class_id']; ?>">
            <div class="form-group">
                <label for="name">Certificate Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="certificate_file">Upload Certificate:</label>
                <input type="file" class="form-control-file" name="certificate_file" accept=".pdf, .jpg, .jpeg, .png" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Certificate</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js (replace with your Bootstrap CDN or local files) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>