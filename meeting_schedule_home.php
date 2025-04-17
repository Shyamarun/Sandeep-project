<?php
include 'sql_conn.php';
session_start();
$class_id = $_SESSION['class_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Form and Data</title>
    <!-- Add Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            <div class="container mt-5">
                <form action="meeting_schedule.php" method="post">
                    <div class="form-group">
                        <label for="meetingLink">Meeting Link:</label>
                        <input type="text" class="form-control" id="meetingLink" name="meetingLink" required>
                    </div>
                    <div class="form-group">
                        <label for="meetingDate">Meeting Date:</label>
                        <input type="date" class="form-control" id="meetingDate" name="meetingDate" required>
                    </div>
                    <div class="form-group">
                        <label for="meetingTime">Meeting Time:</label>
                        <input type="time" class="form-control" id="meetingTime" name="meetingTime" required>
                    </div>
                    <!-- Hidden Input for class_id -->
                    <div class="form-group">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <div>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Meeting Link</th>
                                <th>Meeting Date</th>
                                <th>Meeting Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM meetings WHERE class_id = '$class_id'";
                            $result = $conn->query($sql);

                            echo "<h2>Meeting Data for Class ID: $class_id</h2>";

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['meeting_link']}</td>
                                <td>{$row['meeting_date']}</td>
                                <td>{$row['meeting_time']}</td>
                            </tr>";
                                }

                                echo "</tbody></table>";
                            } else {
                                echo "No meetings found for this class.";
                            }
                            ?>
                </div>
                <!-- Display meeting data here (will be shown by default) -->
                <?php
                $conn->close();
                ?>
            </div>

            <!-- Add Bootstrap JS and Popper.js Scripts -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>