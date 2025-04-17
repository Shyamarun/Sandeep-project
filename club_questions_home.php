<?php
include "sql_conn.php";
session_start();
$projectCode = $_GET['projectCode'];
$reg_num = $_GET['responder_reg_num']; // Replace with the desired question_id

$sql = "SELECT question, option1, option2, option3, option4, option5, correctOption
        FROM club_questions 
        WHERE projectCode = '$projectCode'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Quiz Page</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-image: url('clv.webp');
                /* Update with the actual path */
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                overflow-y: scroll;
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
        <div class="container mt-5">
            <form action="club_question_validate.php" method="post">
                <input type="hidden" id="projectCode" name="projectCode" value="<?php echo $projectCode; ?>">
                <input type="hidden" id="reg_num" name="reg_num" value="<?php echo $reg_num; ?>">
                <?php
                $questionNumber = 1;
                while ($row = $result->fetch_assoc()) {
                    $question = $row["question"];
                    $options = array($row["option1"], $row["option2"], $row["option3"], $row["option4"], $row["option5"]);
                    $correctOption = $row["correctOption"];
                ?>
                    <div class="card mb-3" id='head'>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo "Question $questionNumber: " . $question; ?></h5>
                            <?php
                            foreach ($options as $option) {
                            ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="selectedOptions[<?php echo $questionNumber; ?>]" value="<?php echo $option; ?>">
                                    <label class="form-check-label" for="selectedOption"><?php echo $option; ?></label>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php
                    $questionNumber++;
                }
                ?>
                <textarea id="head" name="user_paragraph" rows="4" cols="50" placeholder="How can you add value ?"></textarea><br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <!--nav class="navbar fixed-bottom navbar-light bg-light">
            <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
            <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
            <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
            <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
            <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
        </nav-->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php
} else {
    echo "No results found.";
}
?>