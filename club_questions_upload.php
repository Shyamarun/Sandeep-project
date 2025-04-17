<?php
session_start();
$reg_num = $_SESSION['reg_num'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Paper Creator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .question-container {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .option-container {
            margin-top: 10px;
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
        <h2>Question Paper Creator</h2>

        <form action="post_club_questions.php" method="post" enctype="multipart/form-data">
            <!-- Add input fields for projectCode and username -->
            <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
            <div class="form-group">
                <label for="projectCode">Project ID:</label>
                <input type="text" class="form-control" name="projectCode" placeholder="Enter Project Code" required>
            </div>
            <div class="form-group">
                <label for="username">Project Name</label>
                <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
            </div>

            <div id="questions-container">
                <!-- Initial question input fields -->
                <div class="question-container">
                    <label for="question">Question</label>
                    <input type="text" name="question[]" class="question">
                    <input type="text" name="option1[]" placeholder="Enter option 1" class="option">
                    <input type="text" name="option2[]" placeholder="Enter option 2" class="option">
                    <input type="text" name="option3[]" placeholder="Enter option 3" class="option">
                    <input type="text" name="option4[]" placeholder="Enter option 4" class="option">
                    <input type="text" name="option5[]" placeholder="Enter option 5" class="option">
                    <input type="text" name="correct[]" placeholder="Enter correct option" class="correct">
                </div>
            </div>

            <button type="button" class="btn btn-success" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-primary">Post Questions</button>
        </form>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
        <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
        <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
        <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
        <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
    </nav>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function addQuestion() {
            let questionsContainer = document.getElementById('questions-container');

            let questionContainer = document.createElement('div');
            questionContainer.className = 'question-container';

            questionContainer.innerHTML = `
                <label for="question">Question</label>
                <input type="text" name="question[]" class="question">
                <input type="text" name="option1[]" placeholder="Enter option 1" class="option">
                <input type="text" name="option2[]" placeholder="Enter option 2" class="option">
                <input type="text" name="option3[]" placeholder="Enter option 3" class="option">
                <input type="text" name="option4[]" placeholder="Enter option 4" class="option">
                <input type="text" name="option5[]" placeholder="Enter option 5" class="option">
                <input type="text" name="correct[]" placeholder="Enter correct option" class="correct">
            `;

            questionsContainer.appendChild(questionContainer);
        }
    </script>

</body>

</html>