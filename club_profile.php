<?php
session_start();
$reg_num = $_SESSION['reg_num'];
?>
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
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('ch.jpg');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <?php
            include 'sql_conn.php';

            // Fetch data from the database
            $sql = "SELECT projectCode, projectName, description FROM club_uploads where reg_num='$reg_num'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3 project-box" id="head" onclick="redirectToPage(\'' . $row['projectCode'] . '\', \'' . $_SESSION['reg_num'] . '\')">';
                    echo '<h5>' . $row['projectCode'] . '</h5>';
                    echo '<p>' . $row['projectName'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<script>alert('No results found');window.location.href='club_home.php';</script>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- Navigation bar -->
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
        <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
        <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
        <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
        <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
    </nav>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function redirectToPage(projectCode, reg_num) {
            // Redirect to another page with the projectCode
            window.location.href = 'club_profile_uploads.php?projectCode=' + projectCode + '&reg_num=' + reg_num;
        }
    </script>
</body>

</html>