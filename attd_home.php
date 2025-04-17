<?php
include 'sql_conn.php';
session_start();
$class_id = $_SESSION['class_id'];

$sql = "SELECT reg_num, full_name FROM stdreg WHERE class_id = '$class_id'";
$result = $conn->query($sql);
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

        .form-group {
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Attendance for Class <?php echo $class_id; ?></h2>
        <form action="attd_submit.php" method="post">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Registration number</th>
                        <th>Full Name</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['reg_num']}</td>";
                        echo "<td>{$row['full_name']}</td>";
                        echo "<td><input type='checkbox' name='attendance[{$row['reg_num']}]' value='Present'></td>";
                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>

            <div class="form-group">
                <button type="submit" class="btn btn-success" name="submit">Submit Attendance</button>
            </div>
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        </form>

        <div class="form-group">
            <!--form action="attd_update_home.php" method="post">
                <input type="hidden" name="class_id" id="class_id" value='<?php echo $class_id; ?>'>
                <button type="submit" class="btn btn-success" name="submit">Update Attendance</button>
            </form-->
        </div>
    </div>
    <div class="container mt-5">
        <!-- Existing form code -->

        <!-- HTML table to display data from Attendance_Summary table for today's date -->
        <h2>Attendance Summary for Past Period<p> </p>Date:<?php echo date("Y-m-d"); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Total Students</th>
                    <th>Present Students</th>
                    <th>Absent Students</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $current_date = date("Y-m-d");

                // SQL query to retrieve data from Attendance_Summary for today's date and the specific class
                $summary_sql = "SELECT * FROM Attendance_Summary WHERE date = '$current_date' AND class_id = '$class_id'";
                $summary_result = $conn->query($summary_sql);

                if ($summary_result->num_rows > 0) {
                    $summary_row = $summary_result->fetch_assoc();
                    echo "<tr>";
                    echo "<td>{$summary_row['class_id']}</td>";
                    echo "<td>{$summary_row['total_students']}</td>";
                    echo "<td>{$summary_row['present_students']}</td>";
                    echo "<td>{$summary_row['absent_students']}</td>";
                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='4'>No data available for today.</td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

</body>

</html>