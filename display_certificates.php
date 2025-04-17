<?php
session_start();
$class_id = $_SESSION["class_id"]; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Student Certificates</title>
    <!-- Include Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

<body class="bg-light">
    <div class="container mt-5">
        <div class="table-container">
            <div class="container mt-5">
                <h2 class="text-center">Student Details</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Certificates</th>
                                <th>Add Certificate</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            // Connect to your database (replace database connection details)
                            include 'sql_conn.php';

                            // Retrieve student data from stdreg table
                            $sql = "SELECT reg_num, full_name FROM stdreg WHERE class_id = '$class_id'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {

                                while ($row = $result->fetch_assoc()) {
                                    $reg_num = $row['reg_num'];
                            ?>
                                    <tr>
                                        <td><?= $row['reg_num'] ?></td>
                                        <td><?= $row['full_name'] ?></td>
                                        <td><a class="btn btn-info btn-sm" href="view_certificates.php?reg_num=<?= $reg_num ?>&class_id=<?= $class_id ?>">View Certificates</a></td>
                                        <td><a class="btn btn-success btn-sm" href="add_certificate.php?reg_num=<?= $reg_num ?>&class_id=<?= $class_id ?>">Add Certificate</a></td>
                                    </tr>
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            <?php
                            } else {
            ?>
                <div class="container mt-5">
                    <p class="alert alert-warning">No students found for the given class ID.</p>
                </div>
            <?php
                            }
                            $conn->close();

            ?>

</body>

</html>