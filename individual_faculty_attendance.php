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
                <?php
                include 'sql_conn.php';
                // Retrieve $faculty_id from POST method
                session_start();
                $faculty_id = $_SESSION['faculty_id'];

                // SQL query to fetch required data
                $sql = "SELECT class_id, day, period, subject FROM timetable WHERE faculty_id = ? ORDER BY day, period";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $faculty_id); // 's' for string type
                $stmt->execute();
                $result = $stmt->get_result();

                // Include Bootstrap CSS
                echo '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">';

                // Start the table
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr><th>Class ID</th><th>Day</th><th>Period</th><th>Subject</th><th>Action</th></tr>';
                echo '</thead>';
                echo '<tbody>';

                // Fetch and display each row
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['class_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['day']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['period']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['subject']) . '</td>';
                    echo '<td>
        <form action="attd_home.php" method="post">
            <input type="hidden" name="class_id" value="' . htmlspecialchars($row['class_id']) . '">
            <button type="submit" class="btn btn-primary">Mark Attendance</button>
        </form>
    </td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';

                // Close connection
                $conn->close();
                ?>