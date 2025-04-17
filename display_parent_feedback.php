<?php
session_start();
$class_id = $_SESSION['class_id']; ?>
<!DOCTYPE html>
<html>

<head>
    <title>View Parent Feedback</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="container mt-5">
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>Registration Number</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'sql_conn.php';

                            $class_id = $_POST['class_id']; // or use another method to set $class_id

                            // SQL to fetch data
                            $sql = "SELECT reg_num, feedback FROM parent_feedback WHERE class_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $class_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . htmlspecialchars($row['reg_num']) . "</td><td>" . htmlspecialchars($row['feedback']) . "</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No feedback available</td></tr>";
                            }

                            $stmt->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Bootstrap JS and Popper.js -->
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>