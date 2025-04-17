<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload CSV to stdreg</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Upload CSV file</h2>
    <form action="import_std_data_process.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fileUpload">CSV file:</label>
            <input type="file" name="file" id="fileUpload" class="form-control">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
