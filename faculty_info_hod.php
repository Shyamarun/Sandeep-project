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
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <?php
            // Include the SQL connection file
            include 'sql_conn.php';
            session_start();
            // Retrieve $user_id from POST method
            $user_id = $_SESSION['user_id'];

            // Check if 'HOD' exists in the $user_id and extract the prefix
            $prefix = '';
            if (strpos($user_id, 'HOD') !== false) {
                $prefix = explode('HOD', $user_id)[0];
            }

            // Prepare SQL query to get distinct subjects and faculty_id from timetable
            $query = "SELECT DISTINCT subject, faculty_id FROM timetable WHERE class_id LIKE ? ORDER BY subject ASC";
            $stmt = $conn->prepare($query);
            $likePrefix = $prefix . '%';
            $stmt->bind_param("s", $likePrefix);
            $stmt->execute();
            $result = $stmt->get_result();

            // Prepare data array
            $facultyData = [];

            while ($row = $result->fetch_assoc()) {
                $faculty_id = $row['faculty_id'];

                // Get faculty name and contact number from master_faculty table
                $queryFaculty = "SELECT facultyName, contactNumber FROM master_faculty WHERE faculty_id = ?";
                $stmtFaculty = $conn->prepare($queryFaculty);
                $stmtFaculty->bind_param("s", $faculty_id);
                $stmtFaculty->execute();
                $resultFaculty = $stmtFaculty->get_result();

                if ($facultyRow = $resultFaculty->fetch_assoc()) {
                    $facultyData[] = [
                        'subject' => $row['subject'],
                        'facultyName' => $facultyRow['facultyName'],
                        'contactNumber' => $facultyRow['contactNumber']
                    ];
                }
            }

            // Check if facultyData is empty
            if (empty($facultyData)) {
                // Display an alert and redirect
                echo "<script>alert('No faculty data found!');window.location.href='auth_matrix_home_hod.php';</script>";
            } else {
                // Include Bootstrap CSS
                echo '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">';

                // Start the table
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr><th>Subject</th><th>Faculty Name</th><th>Contact Number</th></tr>';
                echo '</thead>';
                echo '<tbody>';

                // Display each row
                foreach ($facultyData as $data) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($data['subject']) . '</td>';
                    echo '<td>' . htmlspecialchars($data['facultyName']) . '</td>';
                    echo '<td>' . htmlspecialchars($data['contactNumber']) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }

            // Close connection
            $conn->close();
            ?>