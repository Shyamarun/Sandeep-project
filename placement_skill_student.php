<?php
session_start();
include 'sql_conn.php'; // Ensure this file exists and correctly initializes a $conn variable for MySQL connection.

$reg_num = $_SESSION['reg_num'] ?? '';

// Get all distinct skills
$skillsQuery = "SELECT DISTINCT skill FROM placement_skills";
$skillsResult = $conn->query($skillsQuery);

$skills = [];
while ($skillRow = $skillsResult->fetch_assoc()) {
    $skills[] = $skillRow['skill'];
}

// Calculate average score for each skill
$averages = [];
foreach ($skills as $skill) {
    $avgQuery = "SELECT AVG(score) AS avgScore FROM placement_skills WHERE reg_num = ? AND skill = ?";
    $avgStmt = $conn->prepare($avgQuery);
    $avgStmt->bind_param("ss", $reg_num, $skill);
    $avgStmt->execute();
    $avgResult = $avgStmt->get_result();
    $avgRow = $avgResult->fetch_assoc();
    $averages[$skill] = $avgRow['avgScore'];
}

// Prepare data for the graph
$jsonSkills = json_encode(array_keys($averages));
$jsonAverages = json_encode(array_values($averages));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Skill Average Scores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('st.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .chart-container {
            width: 80%;
            margin: auto;
            margin-bottom: 20px;
        }

        .attendance-table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .attendance-table th,
        .attendance-table td {
            padding: 2px;
            text-align: center;
        }

        .attendance-table th {
            color: #333;
        }

        .color-legend {
            display: inline-block;
            width: 40px;
            height: 15px;
            border: 1px solid #000;
        }

        .attendance-percentage {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .chart-container {
                width: 100%;
                margin-bottom: 20px;
            }

            .attendance-table,
            .attendance-table th,
            .attendance-table td {
                padding: 1px;
            }

            .color-legend {
                width: 30px;
            }
        }

        /* Styles from the second code */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            border: 2px solid #007bff;
        }

        table {
            background: transparent;
        }

        th,
        td {
            color: #333;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <h2> </h2>
            <table class="table table-bordered">
                <div class="container mt-5">
                    <h2>Average Scores by Skill for <?= htmlspecialchars($reg_num) ?></h2>
                    <canvas id="scoresChart"></canvas>
                </div>

                <script>
                    const skills = JSON.parse('<?= $jsonSkills ?>');
                    const averages = JSON.parse('<?= $jsonAverages ?>');
                    const ctx = document.getElementById('scoresChart').getContext('2d');

                    const colors = [
                        'rgba(255, 99, 132, 0.8)', // Red
                        'rgba(54, 162, 235, 0.8)', // Blue
                        'rgba(255, 206, 86, 0.8)', // Yellow
                        'rgba(75, 192, 192, 0.8)', // Green
                        'rgba(153, 102, 255, 0.8)', // Purple
                        'rgba(255, 159, 64, 0.8)', // Orange
                        'rgba(22, 160, 133, 0.8)', // Turquoise
                        'rgba(39, 174, 96, 0.8)', // Emerald
                        'rgba(41, 128, 185, 0.8)', // Nephritis
                        'rgba(142, 68, 173, 0.8)', // Amethyst
                        'rgba(243, 156, 18, 0.8)', // Sunflower
                        'rgba(211, 84, 0, 0.8)', // Carrot
                        'rgba(192, 57, 43, 0.8)', // Pomegranate
                        'rgba(127, 140, 141, 0.8)', // Asbestos
                        'rgba(22, 160, 133, 0.8)', // Green Sea
                        'rgba(44, 62, 80, 0.8)', // Midnight Blue
                        'rgba(231, 76, 60, 0.8)', // Alizarin
                        'rgba(230, 126, 34, 0.8)', // Pumpkin
                        'rgba(155, 89, 182, 0.8)', // Wisteria
                        'rgba(52, 73, 94, 0.8)' // Wet Asphalt
                    ];

                    // Mapping colors to skills
                    let backgroundColors = skills.map((skill, index) => colors[index % colors.length]);

                    const scoresChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: skills,
                            datasets: [{
                                label: 'Average Score',
                                data: averages,
                                backgroundColor: backgroundColors,
                                borderColor: backgroundColors.map(color => color.replace('0.8', '1')), // Making border colors a bit darker
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100 // Ensuring the maximum value of y-axis is 100
                                }
                            }
                        }
                    });
                </script>


</body>

</html>