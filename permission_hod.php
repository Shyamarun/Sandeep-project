<?php
session_start();
include 'sql_conn.php'; // Ensure this file sets up a MySQLi connection as $conn

$user_id = $_SESSION['user_id'] ?? '';

// Fetch records from permission_student table where recipient = $user_id and status="Waiting"
$query = "SELECT id,reg_num,full_name,semester,sem_year,subject, body, status FROM permission_student WHERE recipient = ? AND status = 'Waiting' ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Permission Request</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
        <div class="table-container">
            <div class="container">
                <h3 class="mt-5 text-center">Existing Permission Requests</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>Full Name</th>
                            <th>Semester Year</th>
                            <th>Semester</th>
                            <th>Subject</th>
                            <th>Body</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['reg_num']) ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['sem_year']) ?></td>
                                <td><?= htmlspecialchars($row['semester']) ?></td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><?= htmlspecialchars($row['body']) ?></td>
                                <td>
                                    <button onclick="updateStatus('Accepted', '<?= $row['reg_num'] ?>','<?= $row['id'] ?>','Requested Accepted by HOD')" class="btn btn-success">Accept</button>
                                    <button onclick="updateStatus('Rejected', '<?= $row['reg_num'] ?>','<?= $row['id'] ?>','Requested Rejected by HOD')" class="btn btn-danger">Reject</button>
                                    <button onclick="showOptions('<?= $row['reg_num'] ?>', '<?= $row['id'] ?>', this)" class="btn btn-primary">Forward</button>

                                    <!-- Inline Options for Forwarding -->
                                    <div id="forwardOptions-<?= $row['id'] ?>" style="display:none;">
                                        <form>
                                            <input type="radio" id="principal-<?= $row['id'] ?>" name="forwardTo-<?= $row['id'] ?>" value="PRI">
                                            <label for="principal-<?= $row['id'] ?>">Principal</label><br>
                                            <input type="radio" id="director-<?= $row['id'] ?>" name="forwardTo-<?= $row['id'] ?>" value="DIR">
                                            <label for="director-<?= $row['id'] ?>">Director</label><br>
                                            <input type="radio" id="vicePrincipal-<?= $row['id'] ?>" name="forwardTo-<?= $row['id'] ?>" value="VPRI">
                                            <label for="vicePrincipal-<?= $row['id'] ?>">Vice Principal</label><br>
                                            <input type="radio" id="ao-<?= $row['id'] ?>" name="forwardTo-<?= $row['id'] ?>" value="AO">
                                            <label for="ao-<?= $row['id'] ?>">AO</label><br>
                                            <button type="button" onclick="forwardRequestWithSelection('<?= $row['reg_num'] ?>', '<?= $row['id'] ?>')" class="btn btn-primary">Forward with Selection</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <script>
                function showOptions(reg_num, id, button) {
                    // Toggle visibility of the forward options
                    var optionsDiv = document.getElementById('forwardOptions-' + id);
                    optionsDiv.style.display = optionsDiv.style.display === 'none' ? 'block' : 'none';
                }

                function forwardRequestWithSelection(reg_num, id) {
                    // Get the selected option
                    var selectedOption = document.querySelector('input[name="forwardTo-' + id + '"]:checked').value;
                    // Call the original forwardRequest function with the selected option
                    forwardRequest(reg_num, id, selectedOption);
                }

                function updateStatus(newStatus, reg_num, id, newStage) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "update_status.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (this.status == 200) {
                            alert("Permission request for " + reg_num + " successfully " + newStatus + ".");
                            window.location.href = 'permission_hod.php'; // Refresh the page
                        } else {
                            alert("Error: " + this.responseText);
                        }
                    };
                    xhr.send("reg_num=" + reg_num + "&status=" + newStatus + "&stage=" + newStage + "&id=" + id);
                }

                function forwardRequest(reg_num, id, forwardTo) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "forward_request_hod.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (this.status == 200) {
                            // The success message might need to be updated to reflect the actual role the request was forwarded to
                            alert("Permission request for " + reg_num + " successfully forwarded to " + forwardTo + ".");
                            window.location.href = 'permission_hod.php'; // Refresh the page
                        } else {
                            alert("Error: " + this.responseText);
                        }
                    };
                    // Include the new parameter in the data sent to the server
                    xhr.send("reg_num=" + reg_num + "&id=" + id + "&forwardTo=" + forwardTo);
                }
            </script>



</body>

</html>