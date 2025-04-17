<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Questions</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body class="container mt-4">

    <h2>Retrieve and Display Questions</h2>

    <form action="" method="get">
        <div class="form-group">
            <label for="projectID">Project ID:</label>
            <input type="text" class="form-control" name="projectID" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>

        <button type="submit" class="btn btn-primary">Retrieve Questions</button>
    </form>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $servername = "localhost";
    $dbUsername = "mrx";
    $password = "2905";
    $dbname = "projectx";

    $conn = new mysqli($servername, $dbUsername, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET["projectID"]) && isset($_GET["username"])) {
        $projectID = $_GET["projectID"];
        $username = $_GET["username"];

        $sql = "SELECT * FROM club_questions WHERE projectID = '$projectID' AND username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<form action="" method="post">';
            while ($row = $result->fetch_assoc()) {
                echo "<div class='mb-3'>";
                echo "<strong>Question:</strong> " . $row["question"] . "<br>";
                echo "<strong>Options:</strong><br>";

                for ($i = 1; $i <= 5; $i++) {
                    $optionName = 'option' . $i;
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="radio" name="selectedOption[' . $row["id"] . ']" value="' . $i . '">';
                    echo '<label class="form-check-label">' . $row[$optionName] . '</label>';
                    echo '</div>';
                }

                echo '<input type="hidden" class="correctOption" name="correctOption[' . $row["id"] . ']" value="' . $row["correctOption"] . '">';
                echo "</div>";
            }
            echo '<button type="submit" class="btn btn-primary">Submit Answers</button>';
            echo '</form>';
        } else {
            echo "<p class='mt-3'>No questions found for the specified project ID and username.</p>";
        }
    }

    $conn->close();
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var correctOptions = document.querySelectorAll('.correctOption');
            correctOptions.forEach(function(option) {
                option.classList.add('hidden');
            });
        });
    </script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>