<?php
include "sql_conn.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $projectCode = $_POST['projectCode']; // Ensure this is sanitized if used directly in SQL queries

    // Prepared statement to enhance security
    $sql = $conn->prepare("SELECT correctOption FROM club_questions WHERE projectCode = ?");
    $sql->bind_param("s", $projectCode);
    $sql->execute();
    $result = $sql->get_result();

    $correctOptions = array();
    while ($row = $result->fetch_assoc()) {
        $correctOptions[] = $row["correctOption"];
    }

    // Check if there are correct options
    if (count($correctOptions) > 0) {
        $userAnswersArray = $_POST['selectedOptions'];
        $score = 0;

        foreach ($userAnswersArray as $questionNumber => $selectedOption) {
            if (isset($correctOptions[$questionNumber - 1]) && $selectedOption == $correctOptions[$questionNumber - 1]) {
                $score++;
            }
        }

        $totalQuestions = count($correctOptions);
        $percentage = ($score / $totalQuestions) * 100;

        // If percentage is above 95%, fetch full_name, phone_num from stdreg table
        if ($percentage > 95) {
            $responder_reg_num = $_SESSION['reg_num'];
            $paragraph = $_POST['user_paragraph']; // Validate and sanitize this input as well

            $fetchUserInfoSql = $conn->prepare("SELECT full_name, phone_num FROM stdreg WHERE reg_num = ?");
            $fetchUserInfoSql->bind_param("s", $responder_reg_num);
            $fetchUserInfoSql->execute();
            $userInfoResult = $fetchUserInfoSql->get_result();

            if ($userInfo = $userInfoResult->fetch_assoc()) {
                $full_name = $userInfo['full_name'];
                $phone_num = $userInfo['phone_num'];

                $sqlSender = $conn->prepare("SELECT reg_num FROM club_uploads WHERE projectCode = ?");
                $sqlSender->bind_param("s", $projectCode);
                $sqlSender->execute();
                $sqlSender->bind_result($senderRoll);
                $sqlSender->fetch();
                $sqlSender->close();

                $insertSqlSender = $conn->prepare("INSERT INTO club_responses (projectCode, responder_reg_num, full_name, phone_num, description, reg_num) VALUES (?, ?, ?, ?, ?, ?)");
                $insertSqlSender->bind_param("ssssss", $projectCode, $responder_reg_num, $full_name, $phone_num, $paragraph, $senderRoll);
                $insertSqlSender->execute();
                $insertSqlSender->close();

                echo "<script>alert('Submitted response');window.location.href='club_home.php';</script>";
            }
        }
    }

    $conn->close();
} else {
    // Redirect user back or display an error if not a POST request
    echo "<script>alert('Invalid request method.');window.location.href='club_home.php';</script>";
}
