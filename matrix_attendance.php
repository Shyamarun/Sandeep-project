<?php
session_start();
include 'sql_conn.php'; // Include your SQL connection file

// Function to check if user_id meets the specific conditions
function isValidUserId($user_id, &$prefix = null)
{
    if (preg_match('/^(.*?)((DIR)|(PRI)|(VPRI)|(AO))/', $user_id, $matches)) {
        $prefix = $matches[1]; // Store the string before DIR, PRI, VPRI, or AO
        return true;
    }
    return false;
}

// Function to calculate attendance percentage
function getAttendanceData($conn, $prefix)
{
    $attendanceData = [];

    // Retrieve distinct clg_code and course
    $clg_courses = $conn->query("SELECT DISTINCT clg_code, course FROM stdreg");

    if ($clg_courses) {
        while ($row = $clg_courses->fetch_assoc()) {
            $concatenated = strtoupper($row['clg_code'] . $row['course']);

            if ($concatenated == $prefix) {
                // Get distinct branches for the matched course
                $courseUpper = strtoupper($row['course']);
                $branches = $conn->query("SELECT DISTINCT branch FROM stdreg WHERE course = '$courseUpper'");

                if ($branches) {
                    while ($branch_row = $branches->fetch_assoc()) {
                        $branch_code = strtoupper($prefix . $branch_row['branch']);
                        $branch_upper = strtoupper($branch_row['branch']);
                        // SQL query to get attendance data
                        $sql = "SELECT SUM(present_students) as total_present, SUM(total_students) as total_students 
                                FROM attendance_summary 
                                WHERE class_id LIKE '$branch_code%'";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $attendancePercentage = 0;
                            if ($row['total_students'] > 0) {
                                $attendancePercentage = ($row['total_present'] / $row['total_students']) * 100;
                            }
                            $attendanceData[$branch_upper] = $attendancePercentage;
                        }
                    }
                }
            }
        }
    }

    return $attendanceData;
}

// Check if user_id is set and valid
$prefix = ''; // Initialize prefix variable
if (isset($_SESSION['user_id']) && isValidUserId($_SESSION['user_id'], $prefix)) {
    $attendanceData = getAttendanceData($conn, $prefix);
    arsort($attendanceData); // Sort the attendance data in descending order
} else {
    echo "<script>alert('Invalid User ID');window.location.href='auth_matrix_home_page.php'"; // If invalid, the script will end here, so ensure you handle this properly.
    exit;
}

// HTML and JavaScript will follow...
// [The rest of your HTML and JavaScript code remains unchanged]
?>


<!DOCTYPE html>
<html>

<head>
    <title>Attendance Summary</title>
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
                    <h2>Attendance Summary</h2>
                    <div class="chart-container">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Legend</th>
                                <th class="attendance-percentage">Attendance (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $colors = [
                                'rgba(78, 121, 167)', 'rgba(242, 142, 44)', 'rgba(225, 87, 89)',
                                'rgba(118, 183, 178)', 'rgba(89, 161, 79)', 'rgba(237, 201, 73)',
                                'rgba(175, 122, 161)'
                            ];
                            $i = 0;
                            foreach ($attendanceData as $branch => $percentage) {
                                echo "<tr>";
                                echo "<td>$branch</td>";
                                echo "<td><span class='color-legend' style='background-color:" . $colors[$i++] . ";'></span></td>";
                                echo "<td class='attendance-percentage'>" . number_format($percentage, 2) . "%</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <script>
                    var ctx = document.getElementById('attendanceChart').getContext('2d');
                    var attendanceData = <?php echo json_encode($attendanceData); ?>;
                    var data = {
                        labels: Object.keys(attendanceData),
                        datasets: [{
                            data: Object.values(attendanceData),
                            backgroundColor: <?php echo json_encode($colors); ?>,
                            borderColor: <?php echo json_encode($colors); ?>,
                            borderWidth: 1
                        }]
                    };

                    var options = {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Current Day Attendance Percentage by Branch'
                            }
                        }
                    };

                    var attendanceChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: options
                    });
                </script>
</body>

</html>