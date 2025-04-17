<?php
include "sql_conn.php";
session_start();
$reg_num = $_SESSION['reg_num'];
// Fetch all records from club_responses
$sql = "SELECT projectCode, responder_reg_num, full_name, phone_num, description, status_of_request FROM club_responses WHERE reg_num='$reg_num'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Responses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('clv.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .table table-bordered {
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

        #head {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }

        .table th {
            /* Decrease the max-width to a suitable value */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .navbar.fixed-bottom {
            border-radius: 15px;
            /* Curved edges for the navbar */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            margin: 10px 15px;
            /* Adjust margin to ensure the navbar does not stretch fully across */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for depth */
            overflow: hidden;
            /* Ensures content fits within the border radius */
        }
    </style>
</head>

<body>

    <div class="container mt-5">

        <div id='head'>
            <center>
                <h2>Club Responses</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <center>Project ID
                            </th>
                            <th>
                                <center>Responder Registration Number
                            </th>
                            <th>
                                <center>Full Name
                            </th>
                            <th>
                                <center>Phone Number
                            </th>
                            <th>
                                <center>Description
                            </th>
                            <th>
                                <center>Status Of Request
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><center>{$row['projectCode']}</td>";
                                echo "<td><center>{$row['responder_reg_num']}</td>";
                                echo "<td><center>{$row['full_name']}</td>";
                                echo "<td><center>{$row['phone_num']}</td>";
                                echo "<td><center>{$row['description']}</td>";

                                // Check if status_of_request is NULL
                                if ($row['status_of_request'] === null) {
                                    echo '<td>';
                                    echo '<button class="btn btn-success btn-sm" onclick="acceptRequest(\'' . htmlspecialchars($row['projectCode']) . '\', \'' . htmlspecialchars($row['responder_reg_num']) . '\')">Accept</button>';
                                    echo "<div class='mt-3'></div>";
                                    echo '<button class="btn btn-danger btn-sm" onclick="declineRequest(\'' . htmlspecialchars($row['projectCode']) . '\', \'' . htmlspecialchars($row['responder_reg_num']) . '\')">Decline</button>';
                                    echo '</td>';
                                } else {
                                    echo "<td><center>{$row['status_of_request']}</td>";
                                }

                                echo "</tr>";
                            }
                        } else {
                            echo "<script>alert('No records found');window.location.href='club_home.php';</script>";
                        }
                        ?>
                    </tbody>
                </table>
        </div>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
        <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
        <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
        <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
        <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
    </nav>

    <script>
        function acceptRequest(projectCode, reg_num) {
            // Implement AJAX call to update status_of_request to 'Accept'
            $.post("club_response_status.php", {
                projectCode: projectCode,
                reg_num: reg_num,
                status: 'Accept'
            }, function(data) {
                // Handle success or error if needed
                console.log(data);
                // Reload the page after the request is processed
                location.reload();
            });
        }

        function declineRequest(projectCode, reg_num) {
            // Implement AJAX call to update status_of_request to 'Rejected'
            $.post("club_response_status.php", {
                projectCode: projectCode,
                reg_num: reg_num,
                status: 'Rejected'
            }, function(data) {
                // Handle success or error if needed
                console.log(data);
                // Reload the page after the request is processed
                location.reload();
            });
        }
    </script>


</body>

</html>

<?php
$conn->close();
?>