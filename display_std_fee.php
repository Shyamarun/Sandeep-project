<?php
session_start();
$class_id = $_SESSION['class_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <table class="table mt-3">
                    <tr>
                        <th>Roll number</th>
                        <th>Tution Fee</th>
                        <th>Building Fund</th>
                        <th>CRT Fee</th>
                        <th>Bus Fee</th>
                        <th>Hostel Fee</th>
                        <th>Total Fee</th>
                        <th>Amount Paid</th>
                        <th>Due Amount</th>
                    </tr>
                    <?php
                    include 'sql_conn.php';

                    $sql = "SELECT * FROM fees WHERE class_id= ? ORDER BY reg_num ASC";

                    $stmt = $conn->prepare($sql);

                    // Check for errors in prepare
                    if (!$stmt) {
                        die("Error in prepare: " . $conn->error);
                    }

                    $stmt->bind_param("s", $class_id);

                    // Execute the query
                    $stmt->execute();

                    // Check for errors in execution
                    if ($stmt->errno) {
                        die("Error in execute: " . $stmt->error);
                    }

                    // Get the result set
                    $result = $stmt->get_result();

                    // Fetch and process the result set
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['reg_num'] . "</td>";
                            echo "<td>" . $row['tution_fee'] . "</td>";
                            echo "<td>" . $row['building_fund'] . "</td>";
                            echo "<td>" . $row['crt_fee'] . "</td>";
                            echo "<td>" . $row['bus_fee'] . "</td>";
                            echo "<td>" . $row['hostel_fee'] . "</td>";
                            echo "<td>" . $row['total_fee'] . "</td>";
                            echo "<td>" . $row['amount_paid'] . "</td>";
                            echo "<td>" . $row['due_amount'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No data available</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>