<?php
session_start();
include 'sql_conn.php'; // Ensure this file sets up a MySQLi connection as $conn

// Assume $user_id is already set from $_SESSION or another source
$user_id = $_SESSION['user_id'] ?? '';
$suffix = '';

// List of possible suffixes or roles
$possibleSuffixes = ['DIR', 'PRI', 'VPRI', 'AO'];

foreach ($possibleSuffixes as $possibleSuffix) {
    if (strpos($user_id, $possibleSuffix) !== false) {
        $suffix = $possibleSuffix;
        break; // Stop the loop once a match is found
    }
}
// Fetch records from permission_student table where recipient = $class_id and status="Waiting"
$query = "SELECT id,faculty_id,facultyName,contactNumber,subject, body, status FROM permission_teacher WHERE recipient = ? AND status = 'Waiting' ORDER BY created_at ASC";
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
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <h3>Existing Permission Requests</h3>
                    <button onclick="window.location.href='permission_pri_dir.php';" class="btn btn-primary">Student's Requests</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Faculty ID</th>
                            <th>Full Name</th>
                            <th>Contact number</th>
                            <th>Subject</th>
                            <th>Body</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['faculty_id']) ?></td>
                                <td><?= htmlspecialchars($row['facultyName']) ?></td>
                                <td><?= htmlspecialchars($row['contactNumber']) ?></td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><?= htmlspecialchars($row['body']) ?></td>
                                <td>
                                    <button onclick="updateStatus('Accepted', '<?= $row['faculty_id'] ?>','<?= $row['id'] ?>' ,'Requested Accepted by ','<?= $suffix ?>')" class="btn btn-success">Accept</button>
                                    <button onclick="updateStatus('Rejected', '<?= $row['faculty_id'] ?>','<?= $row['id'] ?>','Requested Rejected by ','<?= $suffix ?>')" class="btn btn-danger">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

            <script>
                function updateStatus(newStatus, faculty_id, id, newStage, suffix) {
                    var xhr = new XMLHttpRequest();
                    if (suffix == 'DIR') {
                        suffix = 'Director';
                    } else if (suffix == 'PRI') {
                        suffix = 'Principal';
                    } else if (suffix == 'VPRI') {
                        suffix = 'Vice Principal';
                    } else if (suffix == 'AO') {
                        suffix = 'AO';
                    }
                    xhr.open("POST", "update_pri_dir_fac.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (this.status == 200) {
                            alert("Permission request for " + faculty_id + " successfully " + newStatus + ".");
                            window.location.href = 'permission_pri_dir.php'; // Refresh the page
                        } else {
                            alert("Error: " + this.responseText);
                        }
                    };
                    xhr.send("faculty_id=" + faculty_id + "&status=" + newStatus + "&stage=" + (newStage + suffix) + "&id=" + id);
                }
            </script>
</body>

</html>