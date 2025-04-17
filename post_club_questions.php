<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectCode = isset($_POST["projectCode"]) ? $_POST["projectCode"] : '';
    $reg_num = $_SESSION['reg_num'];

    include 'sql_conn.php';
    if (isset($_POST["question"])) {
        foreach ($_POST["question"] as $index => $question) {
            $question = mysqli_real_escape_string($conn, $question);

            $options = array_map(function ($option) use ($conn) {
                return mysqli_real_escape_string($conn, $option);
            }, [
                $_POST["option1"][$index],
                $_POST["option2"][$index],
                $_POST["option3"][$index],
                $_POST["option4"][$index],
                $_POST["option5"][$index]
            ]);

            $correctOption = $_POST["correct"][$index];

            // Handle image file upload
            $imagePath = "uploads/{$reg_num}/{$projectCode}/";  // Set a default path or handle file upload logic

            $sql = "INSERT INTO club_questions (projectCode, username, question, option1, option2, option3, option4, option5, correctOption, imagePath) 
                    VALUES ('$projectCode', '$reg_num', '$question', '$options[0]', '$options[1]', '$options[2]', '$options[3]', '$options[4]', '$correctOption', '$imagePath')";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        echo "<script>alert('Questions posted successfully!');window.location.href='club_uploads_home.php';</script>";
    }else{
        echo "<script>alert('No questions posted\nTry again');window.location.href='club_uploads_home.php';</script>";
    }

    $conn->close();
}
