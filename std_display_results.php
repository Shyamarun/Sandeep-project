<?php
session_start();
// MySQL database connection
include 'sql_conn.php';
$reg_num = $_SESSION['reg_num'];
$selectedSemesters = isset($_POST['selectedSemesters']) ? $_POST['selectedSemesters'] : [];

// Initialize variables
$course = '';
$semesters = [];
$sgpaData = [];

// Fetch course for the given reg_num
if ($reg_num) {
    $sqlCourse = "SELECT course FROM stdreg WHERE reg_num = ?";
    if ($stmt = $conn->prepare($sqlCourse)) {
        $stmt->bind_param("s", $reg_num);
        $stmt->execute();
        $resultCourse = $stmt->get_result();
        if ($resultCourse->num_rows > 0) {
            $course = $resultCourse->fetch_assoc()['course'];
        }
        $stmt->close();
    }
}
$studentDetails = [];
if ($reg_num) {
    $sqlStudentDetails = "SELECT clg_code, course, branch, full_name, aadhar_num, reg_num, profile_photo FROM stdreg WHERE reg_num = '$reg_num'";
    $resultStudentDetails = $conn->query($sqlStudentDetails);
    if ($resultStudentDetails->num_rows > 0) {
        $studentDetails = $resultStudentDetails->fetch_assoc();
    }
}

// Define semesters based on course
if ($course == 'BTECH' || $course == 'BPHARMACY') {
    $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];
} elseif ($course == 'DIPLOMA' || $course == 'DEGREE') {
    $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2'];
}
// Check if specific semesters are selected
if (!empty($selectedSemesters)) {
    foreach ($selectedSemesters as $semester) {
        // Set table name based on course
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

        if ($tableName) {
            $sql = "SELECT sgpa FROM $tableName WHERE reg_num = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $reg_num);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $sgpaData[$semester] = $row['sgpa'];
                } else {
                    $sgpaData[$semester] = 0;
                }
                $stmt->close();
            }
        }
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
    <!-- Additional CSS for Certificate styling -->
    <style>
        .certificate {
            border: 1px solid #000;
            padding: 20px;
            margin-top: 20px;
        }

        .certificate-header img {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .certificate-footer img {
            width: 100px;
            height: auto;
        }

        .certificate-body img {
            width: 100px;
            height: auto;
            float: right;
        }

        .certificate-content {
            clear: both;
        }

        @media (max-width: 768px) {
            .table-responsive-sm {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-screg_numing: touch;
            }

            .table-responsive-sm table {
                width: 100%;
            }

            .certificate-content {
                font-size: 0.8em;
                /* Adjust font size for smaller screens */
            }
        }

        .certificate-container {
            /* Optional border */
            page-break-after: always;
            /* This ensures each certificate starts on a new page when printed */
        }

        @media print {
            .certificate-container {
                page-break-after: always;
            }
        }

        #table-container {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
        }

        #table-container1 {
            background: rgba(255, 255, 255);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url("tt.jpg");
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Add further styling as needed */
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Form for SGPA and Certificate -->
        <form method="post" class="mb-4" id='table-container'>
            <input type="hidden" name="reg_num" value="<?php echo $_POST['reg_num']; ?>">
            <!-- Add checkboxes for each semester with a form to submit the selected semesters -->
            <?php foreach ($semesters as $semester) : ?>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="selectedSemesters[]" id="<?php echo $semester; ?>" value="<?php echo $semester; ?>" class="form-check-input" <?php echo in_array($semester, $selectedSemesters) ? 'checked' : ''; ?>>
                    <label for="<?php echo $semester; ?>" class="form-check-label"><?php echo $semester; ?></label>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Show Graph and Certificate</button>
        </form>

        <!-- SGPA Chart -->
        <div class="container" id="table-container1">
            <canvas id="sgpaChart"></canvas>
        </div>

        <?php if (!empty($selectedSemesters)) : ?>
            <?php foreach ($selectedSemesters as $semester) : ?>
                <?php
                // Set table name for results based on course
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

                if ($tableNameResults) {
                    $sqlResults = "SELECT * FROM $tableNameResults WHERE reg_num = ?";
                    if ($stmtResults = $conn->prepare($sqlResults)) {
                        $stmtResults->bind_param("s", $reg_num);
                        $stmtResults->execute();
                        $resultResults = $stmtResults->get_result();
                ?>
                        <div class="certificate-container">
                            <div class="certificate" id="table-container1">
                                <!-- Certificate header and student photo -->
                                <div class="certificate-header">
                                    <img src="avev.jpg" alt="Header">
                                </div>
                                <div class="certificate-body">
                                    <img src="<?php echo $studentDetails['profile_photo']; ?>" alt="Student Photo">
                                    <div class="certificate-content">
                                        <h1><?php echo $studentDetails['full_name']; ?></h1>
                                        <p>Course: <?php echo $studentDetails['course']; ?></p>
                                        <p>Branch: <?php echo $studentDetails['branch']; ?></p>
                                        <p>Aadhar No: <?php echo $studentDetails['aadhar_num']; ?></p>
                                        <p>Hall Ticket No: <?php echo $studentDetails['reg_num']; ?></p>
                                        <p>Semester: <?php echo str_replace('_', '.', $semester); ?></p>

                                        <!-- Results table -->
                                        <div class="table-responsive-sm">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Course Code</th>
                                                        <th>Title</th>
                                                        <th>Grade</th>
                                                        <th>Credits</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($rowResults = $resultResults->fetch_assoc()) : ?>
                                                        <tr>
                                                            <td><?php echo $rowResults['sub_code']; ?></td>
                                                            <td><?php echo $rowResults['sub_name']; ?></td>
                                                            <td><?php echo $rowResults['grade']; ?></td>
                                                            <td><?php echo $rowResults['credit']; ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Certificate footer -->
                                <div class="certificate-footer">
                                    <img src="av.jpeg" alt="Emblem">
                                    <p>Controller of Examinations</p>
                                </div>
                            </div>
                        </div>
                <?php
                        $stmtResults->close();
                    }
                }
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Chart.js Script for SGPA -->
    <script>
        var sgpaData = <?php echo json_encode(array_merge([0], array_values($sgpaData))); ?>;
        var semesterLabels = <?php echo json_encode(array_merge(['0'], array_keys($sgpaData))); ?>;

        var ctx = document.getElementById('sgpaChart').getContext('2d');
        var sgpaChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: semesterLabels,
                datasets: [{
                    label: 'SGPA for reg_num <?php echo $reg_num; ?>',
                    data: sgpaData,
                    backgroundColor: 'rgba(<?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, 0.2)',
                    borderColor: 'rgba(<?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 9
                    },
                    y: {
                        beginAtZero: true,
                        max: 10
                    }
                }
            }
        });
    </script>
    <?php $conn->close(); ?>
</body>

</html>