<?php
// PHP code to fetch and display videos
session_start();
include 'sql_conn.php';
try {
    $usernameToFetch = $_POST['reg_num']; // Replace with the actual username you want to retrieve

    // Modify the query to retrieve user profile information
    $query = "SELECT username, profile_picture, bio FROM thoughts_user_profiles WHERE username = ?";
    $statement = $conn->prepare($query);

    if (!$statement) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    // Bind the parameter
    $statement->bind_param("s", $usernameToFetch);

    // Execute the query
    $statement->execute();

    // Get the result set
    $result = $statement->get_result();

    if ($result === false) {
        throw new Exception("Query error: " . $conn->error);
    }

    // Fetch user profile if available
    if ($row = $result->fetch_assoc()) {
        $fetchedUsername = $row['username'];
        $profilePicture = $row['profile_picture'];
        $bio = $row['bio'];
    } else {
        // User not found
        throw new Exception("User not found.");
    }

    // Close the database connection
    $conn->close();
} catch (Exception $e) {
    // Handle exceptions
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thoughts User Page</title>
    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Using Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f0f0f0;
            padding: 20px;
        }

        .profile video {
            width: 100%;
            height: auto;
        }

        .tabs div {
            cursor: pointer;
        }

        .tabs div.active {
            color: #000;
            font-weight: bold;
        }

        .videos video {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="container">

    <div class="profile text-center my-3">
        <img src="<?php echo $profilePicture; ?>" alt="Profile Picture">
        <h2>@<?php echo $fetchedUsername; ?></h2>
        <p>Bio: <?php echo $bio; ?></p>
    </div>
    <div class="row">
        <!-- PHP code to fetch and display videos -->
        <?php


        // Fetch videos for the specified user
        $sql = "SELECT * FROM thoughts WHERE username = '$username'";
        $result = $conn->query($sql);

        // Check if there are videos
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display video information (you can customize this part)
                echo "<div class='col-md-4 mb-3'>";
                echo "<video src='" . $row['video_path'] . "' alt='Video' class='img-fluid' controls></video>";
                echo "</div>";
            }
        } else {
            echo "No videos found for the user.";
        }

        // Close the database connection
        $conn->close();
        ?>
        <!-- End of PHP code to fetch and display videos -->
    </div>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>