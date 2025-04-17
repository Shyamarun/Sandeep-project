<?php
include 'sql_conn.php'; // Include your SQL connection file
session_start();
$class_id = $_SESSION['class_id'] ?? ''; // Replace with actual method to get class_id

// Fetch data from database
$sql = "SELECT reg_num, semester, sem_year, section FROM stdreg WHERE class_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Start HTML
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student Registrations</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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

                @media (max-width: 768px) {
                    .table-responsive-sm {
                        display: block;
                        width: 100%;
                        overflow-x: auto;
                        -webkit-overflow-screg_numing: touch;
                    }

                    .table-responsive-sm table {
                        width: 100%;
                    }

                    .certificate-content {
                        font-size: 0.8em;
                        /* Adjust font size for smaller screens */
                    }
                }
            </style>
        </head>

    <body>
        <div class="container mt-5">
            <div class="table-container">
                <div class="container">
                    <h2>Student Registrations</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Registration Number</th>
                                <th>Semester</th>
                                <th>Year</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['reg_num']); ?></td>
                                    <td><?php echo htmlspecialchars($row['semester']); ?></td>
                                    <td><?php echo htmlspecialchars($row['sem_year']); ?></td>
                                    <td><?php echo htmlspecialchars($row['section']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <form action="promote_all.php" method="post">
                        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                        <button type="submit" class="btn btn-primary">Promote All</button>
                    </form>
                    <form action="change_section_page.php" method="post">
                        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                        <button type="submit" class="btn btn-secondary">Change Section</button>
                    </form>
                </div>

                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php
    $stmt->close();
}
$conn->close();
?>