<?php
include 'sql_conn.php'; // This file should establish a connection to your database.
session_start();

// Ensure these session variables are correctly assigned before this script runs.
$class_id = $_SESSION['class_id'];
$reg_num = $_SESSION['reg_num'];
$table_name = "attendance_" . strtoupper($class_id); // Construct the table name with the class ID in uppercase.

$current_year = date("Y");
$current_month = date("m");

// Function to get a list of dates for which attendance has been marked in a specific month and year
function getAttendanceDates($conn, $table_name, $year, $month)
{
    $monthPattern = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '%';
    $query = "SELECT DISTINCT `date` FROM `$table_name` WHERE `date` LIKE '$monthPattern' ORDER BY `date` ASC";
    $result = $conn->query($query);
    $dates = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row['date'];
        }
    }
    return $dates;
}

// Function to display monthly attendance percentage
function displayMonthlyAttendance($conn, $table_name, $reg_num, $year, $month)
{
    $dates = getAttendanceDates($conn, $table_name, $year, $month);
    if (empty($dates)) {
        return; // If no attendance records found for the month, exit the function
    }
    $totalDays = count($dates);
    $totalPresentDays = 0;

    foreach ($dates as $date) {
        $sql = "SELECT `attendance_status` FROM `$table_name` WHERE `reg_num` = '$reg_num' AND `date` = '$date'";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            if ($row['attendance_status'] == 'Present') {
                $totalPresentDays++;
            }
        }
    }
    $attendancePercentage = ($totalPresentDays / $totalDays) * 100;

    echo "<tr><center>";
    echo "<td><center>{$reg_num}</center></td>";
    echo "<td><center>" . date("F", mktime(0, 0, 0, $month, 10, $year)) . "</center></td>";
    echo "<td><center>" . number_format($attendancePercentage, 2) . "%</center></td>";
    echo "</center></tr>";
}

// Function to display daily attendance
function displayDailyAttendance($conn, $table_name, $reg_num, $year, $month)
{
    $dates = getAttendanceDates($conn, $table_name, $year, $month);
    foreach ($dates as $date) {
        $sql = "SELECT `attendance_status` FROM `$table_name` WHERE `reg_num` = '$reg_num' AND `date` = '$date'";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $attendanceStatus = $row['attendance_status'] ?? 'Not Marked';
            echo "<tr><center>";
            echo "<td><center>{$reg_num}</center></td>";
            echo "<td><center>{$date}</center></td>"; // Use the date from the database
            echo "<td><center>{$attendanceStatus}</center></td>";
            echo "</center></tr>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Attendance System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('st.jpg');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .table-container {
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
            border: 2px solid #007bff;
            /* Solid border for a distinct frame */
        }

        table {
            background: transparent;
            /* Transparent background for the table */
        }

        th,
        td {
            color: #333;
            /* Dark text for readability */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            /* Bootstrap's default border color */
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            /* More opaque blue for header background */
            color: white;
            /* White text color for headers */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <h2> </h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><center>Registration Number</center></th>
                        <th><center>Date</center></th>
                        <th><center>Attendance Status</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display monthly attendance percentage for each month up to current
                    for ($month = 1; $month <= $current_month; $month++) {
                        displayMonthlyAttendance($conn, $table_name, $reg_num, $current_year, $month);
                        displayDailyAttendance($conn, $table_name, $reg_num, $current_year, $month); // Display daily attendance
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>