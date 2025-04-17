<?php
session_start();
$reg_num = $_SESSION['reg_num'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('library_back.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .head {
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
        }

        .navbar.fixed-bottom {
            border-radius: 15px;
            /* Curved edges for the navbar */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            margin: 10px 15px;
            /* Adjust margin to ensure the navbar does not stretch fully across */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for depth */
            overflow: hidden;
            /* Ensures content fits within the border radius */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="head">
            <div class="form-container">
                <h2>Upload Files</h2>
                <form action="club_uploads_process.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="reg_num" value="<?php echo $reg_num; ?>">
                    <div class="form-group">
                        <label for="projectName">Project Name:</label>
                        <input type="text" class="form-control" name="projectName" required>
                    </div>
                    <div class="form-group">
                        <label for="projectCode">Project Code:</label>
                        <input type="text" class="form-control" name="projectCode" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file">Upload File:</label>
                        <input type="file" class="form-control-file" name="file" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
            <div class="mt-3"></div>
            <div class="action-buttons">
                <a href="club_questions_upload.php?reg_num=<?php echo $_SESSION['reg_num']; ?>" class="btn btn-success">Add Questions</a>
            </div>
        </div>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
        <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
        <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
        <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
        <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
    </nav>
</body>

</html>