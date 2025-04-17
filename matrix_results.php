<?php
session_start();
function getPrefixFromUserId($user_id)
{
    if (preg_match('/^(.*?)(BTECH|DEGREE|DIPLOMA|BPHARMACY|DIR|PRI|VPRI|AO)/', $user_id, $matches)) {
        return ['prefix' => $matches[1], 'category' => $matches[2]]; // Return the string before the category and the category itself
    }
    return false;
}

// Initialize the variables
$averageSgpaData = [];
$branches = [];
$averageSgpas = [];

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $prefixData = getPrefixFromUserId($user_id);
    if ($prefixData !== false) {
        $prefix = $prefixData['prefix'];
        $category = $prefixData['category'];
        // Set semesters and table name based on category
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


        // MySQL database connection
        include 'sql_conn.php';

        // Loop through each semester
        foreach ($semesters as $semester) {
            $tableName = strtolower($category) . "_{$semester}_sgpa";
            $sql = "SELECT s.class_id, AVG(sg.sgpa) as avg_sgpa
                    FROM `$tableName` sg 
                    INNER JOIN stdreg s ON s.reg_num = sg.reg_num
                    WHERE s.class_id LIKE '$prefix%'
                    GROUP BY s.class_id";


            if ($result = $conn->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $branch = substr($row["class_id"], strlen($prefix) + 1, 3);
                    $branch_name = ''; // Initialize as empty string

                    if ($category == 'BTECH') {
                        $branchNames = [
                            'CSE', 'CSM', 'CSD', 'MECH', 'EEE', 'ECE', 'AI'
                        ];
                        foreach ($branchNames as $name) {
                            if (strpos($row["class_id"], $name) !== false) {
                                $branch_name = $name;
                                break;
                            }
                        }
                    } elseif (
                        $category == 'DIPLOMA'
                    ) {
                        $branchNames = [
                            'MECH', 'EEE', 'CSE', 'ECE'
                        ];
                        foreach ($branchNames as $name) {
                            if (strpos($row["class_id"], $name) !== false) {
                                $branch_name = $name;
                                break;
                            }
                        }
                    } elseif (
                        $category == 'DEGREE'
                    ) {
                        // Example DEGREE identifiers; adjust as needed
                        $branchNames = ['BCom', 'BSc', 'BA']; // Simplify for example
                        foreach ($branchNames as $name) {
                            if (strpos($row["class_id"], $name) !== false) {
                                $branch_name = $name;
                                break;
                            }
                        }
                    } elseif (
                        $category == 'BPHARMACY'
                    ) {
                        $branchNames = ['PharmD', 'BPharm', 'MPharm'];
                        foreach ($branchNames as $name) {
                            if (strpos($row["class_id"], $name) !== false) {
                                $branch_name = $name;
                                break;
                            }
                        }
                    }

                    $averageSgpaData[$branch_name] = isset($averageSgpaData[$branch])
                        ? ($averageSgpaData[$branch] + $row["avg_sgpa"]) / 2
                        : $row["avg_sgpa"];
                }
            } else {
                echo "<script>alert('No data to show');window.location.href='auth_matrix_home_page.php';</script>";
            }
        }

        $conn->close();

        // Prepare data for the bar graph
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
    <title>Average SGPA by Branch</title>
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
                        <h2>Average SGPA by Branch</h2>
                        <canvas id="averageSgpaChart"></canvas>
                    <?php else : ?>
                        <p>User ID is not valid or no data posted.</p>
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