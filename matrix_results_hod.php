<?php
session_start();
function getPrefixFromUserId($user_id)
{
    // First check: Extract prefix if 'HOD' is present
    if (preg_match('/^(.*?)(HOD)$/', $user_id, $matches)) {
        $prefix = $matches[1]; // Everything before 'HOD' as prefix
    }

    // Second check: Extract category if any specified keyword is present
    if (preg_match('/(BTECH|DEGREE|DIPLOMA|BPHARMACY)/', $user_id, $matches)) {
        $category = $matches[1]; // The specific category found
    }

    $result = ['prefix' => $prefix, 'category' => $category];

    return $result;
}




$averageSgpaData = [];
$branches = [];
$averageSgpas = [];
$prefix = '';
$category = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $prefixData = getPrefixFromUserId($user_id);
    if ($prefixData !== false) {
        $prefix = $prefixData['prefix'];
        $category = $prefixData['category'];
        switch ($category) {
            case 'BTECH':
                $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];
                break;
            case 'DEGREE':
            case 'DIPLOMA':
                $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2'];
                break;
            case 'BPHARMACY':
                $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];
                break;
            default:
                $semesters = [];
        }

        include 'sql_conn.php';

        // Get distinct class_ids from stdreg table
        $distinctClassSql = "SELECT DISTINCT class_id FROM stdreg WHERE class_id LIKE '$prefix%'";
        $classResult = $conn->query($distinctClassSql);

        if ($classResult) {
            while ($classRow = $classResult->fetch_assoc()) {
                $classId = $classRow['class_id'];
                $totalSgpa = 0;
                $count = 0;

                // Get reg_num for each class_id
                $regNumSql = "SELECT reg_num FROM stdreg WHERE class_id = '$classId'";
                $regResult = $conn->query($regNumSql);

                if ($regResult) {
                    while ($regRow = $regResult->fetch_assoc()) {
                        $regNum = $regRow['reg_num'];

                        // Loop through each semester
                        foreach ($semesters as $semester) {
                            $tableName = "{$category}_{$semester}_sgpa"; // Adjusted for category
                            $sgpaSql = "SELECT sgpa FROM `$tableName` WHERE reg_num = '$regNum'";
                            $sgpaResult = $conn->query($sgpaSql);

                            if ($sgpaResult && $sgpaRow = $sgpaResult->fetch_assoc()) {
                                $totalSgpa += $sgpaRow['sgpa'];
                                $count++;
                            }
                        }
                    }
                }

                if ($count > 0) {
                    $averageSgpaData[$classId] = $totalSgpa / $count;
                }
            }
        }

        $conn->close();

        $branches = array_keys($averageSgpaData);
        $averageSgpas = array_values($averageSgpaData);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Average SGPA by Section</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

                <div class="container mt-4">
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $prefix !== false) : ?>
                        <h2>Average SGPA by Section</h2>
                        <canvas id="averageSgpaChart"></canvas>
                    <?php else : ?>
                        <?php echo "<script>alert('No data to show');window.location.href='auth_matrix_home_hod.php';</script>"; ?>
                    <?php endif; ?>
                </div>
                <?php if (!empty($branches) && !empty($averageSgpas)) : ?>
                    <script>
                        var branches = <?php echo json_encode($branches); ?>;
                        var averageSgpas = <?php echo json_encode($averageSgpas); ?>;

                        var ctx = document.getElementById('averageSgpaChart').getContext('2d');
                        var averageSgpaChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: branches,
                                datasets: [{
                                    label: 'Average SGPA',
                                    data: averageSgpas,
                                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                                    borderColor: 'rgba(0, 123, 255, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        suggestedMax: 10 // Assuming SGPA is on a scale of 10
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true
                                    }
                                }
                            }
                        });
                    </script>
                <?php endif; ?>
</body>

</html>