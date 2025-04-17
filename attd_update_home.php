<?php
include 'sql_conn.php';
session_start();
$class_id = $_SESSION['class_id'];
$table_name = "Attendance_" . $class_id;
$current_date = date("Y-m-d");
$sql = "SELECT reg_num,attendance_status,date FROM $table_name WHERE class_id = '$class_id' AND attendance_status='Absent' AND date='$current_date'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Attendance System</title>
</head>

<body>

    <div class="container mt-5">
        <h2>Attendance for Class <?php echo $class_id; ?></h2>
        <form action="attd_update.php" method="post">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Registration number</th>
                        <th>Today's Attendance</th>
                        <th>Date</th>
                        <th>Updated Attendance</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if ($conn->query($sql) === FALSE) {
                        echo "<tr>";
                        echo "<td>No data to update</td>";
                        echo "</tr>";
                    }else{
                        while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['reg_num']}</td>";
                        echo "<td>{$row['attendance_status']}</td>";
                        echo "<td>{$row['date']}</td>";
                        echo "<td><input type='checkbox' name='attendance[{$row['reg_num']}]' value='Present'></td>";
                        echo "</tr>";
                    }
                    }
                    ?>

                </tbody>
            </table>

            <div class="form-group">
                <button type="submit" class="btn btn-success" name="submit">Submit Attendance</button>
            </div>
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        </form>
    </div>

</body>

</html>