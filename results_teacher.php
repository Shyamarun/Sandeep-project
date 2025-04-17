<?php
// MySQL database connection
include 'sql_conn.php';
session_start();
// Get class_id from the form submission
$class_id = $_SESSION['class_id'] ?? '';
$course = '';
$semesters = [];
if ($class_id) {
    $sqlCourse = "SELECT DISTINCT course FROM stdreg WHERE class_id = ?";
    if ($stmt = $conn->prepare($sqlCourse)) {
        $stmt->bind_param("s", $class_id);
        $stmt->execute();
        $resultCourse = $stmt->get_result();
        if ($resultCourse->num_rows > 0) {
            $course = $resultCourse->fetch_assoc()['course'];
        }
        $stmt->close();
    }
}
// Get reg_num from stdreg table for the specified class_id
$sql_reg_nums = "SELECT reg_num FROM stdreg WHERE class_id = '$class_id'";
$result_reg_nums = $conn->query($sql_reg_nums);
$reg_nums = [];
while ($row = $result_reg_nums->fetch_assoc()) {
    $reg_nums[] = $row['reg_num'];
}

if ($course == 'BTECH' || $course == 'BPHARMACY') {
    $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];
} elseif ($course == 'DIPLOMA' || $course == 'DEGREE') {
    $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2'];
}

// Initialize an array to store SGPA data for each semester
$sgpaData = [];
$selectedreg_num = $_POST['reg_num'] ?? '';
if (empty($selectedreg_num)) {
    // Fetch SGPA for all students in the class for each semester
    foreach ($semesters as $semester) {
        switch ($course) {
            case 'BTECH':
                $tableName = "btech_{$semester}_sgpa";
                break;
            case 'BPHARMACY':
                $tableName = "bpharmacy_{$semester}_sgpa";
                break;
            case 'DIPLOMA':
                $tableName = "diploma_{$semester}_sgpa";
                break;
            case 'DEGREE':
                $tableName = "degree_{$semester}_sgpa";
                break;
            default:
                $tableName = "";
        }
        $semesterSgpa = [];

        for ($i = 1; $i <= 4; $i++) {
            $sql = "SELECT reg_num, sgpa FROM $tableName WHERE reg_num IN (SELECT reg_num FROM stdreg WHERE class_id='$class_id')";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $semesterSgpa[$row['reg_num']] = $row['sgpa'];
            }
        }

        $sgpaData[$semester] = $semesterSgpa;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester vs SGPA Graph</title>
    <!-- Include Bootstrap and Chart.js libraries -->
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
                    <!-- Dropdown form for reg_num number selection -->
                    <form method="post" class="mb-4">
                        <select id="selectreg_num" name="reg_num" class="form-select mb-3">
                            <option value="">Select reg_num Number</option>
                            <?php foreach ($reg_nums as $reg_num) : ?>
                                <option value="<?php echo $reg_num; ?>" <?php echo $selectedreg_num === $reg_num ? 'selected' : ''; ?>><?php echo $reg_num; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Show Results</button>
                    </form>

                    <!-- Canvas for the SGPA chart (only displayed for class-wide data) -->
                    <?php if (empty($selectedreg_num)) : ?>
                        <div class="container">
                            <canvas id="sgpaChart"></canvas>
                        </div>
                    <?php endif; ?>

                    <!-- Detailed results table for individual reg_num number -->
                    <?php if (!empty($selectedreg_num)) : ?>
                        <div class="table-responsive">
                            <?php foreach ($semesters as $semester) : ?>
                                <?php
                                $tableNameResults = "";
                                switch ($course) {
                                    case 'BTECH':
                                        $tableNameResults = "btech_{$semester}_results";
                                        break;
                                    case 'BPHARMACY':
                                        $tableNameResults = "bpharmacy_{$semester}_results";
                                        break;
                                    case 'DIPLOMA':
                                        $tableNameResults = "diploma_{$semester}_results";
                                        break;
                                    case 'DEGREE':
                                        $tableNameResults = "degree_{$semester}_results";
                                        break;
                                }
                                $sqlResults = "SELECT * FROM $tableNameResults WHERE reg_num = '$selectedreg_num'";
                                $resultResults = $conn->query($sqlResults);
                                ?>

                                <?php if ($resultResults->num_rows > 0) : ?>
                                    <center>
                                        <h4 class="mt-3">Results for Semester <?php echo $semester; ?></h4>
                                    </center>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Roll Number</th>
                                                <th>Subject Code</th>
                                                <th>Subject Name</th>
                                                <th>Internals</th>
                                                <th>Grade</th>
                                                <th>Credit</th>
                                                <th>Grade Point</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($rowResults = $resultResults->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?php echo $rowResults['reg_num']; ?></td>
                                                    <td><?php echo $rowResults['sub_code']; ?></td>
                                                    <td><?php echo $rowResults['sub_name']; ?></td>
                                                    <td><?php echo $rowResults['internals']; ?></td>
                                                    <td><?php echo $rowResults['grade']; ?></td>
                                                    <td><?php echo $rowResults['credit']; ?></td>
                                                    <td><?php echo $rowResults['grade_point']; ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- JavaScript to render the SGPA chart (only for class-wide data) -->
                <?php if (empty($selectedreg_num)) : ?>
                    <script>
                        var sgpaData = <?php echo json_encode($sgpaData); ?>;
                        var semesterLabels = <?php echo json_encode($semesters); ?>;
                        var chartData = [];

                        Object.keys(sgpaData).forEach(function(key) {
                            var dataset = {
                                label: 'Semester ' + key,
                                data: Object.values(sgpaData[key]),
                                backgroundColor: 'rgba(' + Math.floor(Math.random() * 255) + ', ' + Math.floor(Math.random() * 255) + ', ' + Math.floor(Math.random() * 255) + ', 0.2)',
                                borderColor: 'rgba(' + Math.floor(Math.random() * 255) + ', ' + Math.floor(Math.random() * 255) + ', ' + Math.floor(Math.random() * 255) + ', 1)',
                                borderWidth: 1
                            };
                            chartData.push(dataset);
                        });

                        var ctx = document.getElementById('sgpaChart').getContext('2d');
                        var sgpaChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys(sgpaData[semesterLabels[0]]),
                                datasets: chartData
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 10
                                    }
                                }
                            }
                        });
                    </script>
                <?php endif; ?>

</body>

</html>
<?php $conn->close(); ?>