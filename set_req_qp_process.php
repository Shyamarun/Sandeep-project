<?php
session_start();

include 'sql_conn.php'; // Make sure this is included before trying to use $conn

// Check and create the table if it does not exist
$tableSql = "CREATE TABLE IF NOT EXISTS placement_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    collegeCode VARCHAR(255),
    skill VARCHAR(255),
    question VARCHAR(255),
    option1 VARCHAR(255),
    option2 VARCHAR(255),
    option3 VARCHAR(255),
    option4 VARCHAR(255),
    option5 VARCHAR(255),
    correctOption VARCHAR(255)
)";
if ($conn->query($tableSql) !== TRUE) {
    die("Error setting up the database.");
}

$duplicateMessages = []; // Initialize an array to store messages about duplicates

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collegeCode = $_SESSION['collegeCode'];
    $skill = isset($_POST["skill"]) ? $_POST["skill"] : '';
    
    if (isset($_POST["question"])) {
        foreach ($_POST["question"] as $index => $question) {
            $questionEscaped = mysqli_real_escape_string($conn, $question);
            // Escape the options
            $options = array_map(function ($option) use ($conn) {
                return mysqli_real_escape_string($conn, $option);
            }, [
                $_POST["option1"][$index],
                $_POST["option2"][$index],
                $_POST["option3"][$index],
                $_POST["option4"][$index],
                $_POST["option5"][$index]
            ]);
            $correctOption = mysqli_real_escape_string($conn, $_POST["correct"][$index]);

            $checkSql = "SELECT id FROM placement_questions WHERE 
                         question = '$questionEscaped' AND 
                         option1 = '{$options[0]}' AND 
                         option2 = '{$options[1]}' AND 
                         option3 = '{$options[2]}' AND 
                         option4 = '{$options[3]}' AND 
                         option5 = '{$options[4]}' AND 
                         correctOption = '$correctOption' AND collegeCode = '$collegeCode' AND skill = '$skill'";
            $checkResult = $conn->query($checkSql);
            if ($checkResult && $checkResult->num_rows > 0) {
                // Instead of exiting, add a message to the duplicates array
                $duplicateMessages[] = "Duplicate found for question: '$question' with provided options.";
            } else {
                $sql = "INSERT INTO placement_questions (collegeCode, skill, question, option1, option2, option3, option4, option5, correctOption) 
                        VALUES ('$collegeCode', '$skill', '$questionEscaped', '{$options[0]}', '{$options[1]}', '{$options[2]}', '{$options[3]}', '{$options[4]}', '$correctOption')";
                if ($conn->query($sql) !== TRUE) {
                    echo "<script>alert('Error inserting a question. Please try again.');window.location.href='set_rq_qp.php';</script>";
                    exit();
                }
            }
        }

    } else {
        echo "<script>alert('No questions posted. Try again.');window.location.href='set_rq_qp.php';</script>";
        exit();
    }
    if (!empty($duplicateMessages)) {
    // Convert the array of messages into a JSON string and use it in the alert
    $duplicatesJson = json_encode(implode("\n", $duplicateMessages));
    echo "<script>alert($duplicatesJson); window.location.href='set_rq_qp.php';</script>";
    } else {
    // Confirm all new questions were posted successfully
    echo "<script>alert('All questions posted successfully!'); window.location.href='set_rq_qp.php';</script>";
    }
    $conn->close();
}
?>
