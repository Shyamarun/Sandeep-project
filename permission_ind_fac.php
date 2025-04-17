<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Permission Request</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('st.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .chart-container {
            width: 80%;
            margin: auto;
            margin-bottom: 20px;
        }

        .attendance-table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .attendance-table th,
        .attendance-table td {
            padding: 2px;
            text-align: center;
        }

        .attendance-table th {
            color: #333;
        }

        .color-legend {
            display: inline-block;
            width: 40px;
            height: 15px;
            border: 1px solid #000;
        }

        .attendance-percentage {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .chart-container {
                width: 100%;
                margin-bottom: 20px;
            }

            .attendance-table,
            .attendance-table th,
            .attendance-table td {
                padding: 1px;
            }

            .color-legend {
                width: 30px;
            }
        }

        /* Styles from the second code */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            border: 2px solid #007bff;
        }

        table {
            background: transparent;
        }

        th,
        td {
            color: #333;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container">
        <div class="table-container">
            <h2 class="mt-5">
                <center>Permission Request Form</center>
            </h2>
            <form action="per_ind_process.php" method="POST">
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="body">Body</label>
                    <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Select Recipient</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="designation" id="PRI" value="PRI" required>
                        <label class="form-check-label" for="principal">Principal</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="designation" id="DIR" value="DIR" required>
                        <label class="form-check-label" for="director">Director</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="designation" id="VPRI" value="VPRI" required>
                        <label class="form-check-label" for="vice_principal">Vice Principal</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="designation" id="AO" value="AO" required>
                        <label class="form-check-label" for="AO">AO</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>

            <!-- Display Existing Records -->
            <center>
                <h3 class="mt-5">Existing Permission Requests</h3>
                <center>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <center>Subject
                                </th>
                                <th>
                                    <center>Body
                                </th>
                                <th>
                                    <center>Stage
                                </th>
                                <th>
                                    <center>Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'sql_conn.php'; // File to connect to the database
                            $faculty_id = $_SESSION['faculty_id'];
                            $query = "SELECT subject, body, stage, status FROM permission_teacher WHERE faculty_id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $faculty_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                            <td><center>" . htmlspecialchars($row['subject']) . "</td>
                            <td><center>" . htmlspecialchars($row['body']) . "</td>
                            <td><center>" . htmlspecialchars($row['stage']) . "</td>
                            <td><center>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'><center>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
        </div>
        </div>
</body>

</html>