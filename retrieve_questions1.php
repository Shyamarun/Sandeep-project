<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'sql_conn.php';
session_start();

// Retrieve questions based on project ID and name
$projectID = isset($_GET["projectID"]) ? $_GET["projectID"] : '';
$username = isset($_GET["username"]) ? $_GET["username"] : '';

$sql = "SELECT * FROM club_questions WHERE projectID = '$projectID' AND username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<strong>Question:</strong> " . $row["question"] . "<br>";
        echo "<strong>Options:</strong><br>";
        echo "Option 1: " . $row["option1"] . "<br>";
        echo "Option 2: " . $row["option2"] . "<br>";
        echo "Option 3: " . $row["option3"] . "<br>";
        echo "Option 4: " . $row["option4"] . "<br>";
        echo "Option 5: " . $row["option5"] . "<br>";
        echo "<strong>Correct Option:</strong> " . $row["correctOption"] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No questions found for the specified project ID and name.";
}

$conn->close();
