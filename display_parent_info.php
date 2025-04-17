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
                session_start();
                $class_id = $_SESSION['class_id'];
                // Connect to your database
                include 'sql_conn.php';

                // Check if the form is submitted
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Retrieve search input
                    $reg_num = $_POST['reg_num'];

                    // Modify the SQL query to include the reg_num condition
                    $sql = "SELECT * FROM stdreg WHERE class_id='$class_id' AND reg_num='$reg_num'";
                    $result = $conn->query($sql);
                } else {
                    // Retrieve certificates data from student_certificates table
                    $sql = "SELECT * FROM stdreg WHERE class_id='$class_id'";
                    $result = $conn->query($sql);
                }

                echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <!-- Include Bootstrap CSS from CDN -->
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <!-- Add your custom CSS styles -->
            <style>
                body {
                    /* Add your custom styles here */
                    background-color: #f8f9fa;
                }

                .container {
                    /* Add your container styles here */
                    margin-top: 50px;
                }

                /* Add more custom styles as needed */
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='text-center'>Parent Contact Info</h2>";

                // Add the search form
                echo "
                <form class='mb-3' method='post'>
                    <div class='input-group'>
                        <input type='text' class='form-control' placeholder='Enter Roll Number' name='reg_num'>
                        <button class='btn btn-primary' type='submit'>Search</button>
                    </div>
                </form>";

                if ($result->num_rows > 0) {
                    echo "<table class='table table-bordered table-striped'>
                <thead class='thead-dark'>
                    <tr>
                        <th>Roll Number</th>
                        <th>Parent Name</th>
                        <th>Parent Number</th>
                    </tr>
                </thead>
                <tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                    <td>{$row['reg_num']}</td>
                    <td>{$row['parent_name']}</td>
                    <td>{$row['parent_phone_num']}</td>
                </tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<p class='alert alert-warning'>No records found for the student.</p>";
                }

                echo "
            </div>
            <!-- Include Bootstrap JS from CDN -->
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
        </body>
        </html>";

                $conn->close();
                ?>