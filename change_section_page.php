<?php
include 'sql_conn.php'; // Include your SQL connection file
session_start();
$class_id = $_SESSION['class_id'] ?? ''; // Retrieve class_id

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
        <title>Change Section</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .certificate {
                border: 1px solid #000;
                padding: 20px;
                margin-top: 20px;
            }

            .certificate-header img {
                width: 100%;
                height: auto;
                margin-bottom: 20px;
            }

            .certificate-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 20px;
            }

            .certificate-footer img {
                width: 100px;
                height: auto;
            }

            .certificate-body img {
                width: 100px;
                height: auto;
                float: right;
            }

            .certificate-content {
                clear: both;
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

            .certificate-container {
                /* Optional border */
                page-break-after: always;
                /* This ensures each certificate starts on a new page when printed */
            }

            @media print {
                .certificate-container {
                    page-break-after: always;
                }
            }

            #table-container {
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
            }

            #table-container1 {
                background: rgba(255, 255, 255);
                /* Semi-transparent white background */
                border-radius: 10px;
                /* Rounded corners for the table container */
                padding: 20px;
                /* Padding around the table */
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
                /* Enhanced shadow for a deeper frame effect */
                margin-top: 20px;
                /* Margin to distance from top */
            }

            body {
                font-family: Arial, sans-serif;
                background-image: url("tt.jpg");
                /* Update with the actual path */
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }

            /* Add further styling as needed */
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Change Section</h2>
            <form action="change_section.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <table class="table" id="table-container">
                    <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>Semester</th>
                            <th>Year</th>
                            <th>Current Section</th>
                            <th>New Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['reg_num']); ?></td>
                                <td><?php echo htmlspecialchars($row['semester']); ?></td>
                                <td><?php echo htmlspecialchars($row['sem_year']); ?></td>
                                <td><?php echo htmlspecialchars($row['section']); ?></td>
                                <td>
                                    <select name="new_sections[<?php echo $row['reg_num']; ?>]" class="form-control">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Submit Changes</button>
            </form>
        </div>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php
    // End HTML

    $stmt->close();
}
$conn->close();
?>