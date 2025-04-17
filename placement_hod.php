<?php
session_start();
include 'sql_conn.php'; // Ensure this file exists and correctly initializes a $conn variable for MySQL connection.

$user_id = $_SESSION['user_id'] ?? '';
$prefix = strpos($user_id, 'HOD') !== false ? explode('HOD', $user_id)[0] : $user_id;

// Get all distinct class_ids matching prefix
// Assuming $prefix is sanitized and safe to use directly
$classIdsQuery = "SELECT DISTINCT class_id FROM placement_skills WHERE class_id LIKE '{$prefix}%'";
$classIdsResult = $conn->query($classIdsQuery);


$class_ids = [];
while ($row = $classIdsResult->fetch_assoc()) {
    $class_ids[] = $row['class_id'];
}

// Get all distinct skills
$skillsQuery = "SELECT DISTINCT skill FROM placement_skills";
$skillsResult = $conn->query($skillsQuery);

$skills = [];
while ($skillRow = $skillsResult->fetch_assoc()) {
    $skills[] = $skillRow['skill'];
}

// Calculate average score for each skill for each class_id
$averages = [];
foreach ($class_ids as $class_id) {
    foreach ($skills as $skill) {
        $avgQuery = "SELECT AVG(score) as avgScore FROM placement_skills WHERE class_id = ? AND skill = ?";
        $avgStmt = $conn->prepare($avgQuery);
        $avgStmt->bind_param("ss", $class_id, $skill);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $avgRow = $avgResult->fetch_assoc();
        $averages[$class_id][$skill] = $avgRow['avgScore'];
    }
}

$jsonAverages = json_encode($averages);
$jsonSkills = json_encode($skills);
$jsonClassIds = json_encode($class_ids);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Class Skill Averages</title>
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
                    <h2>Class Skill Averages</h2>
                    <canvas id="avgScoresChart"></canvas>
                </div>

                <script>
                    const averages = JSON.parse('<?= $jsonAverages ?>');
                    const skills = JSON.parse('<?= $jsonSkills ?>');
                    const classIds = JSON.parse('<?= $jsonClassIds ?>');
                    const ctx = document.getElementById('avgScoresChart').getContext('2d');

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

                    // Prepare the datasets for the chart, assigning colors to each skill
                    let datasets = skills.map((skill, index) => {
                        let backgroundColor = colors[index % colors.length];
                        let data = classIds.map(classId => averages[classId][skill] ? averages[classId][skill] : 0);
                        return {
                            label: skill,
                            data: data,
                            backgroundColor: backgroundColor,
                            borderColor: backgroundColor,
                            borderWidth: 1
                        };
                    });

                    const avgScoresChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: classIds,
                            datasets: datasets
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100 // Set y-axis maximum to 100
                                }
                            }
                        }
                    });
                </script>

</body>

</html>