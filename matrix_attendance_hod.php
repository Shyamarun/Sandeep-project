<?php
session_start();
include 'sql_conn.php'; // Include your SQL connection file
$attendanceData = []; // Initialize as an empty array to avoid warnings

// Function to check if user_id meets the specific conditions
function isValidUserId($user_id, &$prefix = null)
{
    // Check for 'HOD' in the user_id
    if (preg_match('/^(.*?)(HOD)/i', $user_id, $matches)) {
        $prefix = $matches[1]; // Store the string before HOD
        return true;
    }
    return false;
}


// Function to calculate attendance percentage
function getAttendanceData($conn, $prefix)
{
    $attendanceData = [];

    // SQL to get distinct branches from class_id in the attendance_summary table
    $branchSql = "SELECT DISTINCT SUBSTRING(class_id, LENGTH('$prefix') + 1, 3) AS branch FROM attendance_summary";
    $branchResult = mysqli_query($conn, $branchSql);

    while ($branchRow = mysqli_fetch_assoc($branchResult)) {
        $branch = $branchRow['branch'];

        // SQL to get attendance data for each branch
        $sql = "SELECT SUM(present_students) as total_present, SUM(total_students) as total_students 
                FROM attendance_summary 
                WHERE class_id LIKE '$prefix$branch%' AND date = CURDATE();";

        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $attendancePercentage = 0;
            if ($row['total_students'] > 0) {
                $attendancePercentage = ($row['total_present'] / $row['total_students']) * 100;
            }
            $attendanceData[$branch] = $attendancePercentage;
        }
    }

    return $attendanceData;
}

function insertAttendanceData($conn, $attendanceData)
{
    $date = date('Y-m-d'); // Use the current date or specify the date you're recording.

    foreach ($attendanceData as $branch => $percentage) {
        // Prepare an INSERT statement to avoid SQL injection.
        $stmt = $conn->prepare("INSERT INTO daily_attendance (branch, attendance_percentage, date_recorded) VALUES (?, ?, ?)");

        // Bind parameters to the prepared statement as strings.
        $stmt->bind_param("sds", $branch, $percentage, $date);

        // Execute the prepared statement.
        $stmt->execute();

        // Close the prepared statement.
        $stmt->close();
    }
}

$prefix = ''; // Initialize prefix variable
// Check if user_id is set and valid
if (isset($_SESSION['user_id']) && isValidUserId($_SESSION['user_id'], $prefix)) {
    $attendanceData = getAttendanceData($conn, $prefix);
    arsort($attendanceData);
} else {
    echo "<script>alert('Invalid User ID');window.location.href='auth_matrix_home_hod.php';</script>";
    exit;
}

// HTML and JavaScript will follow...
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