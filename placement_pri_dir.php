<?php
session_start();
include 'sql_conn.php'; // Make sure this file properly sets up a connection to your MySQL database.

$user_id = $_SESSION['user_id'] ?? '';
$classId = '';
// Function to extract prefix from user_id
function extractPrefix($user_id)
{
    $prefixes = ['DIR', 'PRI', 'AO', 'VPRI'];
    foreach ($prefixes as $prefix) {
        if (strpos($user_id, $prefix) !== false) {
            return explode($prefix, $user_id)[0];
        }
    }
    return $user_id; // Return original user_id if no prefixes found
}

$prefix = extractPrefix($user_id);

// Fetch collegeCode and course
$registrationQuery = "SELECT collegeCode, course FROM pri_dir_registration WHERE user_id = ?";
$regStmt = $conn->prepare($registrationQuery);
$regStmt->bind_param("s", $user_id);
$regStmt->execute();
$regResult = $regStmt->get_result();
$registration = $regResult->fetch_assoc();

$collegeCode = $registration['collegeCode'] ?? '';
$course = $registration['course'] ?? '';

// Get all distinct branches
$branchesQuery = "SELECT DISTINCT branch FROM stdreg WHERE clg_code = ? AND course = ?";
$branchesStmt = $conn->prepare($branchesQuery);
$branchesStmt->bind_param("ss", $collegeCode, $course);
$branchesStmt->execute();
$branchesResult = $branchesStmt->get_result();

$branches = [];
while ($branchRow = $branchesResult->fetch_assoc()) {
    $branches[] = $branchRow['branch'];
}

// Calculate average score for each skill for each branch
$averages = [];
foreach ($branches as $branch) {
    $classId = $prefix . $branch; // Concatenated class_id
    $avgQuery = "SELECT skill, AVG(score) AS avgScore FROM placement_skills WHERE class_id LIKE '{$classId}%' GROUP BY skill";
    $avgResult = $conn->query($avgQuery);
    while ($avgRow = $avgResult->fetch_assoc()) {
        $averages[$branch][$avgRow['skill']] = $avgRow['avgScore'];
    }
}

// Prepare data for the graph
$jsonAverages = json_encode($averages);
$jsonBranches = json_encode(array_keys($averages));
$jsonSkills = json_encode(array_unique(call_user_func_array('array_merge', array_map('array_keys', array_values($averages))))); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Branch Skill Averages</title>
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
                    <h2>Branch Skill Averages</h2>
                    <canvas id="branchSkillsChart"></canvas>
                </div>

                <script>
                    const averages = JSON.parse('<?= $jsonAverages ?>');
                    const branches = JSON.parse('<?= $jsonBranches ?>');
                    const skills = JSON.parse('<?= $jsonSkills ?>');
                    const ctx = document.getElementById('branchSkillsChart').getContext('2d');

                    const colors = [
                        // Add your 20 colors here
                    ];

                    // Prepare datasets
                    let datasets = skills.map((skill, index) => ({
                        label: skill,
                        data: branches.map(branch => averages[branch][skill] || 0),
                        backgroundColor: colors[index % colors.length],
                    }));

                    const branchSkillsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: branches,
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