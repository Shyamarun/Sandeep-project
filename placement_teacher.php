<?php
session_start();
include 'sql_conn.php';

$class_id = $_SESSION['class_id'] ?? '';

// Get all distinct skills
$skillsQuery = "SELECT DISTINCT skill FROM placement_skills";
$skillsResult = $conn->query($skillsQuery);

$skills = [];
while($skillRow = $skillsResult->fetch_assoc()) {
    $skills[] = $skillRow['skill'];
}

// Get all reg_num for the class
$studentsQuery = "SELECT reg_num FROM stdreg WHERE class_id = ?";
$studentsStmt = $conn->prepare($studentsQuery);
$studentsStmt->bind_param("s", $class_id);
$studentsStmt->execute();
$studentsResult = $studentsStmt->get_result();

$students = [];
while($studentRow = $studentsResult->fetch_assoc()) {
    $students[] = $studentRow['reg_num'];
}

// Calculate average score for each skill for each student
$averages = [];
foreach ($students as $student) {
    foreach ($skills as $skill) {
        $avgQuery = "SELECT AVG(score) as avgScore FROM placement_skills WHERE reg_num = ? AND skill = ? AND class_id = ?";
        $avgStmt = $conn->prepare($avgQuery);
        $avgStmt->bind_param("sss", $student, $skill, $class_id);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $avgRow = $avgResult->fetch_assoc();
        $averages[$student][$skill] = $avgRow['avgScore'];
    }
}

// Convert the averages to JSON to use in the JavaScript for charting
$jsonAverages = json_encode($averages);
$jsonSkills = json_encode($skills);
// Close the database connection
$studentsStmt->close();
$avgStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Average Scores by Skill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>Average Scores by Skill for Class <?= htmlspecialchars($class_id) ?></h2>
    <canvas id="scoresChart"></canvas>
</div>

<script>
// Parse the PHP JSON data into JavaScript object
const averages = JSON.parse('<?= $jsonAverages ?>');
const skills = JSON.parse('<?= $jsonSkills ?>');

// Light color palette for the bars
const colors = [
    'rgba(255, 99, 132, 0.8)',    // Red
    'rgba(54, 162, 235, 0.8)',    // Blue
    'rgba(255, 206, 86, 0.8)',    // Yellow
    'rgba(75, 192, 192, 0.8)',    // Green
    'rgba(153, 102, 255, 0.8)',   // Purple
    'rgba(255, 159, 64, 0.8)',    // Orange
    'rgba(22, 160, 133, 0.8)',    // Turquoise
    'rgba(39, 174, 96, 0.8)',     // Emerald
    'rgba(41, 128, 185, 0.8)',    // Nephritis
    'rgba(142, 68, 173, 0.8)',    // Amethyst
    'rgba(243, 156, 18, 0.8)',    // Sunflower
    'rgba(211, 84, 0, 0.8)',      // Carrot
    'rgba(192, 57, 43, 0.8)',     // Pomegranate
    'rgba(127, 140, 141, 0.8)',   // Asbestos
    'rgba(22, 160, 133, 0.8)',    // Green Sea
    'rgba(44, 62, 80, 0.8)',      // Midnight Blue
    'rgba(231, 76, 60, 0.8)',     // Alizarin
    'rgba(230, 126, 34, 0.8)',    // Pumpkin
    'rgba(155, 89, 182, 0.8)',    // Wisteria
    'rgba(52, 73, 94, 0.8)'       // Wet Asphalt
];


// Prepare the datasets for the chart
let datasets = [];
Object.keys(averages).forEach((regNum, index) => {
    let studentScores = skills.map(skill => averages[regNum][skill] || 0);
    datasets.push({
        label: regNum,
        data: studentScores,
        backgroundColor: colors[index % colors.length], // Use colors from our palette
    });
});

// Chart.js bar chart setup
const ctx = document.getElementById('scoresChart').getContext('2d');
const scoresChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: skills,
        datasets: datasets
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>

</body>
</html>
