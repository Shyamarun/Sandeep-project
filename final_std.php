<?php
// MySQL database connection
include 'sql_conn.php';
session_start();
$reg_num = $_SESSION['reg_num'] ?? '';
$selectedSemesters = isset($_POST['selectedSemesters']) ? $_POST['selectedSemesters'] : [];

// Define semesters
$semesters = ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];

// Initialize an array to store SGPA data for each semester
$sgpaData = [];

// Check if specific semesters are selected
if (!empty($selectedSemesters)) {
    // Loop through each selected semester and fetch SGPA for the selected roll number
    foreach ($selectedSemesters as $semester) {
        $tableName = "B_Tech_{$semester}_sgpa";
        $sql = "SELECT sgpa FROM `$tableName` WHERE roll = '$reg_num'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sgpaData[$semester] = $row['sgpa'];
        } else {
            // If no data is found for the current semester, set SGPA to 0
            $sgpaData[$semester] = 0;
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
</head>
<body>
    <div class="container mt-4">
        <!-- Display the roll number input and form -->
        <form method="post" class="mb-4">
            <input type="hidden" name="reg_num" value="20Q71A05E7">
            <!--div class="mb-3">
                <label for="selectedRoll" class="form-label">Enter Roll Number:</label>
                <input type="text" name="selectedRoll" id="selectedRoll" class="form-control" value="<?php echo $selectedRoll; ?>">
            </div-->

            <!-- Add checkboxes for each semester with a form to submit the selected semesters -->
            <?php foreach ($semesters as $semester) : ?>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="selectedSemesters[]" id="<?php echo $semester; ?>" value="<?php echo $semester; ?>" class="form-check-input" <?php echo in_array($semester, $selectedSemesters) ? 'checked' : ''; ?>>
                    <label for="<?php echo $semester; ?>" class="form-check-label"><?php echo $semester; ?></label>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Show Graph</button>
        </form>

        <!-- Display the canvas for the SGPA chart -->
        <div class="container">
            <canvas id="sgpaChart"></canvas>
        </div>
    </div>

    <!-- Display the results table if a semester is selected -->
    <?php if (!empty($selectedSemesters)) : ?>
        <div class="container">
            <div class="table-responsive">
                <?php foreach ($selectedSemesters as $selectedSemester) : ?>
                    <?php
                    $tableNameResults = "B_Tech_{$selectedSemester}_results";
                    $sqlResults = "SELECT * FROM `$tableNameResults` WHERE roll = '$reg_num'";
                    $resultResults = $conn->query($sqlResults);
                    ?>

                    <?php if ($resultResults->num_rows > 0) : ?>
                        <!-- Display the table for each selected semester -->
                        <center><h4 class="mt-3">Results for Semester <?php echo $selectedSemester; ?></h4></center>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Roll</th>
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
                                        <td><?php echo $rowResults['roll']; ?></td>
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
        </div>
    <?php endif; ?>

    <script>
    // Create datasets for each semester
    var sgpaData = <?php echo json_encode(array_merge([0], array_values($sgpaData))); ?>;
    var semesterLabels = <?php echo json_encode(array_merge(['0'], array_keys($sgpaData))); ?>;

    // Create a line chart using Chart.js
    var ctx = document.getElementById('sgpaChart').getContext('2d');
    var sgpaChart = new Chart(ctx, {
        type: 'line', // Change the chart type to line
        data: {
            labels: semesterLabels,
            datasets: [{
                label: 'SGPA for Roll <?php echo $reg_num; ?>',
                data: sgpaData,
                backgroundColor: 'rgba(<?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, 0.2)',
                borderColor: 'rgba(<?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    max: 9 // Set the maximum number of ticks on the x-axis
                },
                y: {
                    beginAtZero: true,
                    max: 10  // Set the maximum value for the y-axis
                }
            }
        }
    });
</script>






    <?php $conn->close(); ?>
</body>
</html>
