<?php
session_start();
$reg_num = $_GET['reg_num'];
$class_id = $_SESSION['class_id'];

if (isset($_GET['reg_num'])) {
    // Connect to your database
    include 'sql_conn.php';
    // Retrieve certificates data from student_certificates table
    $sql = "SELECT * FROM certificates WHERE reg_num = '$reg_num' AND class_id='$class_id'";
    $result = $conn->query($sql);

    echo "
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
                <h2 class='text-center'>Certificates for Roll Number: $reg_num</h2>";

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-striped'>
                <thead class='thead-dark'>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Certificate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['certificate_name']}</td>
                    <td>{$row['certificate_description']}</td>
                    <td><img src='{$row['file_path']}' class='img-fluid' style='max-width: 200px; max-height: 200px;'></td>
                    <td><a class='btn btn-danger btn-sm' href='delete_certificate.php?id={$row['id']}'>Delete</a></td>
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='alert alert-warning'>No certificates found for the student.</p>";
    }

    echo "
            </div>
            <!-- Include Bootstrap JS from CDN -->
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
        </body>
        </html>";

    $conn->close();
}
