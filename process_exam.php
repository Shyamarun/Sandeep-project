<?php
include 'sql_conn.php';

$answers = $_POST['answers'] ?? [];
$reg_num = $_POST['reg_num'] ?? '';
$skill = $_POST['skill'] ?? '';

// Check if placement_skills table exists, if not create it
$createTableQuery = "CREATE TABLE IF NOT EXISTS placement_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reg_num VARCHAR(255) NOT NULL,
    class_id VARCHAR(255) NOT NULL,
    skill VARCHAR(255) NOT NULL,
    score DECIMAL(5,2) NOT NULL
)";
$conn->query($createTableQuery);

// Fetch correct answers
$query = "SELECT id, correctOption FROM placement_questions WHERE skill = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $skill);
$stmt->execute();
$correctAnswersResult = $stmt->get_result();
$correctAnswers = [];
while($row = $correctAnswersResult->fetch_assoc()) {
    $correctAnswers[$row['id']] = $row['correctOption'];
}

// Calculate score
$totalQuestions = count($correctAnswers);
$correctCount = 0;
foreach ($answers as $questionId => $selectedOption) {
    // Here we compare the full option text. We need to ensure that the text matches exactly, including case
    if (isset($correctAnswers[$questionId]) && strcasecmp($correctAnswers[$questionId], $selectedOption) == 0) {
        $correctCount++;
    }
}

$score = ($correctCount / $totalQuestions) * 100;

// Get class_id from stdreg table
$query = "SELECT class_id FROM stdreg WHERE reg_num = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $reg_num);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$class_id = $row['class_id'] ?? '';

// Insert score into placement_skills table
$query = "INSERT INTO placement_skills (reg_num, class_id, skill, score) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssd", $reg_num, $class_id, $skill, $score);
$stmt->execute();

// Redirect or show a message
echo "<script>alert('Exam completed. Your score: " . $score . "%');window.location.href='placements_student.php';</script>";
// Consider redirecting instead of just echoing the score
// header('Location: results_page.php?score=' . urlencode($score));
exit;
?>
