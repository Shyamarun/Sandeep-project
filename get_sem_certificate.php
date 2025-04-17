<?php
$reg_num = isset($_POST['reg_num']) ? $_POST['reg_num'] : '';
include 'sql_conn.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($reg_num)) {
    $selectedSemester = $_POST['selectedSemester'];
    $studentDetails = [];
    if ($reg_num) {
        $sqlStudentDetails = "SELECT clg_code, course, branch, full_name, aadhar_num, reg_num, profile_photo FROM stdreg WHERE reg_num = '$reg_num'";
        $resultStudentDetails = $conn->query($sqlStudentDetails);
        if ($resultStudentDetails->num_rows > 0) {
            $studentDetails = $resultStudentDetails->fetch_assoc();
        }
    }
    $courseSql = "SELECT course FROM stdreg WHERE reg_num = '$reg_num'";
    $courseResult = $conn->query($courseSql);
    if ($courseResult && $courseResult->num_rows > 0) {
        $courseRow = $courseResult->fetch_assoc();
        $course = $courseRow['course'];

        // Define semesters based on the course
        $semesters = ($course == 'DEGREE' || $course == 'DIPLOMA') ? ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2'] : ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];

        // Initialize an array to store SGPA data for each semester
        $sgpaData = [];
        $tableName = '';

        // Check if a specific semester is selected
        if (!empty($selectedSemester)) {
            $tableName = "";
            switch ($course) {
                case 'BTECH':
                    $tableName = "btech_{$selectedSemester}_sgpa";
                    break;
                case 'BPHARMACY':
                    $tableName = "bpharmacy_{$selectedSemester}_sgpa";
                    break;
                case 'DEGREE':
                    $tableName = "degree_{$selectedSemester}_sgpa";
                    break;
                case 'DIPLOMA':
                    $tableName = "diploma_{$selectedSemester}_sgpa";
                    break;
            }
            $stmt = $conn->prepare("SELECT sgpa FROM {$tableName} WHERE roll = ?");
            if ($stmt) {
                $stmt->bind_param("s", $reg_num);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $sgpaData[$selectedSemester] = $row['sgpa'];
                } else {
                    $sgpaData[$selectedSemester] = 0;
                }
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester vs SGPA Graph</title>
    <link rel="stylesheet" href="results.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
                body {
            font-family: Arial, sans-serif;
        }

        .certificate {
            border: 1px solid #000;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
        }

        .certificate img {
            max-width: 100%;
            height: auto;
        }

        .certificate-header img,
        .certificate-footer img {
            max-height: 100px;
            /* Adjust the max-height as necessary */
        }

        .certificate-body img {
            float: right;
            margin-left: 20px;
            max-width: 150px;
            /* Adjust the max-width as necessary */
            max-height: 150px;
            /* Adjust the max-height as necessary */
        }

        .certificate-content h1 {
            font-size: 24px;
            /* Adjust font size as necessary */
            margin-top: 0;
        }

        .certificate-content p {
            font-size: 16px;
            /* Adjust font size as necessary */
            margin: 2px 0;
            /* Adjust margin as necessary */
        }

        .certificate-table {
            width: 100%;
            table-layout: fixed;
            margin-top: 15px;
        }

        .certificate-table th,
        .certificate-table td {
            text-align: left;
            word-wrap: break-word;
            padding: 8px;
            border: 1px solid #000;
        }

        .certificate-table th {
            background-color: #f2f2f2;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .controller-signature {
            text-align: right;
            margin-right: 10px;
            /* Adjust the margin as necessary */
        }

        @media (max-width: 768px) {
            .certificate-body img {
                width: 50px;
            }

            .certificate-content h1,
            .certificate-content p {
                font-size: smaller;
            }
        }

        @media (max-width: 600px) {
            .certificate-table {
                width: 100%;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Form for SGPA and Certificate -->
        <form method="post" class="mb-4">
            <?php foreach ($semesters as $semester) : ?>
                <div class="form-check form-check-inline">
                    <input type="radio" name="selectedSemester" id="<?php echo $semester; ?>" value="<?php echo $semester; ?>" class="form-check-input" <?php echo ($selectedSemester == $semester) ? 'checked' : ''; ?>>
                    <label for="<?php echo $semester; ?>" class="form-check-label"><?php echo $semester; ?></label>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Show Graph and Certificate</button>
        </form>
        <!-- Certificate -->
        <?php if (!empty($studentDetails)) : ?>
            <div class="certificate">
                <div class="certificate-header">
                    <!-- Replace with your actual image path -->
                    <img src="AVEV.jpg" alt="Header">
                </div>
                <div class="certificate-body">
                    <!-- Replace with your actual image path -->
                    <img src="<?php echo $studentDetails['profile_photo']; ?>" alt="Student Photo">
                    <div class="certificate-content">
                        <h1><?php echo $studentDetails['full_name']; ?></h1>
                        <p>Serial No: <?php echo $studentDetails['clg_code']; ?></p>
                        <p>Course: <?php echo $studentDetails['course']; ?></p>
                        <p>Branch: <?php echo $studentDetails['branch']; ?></p>
                        <p>Aadhar No: <?php echo $studentDetails['aadhar_num']; ?></p>
                        <p>Hall Ticket No: <?php echo $studentDetails['reg_num']; ?></p>
                        <!-- Display the results table -->
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
                                <?php
                                    // Assuming $selectedSemester, $course, and $reg_num are defined and available

                                    $tableNameResults = "";

                                    switch ($course) {
                                        case 'BTECH':
                                            $tableNameResults = "btech_{$selectedSemester}_results";
                                            break;
                                        case 'BPHARMACY':
                                            $tableNameResults = "bpharmacy_{$selectedSemester}_results";
                                            break;
                                        case 'DEGREE':
                                            $tableNameResults = "degree_{$selectedSemester}_results";
                                            break;
                                        case 'DIPLOMA':
                                            $tableNameResults = "diploma_{$selectedSemester}_results";
                                            break;
                                    }

                                    $stmtResults = $conn->prepare("SELECT * FROM {$tableNameResults} WHERE roll = ?");
                                    $stmtResults->bind_param("s", $reg_num);
                                    $stmtResults->execute();
                                    $resultResults = $stmtResults->get_result();

                                    if ($resultResults->num_rows > 0) {
                                        echo "<center><h4 class='mt-3'>Results for Semester $selectedSemester</h4></center>";
                                        echo "<table class='table table-bordered'>";
                                        echo "<thead><tr><th>Roll</th><th>Subject Code</th><th>Subject Name</th><th>Internals</th><th>Grade</th><th>Credit</th><th>Grade Point</th></tr></thead><tbody>";
                                        while ($rowResults = $resultResults->fetch_assoc()) {
                                            echo "<tr><td>{$rowResults['roll']}</td><td>{$rowResults['sub_code']}</td><td>{$rowResults['sub_name']}</td><td>{$rowResults['internals']}</td><td>{$rowResults['grade']}</td><td>{$rowResults['credit']}</td><td>{$rowResults['grade_point']}</td></tr>";
                                        }
                                        echo "</tbody></table>";
                                    }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="certificate-footer">
                    <img src="AV.jpeg" alt="Emblem">
                    <p>P. Sandeep</p>
                    <p>Controller of Examinations</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php $conn->close(); ?>
</body>

</html>