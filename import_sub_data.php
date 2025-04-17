<?php 
session_start();
$collegeCode=$_SESSION['collegeCode']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Uploader</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Upload Subjects</h2>
        <form action="import_sub_data_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="collegeCode" value="<?php echo $_SESSION['collegeCode']; ?>">
            <div class="form-group">
                <label for="course">Select Course:</label>
                <select name="course" id="course" class="form-control">
                    <option value="BTECH">B. Tech</option>
                    <option value="BPHARMACY">B. Pharmacy</option>
                    <option value="DEGREE">Degree</option>
                    <option value="DIPLOMA">Diploma</option>
                </select>
            </div>
            <div class="form-group">
                <label for="csvFile">Upload CSV File:</label>
                <input type="file" name="csvFile" id="csvFile" class="form-control-file" required>
            </div>
            <button type="submit" name="uploadBtn" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
