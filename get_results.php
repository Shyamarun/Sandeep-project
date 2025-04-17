<?php
// Establish the database connection (similar to your main PHP file)
$servername = "localhost";
$username = "mrx";
$password = "2905";
$dbname = "projectx";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedRoll = isset($_GET['roll']) ? $_GET['roll'] : '';
$selectedSemester = isset($_GET['semester']) ? $_GET['semester'] : '';

if ($selectedSemester) {
    $tableNameResults = "B_Tech_{$selectedSemester}_results";
    $sqlResults = "SELECT * FROM `$tableNameResults` WHERE roll = '$selectedRoll'";
    $resultResults = $conn->query($sqlResults);

    if ($resultResults->num_rows > 0) {
        // Display the table header
        echo '<table border="1">
                <tr>
                    <th>Roll</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Internals</th>
                    <th>Grade</th>
                    <th>Credit</th>
                    <th>Grade Point</th>
                </tr>';

        // Fetch and display all rows from the result set
        while ($rowResults = $resultResults->fetch_assoc()) {
            echo '<tr>
                    <td>' . $rowResults['roll'] . '</td>
                    <td>' . $rowResults['sub_code'] . '</td>
                    <td>' . $rowResults['sub_name'] . '</td>
                    <td>' . $rowResults['internals'] . '</td>
                    <td>' . $rowResults['grade'] . '</td>
                    <td>' . $rowResults['credit'] . '</td>
                    <td>' . $rowResults['grade_point'] . '</td>
                </tr>';
        }

        // Close the table
        echo '</table>';
    }
}

// Close the database connection
$conn->close();
?>
