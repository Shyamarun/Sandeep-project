<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Certificates for Roll Number: $reg_num</title>
    <!-- Include Bootstrap CSS from CDN -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <!-- Add your custom CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('certificates.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            /* Add your container styles here */
            margin-top: 50px;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 10px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
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
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            /* More opaque blue for header background */
            color: white;
            /* White text color for headers */
        }

        /* Add more custom styles as needed */
    </style>
</head>

<body>
    <div class='container'>
        <?php
        session_start();
        $reg_num = $_SESSION['reg_num']; ?>
        <div class="table-container">
            <table class='table table-bordered table-striped'>
                <thead class='thead-dark'>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reg_num = $_SESSION['reg_num'];
                    // Connect to your database
                    include 'sql_conn.php';
                    // Retrieve certificates data from student_certificates table
                    $sql = "SELECT * FROM certificates WHERE reg_num = '$reg_num'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                        <td>{$row['certificate_name']}</td>
                        <td>{$row['certificate_description']}</td>
                        <td><img src='{$row['file_path']}' class='img-fluid' style='max-width: 200px; max-height: 200px;'></td>
                        </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
                    } else {
                        echo "<script>alert('No certificates found for the student.');window.location.href='student.php';</script>";
                    }
        ?>
        </div>
        <!-- Include Bootstrap JS from CDN -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>
<?php
$conn->close();
?>