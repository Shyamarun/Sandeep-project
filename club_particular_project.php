<?php
session_start();
$projectCode = $_GET['projectCode'];
$reg_num = $_SESSION['reg_num'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Club Projects</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 60px;
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

        #card {
            width: 100%;
            border: solid 1px;
            overflow: hidden;
            transition: transform 0.3s;
            position: relative;
            display: flex;
            flex-direction: row;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 10px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('library_back.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        #head {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 10px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }
        .card-body img{
            height: 50vh;
            width:100vh;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <!-- Content Section -->
    <div class="container mt-5">
        <?php
        include 'sql_conn.php';

        // Fetch data from the database
        $query = "SELECT projectCode, projectName, description, filePath FROM club_uploads WHERE projectCode='$projectCode'";
        $result = mysqli_query($conn, $query);

        // Display data in boxes
        while ($row = mysqli_fetch_assoc($result)) {
            //$reg_num=$row['reg_num'];
            $projectCode = $row['projectCode'];
            echo '<div class="card mb-3" id="card">';
            
            echo '<div class="card-body">';
            echo '<center><strong><h5 class="card-title">' . $row['projectName'] . '</strong></h5>';
            echo '<center><strong><p class="card-text">' . $row['description'] . '</strong></p>';
            echo '<center><a href="club_questions_home.php?projectCode=' . $projectCode . '&responder_reg_num=' . $reg_num . '" class="btn btn-primary">Join</a>';
            echo '<div class="mt-3"></div>';
            // Embed the file if it's an image
            if (!empty($row['filePath']) && (pathinfo($row['filePath'], PATHINFO_EXTENSION) === 'jpg' || pathinfo($row['filePath'], PATHINFO_EXTENSION) === 'jpeg' || pathinfo($row['filePath'], PATHINFO_EXTENSION) === 'png' || pathinfo($row['filePath'], PATHINFO_EXTENSION) === 'gif')) {
                echo '<center><img src="' . $row['filePath'] . '" class="img-fluid" alt="Project Image">';
            }





            echo '</div>';
            echo '</div>';
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
        <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
        <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
        <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
        <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
    </nav>

    <!-- Bootstrap and Font Awesome Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script>
</body>

</html>