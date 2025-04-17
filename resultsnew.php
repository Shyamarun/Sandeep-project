<?php
// MySQL database connection
include 'sql_conn.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_num = isset($_POST['reg_num']) ? $_POST['reg_num'] : '';
    $selectedSemesters = isset($_POST['selectedSemesters']) ? $_POST['selectedSemesters'] : [];

    // Define semesters
    $semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];

    // Initialize an array to store SGPA data for each semester
    $sgpaData = [];

    // Fetch student details for the certificate
    $studentDetails = [];
    if ($reg_num) {
        $sqlStudentDetails = "SELECT clg_code, course, branch, full_name, aadhar_num, reg_num, profile_photo FROM stdreg WHERE reg_num = ?";
        $stmt = $conn->prepare($sqlStudentDetails);
        $stmt->bind_param("s", $reg_num);
        $stmt->execute();
        $resultStudentDetails = $stmt->get_result();
        if ($resultStudentDetails->num_rows > 0) {
            $studentDetails = $resultStudentDetails->fetch_assoc();
        }
        $stmt->close();
    }

    // Check if specific semesters are selected
    if (!empty($selectedSemesters) && $reg_num) {
        foreach ($selectedSemesters as $semester) {
            $tableName = "B_Tech_{$semester}_sgpa";
            $sql = "SELECT sgpa FROM `$tableName` WHERE roll = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $reg_num);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $sgpaData[$semester] = $row['sgpa'];
            } else {
                $sgpaData[$semester] = 0; // No data found for the current semester
            }
            $stmt->close();
        }
    }
}

// Additional PHP code for calculating SGPA...

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
        /* Your CSS styling to match the certificate layout */
        .certificate {
            border: 1px solid #000;
            padding: 20px;
            margin-top: 20px;
        }

        .certificate-header img {
            width: 100%;
            height: auto;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .certificate-footer img {
            width: 50px;
            height: auto;
        }

        .certificate-body {
            margin-top: 20px;
        }

        /* Further styling can be added to match the provided grade card image */
    </style>
</head>

<body>
    <div class="container mt-4">
        <form method="post" class="mb-4">
            <label for="reg_num">Enter Roll Number:</label>
            <input type="text" name="reg_num" id="reg_num" class="form-control" required>
            <div class="mt-3">
                <?php foreach ($semesters as $semester) : ?>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="selectedSemesters[]" id="<?php echo $semester; ?>" value="<?php echo $semester; ?>" class="form-check-input">
                        <label for="<?php echo $semester; ?>" class="form-check-label"><?php echo $semester; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Show Graph and Certificate</button>
        </form>

        <?php if (!empty($sgpaData)) : ?>
            <!-- Display the SGPA chart here -->
            <!-- Your Chart.js implementation -->
        <?php endif; ?>

        <?php if (!empty($studentDetails)) : ?>
            <!-- Display the certificate here -->
            <div class="certificate">
                <!-- Your certificate HTML and PHP to display student details and results -->
                <!-- Replace 'path_to_logo.jpg' with the actual path to your logo -->
                <img src="path_to_logo.jpg" alt="Institute Logo" style="width:100px; height:auto;">
                <h1>Certificate of Grades</h1>
                <p>Serial No: <?php echo htmlspecialchars($studentDetails['clg_code']); ?></p>
                <p>Examination: <?php echo htmlspecialchars($studentDetails['course']); ?></p>
                <p>Branch: <?php echo htmlspecialchars($studentDetails['branch']); ?></p>
                <p>Name: <?php echo htmlspecialchars($studentDetails['full_name']); ?></p>
                <p>Aadhar No.: <?php echo htmlspecialchars($studentDetails['aadhar_num']); ?></p>
                <p>Hall Ticket No.: <?php echo htmlspecialchars($studentDetails['reg_num']); ?></p>
                <!-- Replace 'path_to_student_photo.jpg' with the actual path to the student's photo -->
                <img src="path_to_student_photo.jpg" alt="Student Photo" style="width:100px; height:auto;">
                <!-- Results table will be here -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th>SGPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sgpaData as $semester => $sgpa) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($semester); ?></td>
                                <td><?php echo htmlspecialchars($sgpa); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Emblem image -->
                <!-- Replace 'path_to_emblem.jpg' with the actual path to your emblem -->
                <img src="path_to_emblem.jpg" alt="Institute Emblem" style="width:50px; height:auto;">
                <p>Controller of Examinations: P. Sandeep</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Your Chart.js script to generate the SGPA chart
    </script>
    <?php $conn->close(); ?>
</body>

</html>