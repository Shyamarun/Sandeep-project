<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Video Gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('skills.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        #external {
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
    <div class="container" id="external">
        <div class="row">
            <div class="col-md-8">
                <h3>Select a Category to View Videos</h3>
                <form action="" method="get">
                    <div class="form-group">
                        <label for="category">Select Category:</label>
                        <select id="category" class="form-control" name="category" required>
                            <!-- Options based on your categories -->
                            <option value="groupA">Group A</option>
                            <option value="groupB">Group B</option>
                            <option value="groupC">Group C</option>
                            <option value="groupD">Group D</option>
                            <option value="Computer science">Computer science</option>
                            <option value="Basic Medical Description">Basic Medical Description</option>
                            <option value="Basic Law points">Basic Law points</option>
                            <option value="Business School">Business School</option>
                            <option value="Chess">Chess</option>
                            <option value="Caroms">Caroms</option>
                            <option value="Puzzle Solving">Puzzle Solving</option>
                            <option value="Karate">Karate</option>
                            <option value="Singing">Singing</option>
                            <option value="Dancing">Dancing</option>
                            <option value="Musical Instruments">Musical Instruments</option>
                            <option value="Drawing And Art">Drawing And Art</option>
                            <option value="Photography">Photography</option>
                            <option value="Video Edeting">Video Edeting</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">View Videos</button>
                </form>

                <?php
                if (isset($_GET['category'])) {
                    // Assuming you have a database connection
                    include 'sql_conn.php';

                    // Fetch videos based on the selected category
                    $category = $conn->real_escape_string($_GET['category']);

                    $query = "SELECT * FROM external_abilities WHERE category = '$category'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="mb-3">';
                            echo '<div class="mt-3"></div>';
                            echo '<h5>' . $row['title'] . '</h5>';
                            echo '<video width="320" height="240" controls>';
                            echo '<source src="' . $row['file_name'] . '" type="video/mp4">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No videos found in the selected category.</p>';
                    }

                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>