<?php
include 'sql_conn.php';

$class_id = 'AVEVBTECHCSE11A';
$reg_num = '20Q71A4203';
$table_name = "Attendance_" . $class_id;
$current_date = date("Y-m-d");

// Fetch attendance records
$sql = "SELECT * FROM $table_name WHERE class_id = '$class_id' AND reg_num='$reg_num' AND date <= LAST_DAY('$current_date')";
$result = $conn->query($sql);

// Display daily attendance until the month end
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
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            color: #007bff;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
        }

        td {
            text-align: center;
        }

        .mt-3 {
            margin-top: 20px;
        }

        .percentage {
            color: #28a745;
        }
    </style>
</head>

<body>

    <div class="container">
        <center><h2 class="mb-4">Attendance for Roll Number: <?php echo $reg_num; ?></h2></center>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><center>Date</center></th>
                    <th><center>Attendance</center></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['date']}</td>";
                    echo "<td>{$row['attendance_status']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Calculate and insert attendance percentage at the end of the month
        $lastDayOfMonth = date("Y-m-t", strtotime($current_date));
        $sql = "SELECT COUNT(*) AS total, SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) AS present FROM $table_name WHERE class_id = '$class_id' AND reg_num='$reg_num' AND date <= '$lastDayOfMonth'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $attendancePercentage = ($row['present'] / $row['total']) * 100;

        // Insert attendance percentage into the new table
        $monthlyTable = "Attendance_Monthly";
        $insertSql = "INSERT INTO $monthlyTable (reg_num, class_id, month_year, attendance_percentage) VALUES ('$reg_num', '$class_id', '" . date("Y-m", strtotime($current_date)) . "', '$attendancePercentage')";
        $conn->query($insertSql);
        ?>

        <div class="mt-3">
            <h4 class="mb-3">Attendance Percentage for <?php echo date("F Y", strtotime($current_date)); ?></h4>
            <p class="percentage">Attendance Percentage: <?php echo number_format($attendancePercentage, 2) . '%'; ?></p>
        </div>
    </div>

</body>

</html>
