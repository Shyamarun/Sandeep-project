<?php
include 'sql_conn.php';
session_start();

// Function to check if faculty_id exists in master_faculty
function checkFaculty($conn, $faculty_id)
{
    $stmt = $conn->prepare("SELECT DISTINCT faculty_id FROM master_faculty WHERE faculty_id = ?");
    $stmt->bind_param("s", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to get current faculty_id for a class_id
function getCurrentFacultyId($conn, $class_id)
{
    $stmt = $conn->prepare("SELECT faculty_id FROM class_incharges WHERE class_id = ?");
    $stmt->bind_param("s", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['faculty_id'];
    } else {
        return "";
    }
}

// Extract prefix if 'HOD' is in user_id
$prefix = '';
if (isset($_SESSION['user_id']) && preg_match('/^(.*?)(HOD)/', $_SESSION['user_id'], $matches)) {
    $prefix = $matches[1]; // Store the string before HOD
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['faculty_id']) && isset($_POST['class_id'])) {
    $user_id = $_SESSION['user_id'];
    $class_id = $_POST['class_id'];
    $faculty_id = $_POST['faculty_id'];

    if (checkFaculty($conn, $faculty_id)) {
        if (isset($_POST['assign'])) {
            $stmt = $conn->prepare("INSERT INTO class_incharges (class_id, faculty_id) VALUES (?, ?)");
            $stmt->bind_param("ss", $class_id, $faculty_id);
        } else if (isset($_POST['update'])) {
            $stmt = $conn->prepare("UPDATE class_incharges SET faculty_id = ? WHERE class_id = ?");
            $stmt->bind_param("ss", $faculty_id, $class_id);
        }

        if ($stmt->execute()) {
            $action = isset($_POST['assign']) ? 'assigned' : 'updated';
            echo "<script>alert('Successfully $action class incharge'); window.location.href='assign_class_incharge.php';</script>";
        } else {
            echo "<script>alert('Error in assigning/updating class incharge'); window.location.href='assign_class_incharge.php';</script>";
        }
    } else {
        echo "<script>alert('Faculty ID not found'); window.location.href='assign_class_incharge.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Class Incharge Management</title>
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
    <div class="container mt-5">
        <div class="table-container">
            <div class="container">
                <h2>Manage Class Incharges</h2>
                <form method="post">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Class ID</th>
                                <th>Faculty ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT DISTINCT class_id FROM stdreg WHERE class_id LIKE '$prefix%' ORDER BY class_id ASC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $currentFacultyId = getCurrentFacultyId($conn, $row["class_id"]);
                                    echo "<tr>";
                                    echo "<form method='post'>"; // Move the form tag inside the loop
                                    echo "<td>" . $row["class_id"] . "</td>";
                                    echo "<td><input type='text' name='faculty_id' value='" . htmlspecialchars($currentFacultyId) . "' required></td>";
                                    echo "<input type='hidden' name='class_id' value='" . $row["class_id"] . "'>";
                                    echo "<td><button type='submit' name='assign' class='btn btn-primary'>Assign</button> <button type='submit' name='update' class='btn btn-warning'>Change</button></td>";
                                    echo "</form>"; // Close the form tag inside the loop
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No classes found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <!-- Bootstrap JS and dependencies -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.6.2/umd.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
$conn->close();
?>