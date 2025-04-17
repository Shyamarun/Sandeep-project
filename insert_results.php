<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login_page.php"); // Redirect to login if not logged in
    exit();
}

$userID = isset($_SESSION['user_data']['userID']) ? $_SESSION['user_data']['userID'] : null;
// MySQL database connection
include 'sql_conn.php';

// Check user authorization
if ($userID) {
    $query = "SELECT staffType FROM staff_details WHERE userID = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (strpos($user['staffType'], 'EXAM CELL') === false) {
            echo "<script>alert('Not authorized.');</script>";
            exit();
        }
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
} else {
    echo "<script>alert('User ID not found.');</script>";
    exit();
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $_POST["course"]; // Get the selected course
    $semester = $_POST["semester"];

    // Sanitize inputs
    $course = strtolower(mysqli_real_escape_string($conn, $course));
    $semester = strtolower(mysqli_real_escape_string($conn, $semester));

    // Set table names based on selected course
    $resultsTable = $course . "_" . $semester . "_results";
    $sgpaTable = $course . "_" . $semester . "_sgpa";

    // Check if the delete button is pressed
    if (isset($_POST["deleteData"])) {
        // Delete data for the selected semester from both tables
        $sqlDeleteResultsData = "DELETE FROM $resultsTable";
        if ($conn->query($sqlDeleteResultsData) !== TRUE) {
            echo "Error deleting data from $resultsTable: " . $conn->error . "<br>";
        }

        // Delete data from SGPA table
        $sqlDeleteSGPAData = "DELETE FROM $sgpaTable";
        if ($conn->query($sqlDeleteSGPAData) !== TRUE) {
            echo "Error deleting data from $sgpaTable: " . $conn->error . "<br>";
        }
    } else {
        // If delete button is not pressed, proceed with inserting data

        $csvFilePath = $_FILES["csvFile"]["tmp_name"];

        // Create the results table if it doesn't exist
        $sqlCreateResultsTable = "CREATE TABLE IF NOT EXISTS $resultsTable (
            reg_num VARCHAR(10),
            sub_code VARCHAR(10),
            sub_name TEXT,
            internals INT(2),
            grade VARCHAR(10),
            credit FLOAT(3, 1),
            grade_point INT DEFAULT NULL
        )";
        $sqlCreateSgpaTable = "CREATE TABLE IF NOT EXISTS $sgpaTable (
            reg_num VARCHAR(10),
            sgpa decimal(3,1)
        )";

        if ($conn->query($sqlCreateResultsTable) !== TRUE) {
            echo "Error creating table $resultsTable: " . $conn->error . "<br>";
        }
        if ($conn->query($sqlCreateSgpaTable) !== TRUE) {
            echo "Error creating table $sgpaTable: " . $conn->error . "<br>";
        }

        // Open the CSV file for reading
        $csvFile = fopen($csvFilePath, 'r');

        if ($csvFile === false) {
            die("Error opening CSV file");
        }

        // Loop through each row and insert into MySQL table
        while (($data = fgetcsv($csvFile)) !== false) {
            $reg_num = $data[0];
            $sub_code = $data[1];
            $sub_name = $data[2];
            $internals = $data[3];
            $grade = $data[4];
            $credit = $data[5];

            // Use prepared statement to insert data into MySQL table
            $sql = "INSERT INTO $resultsTable (reg_num, sub_code, sub_name, internals, grade, credit) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bind_param('sssssd', $reg_num, $sub_code, $sub_name, $internals, $grade, $credit);

            // Execute the statement
            if ($stmt->execute() !== TRUE) {
                echo "Error inserting data for reg_num $reg_num: " . $stmt->error . "<br>";
            }

            // Close the statement for the next iteration
            $stmt->close();
        }

        // Close the CSV file
        fclose($csvFile);

        $sqlUpdateGradePoint = "UPDATE $resultsTable
            SET grade_point = 
                CASE 
                    WHEN grade = 'A+' THEN 10
                    WHEN grade = 'A'  THEN 9
                    WHEN grade = 'B'  THEN 8
                    WHEN grade = 'C'  THEN 7
                    WHEN grade = 'D'  THEN 6
                    WHEN grade = 'E'  THEN 5
                    WHEN grade = 'F'  THEN 0
                    WHEN grade = 'COMPLE' THEN 0
                    WHEN grade = 'ABSENT' THEN 0
                END";

        if ($conn->query($sqlUpdateGradePoint) !== TRUE) {
            echo "Error updating grade_point in $resultsTable: " . $conn->error . "<br>";
        }

        // Insert SGPA into B_Tech_1_1_sgpa table
        $sqlInsertSGPA = "INSERT INTO $sgpaTable (reg_num, sgpa)
             SELECT
                 t.reg_num,
                 SUM(t.grade_point * t.credit) / SUM(t.credit) AS sgpa
             FROM
                 $resultsTable t
             GROUP BY
                 t.reg_num";

        if ($conn->query($sqlInsertSGPA) !== TRUE) {
            echo "Error inserting data into $sgpaTable: " . $conn->error . "<br>";
        }

        // Display success message using alert() in JavaScript
        echo "<script>alert('Data inserted successfully for Semester $semester!');</script>";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Import</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">CSV Import</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="course" class="form-label">Select Course:</label>
                <select name="course" id="course" class="form-select" onchange="updateSemesters()" required>
                    <option value="btech">B.Tech</option>
                    <option value="degree">Degree</option>
                    <option value="diploma">Diploma</option>
                    <option value="bpharmacy">B.Pharmacy</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Select Semester:</label>
                <select name="semester" id="semester" class="form-select" required>
                    <!-- Semester options will be dynamically populated -->
                </select>
            </div>
            <div class="mb-3">
                <label for="csvFile" class="form-label">Choose a CSV file:</label>
                <input type="file" name="csvFile" id="csvFile" class="form-control" accept=".csv">
            </div>
            <button type="submit" class="btn btn-primary">Import Data</button>
            <button type="submit" class="btn btn-danger" name="deleteData">Delete Data</button>
        </form>
        <div class="mt-3"></div>
        <form action="insert_supply_results.php" method="post">
            <input type="hidden" name="userID" value="<?php echo htmlspecialchars($userID); ?>">
            <button type="submit" class="btn btn-secondary">Post Supplementary Results</button>
        </form>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a href="logout.php" class="navbar-brand">
            <img src="logout.png" width="30" height="30" alt="Profile Icon">
        </a>
    </nav>
    <script>
        function updateSemesters() {
            var course = document.getElementById('course').value;
            var semesterSelect = document.getElementById('semester');
            semesterSelect.innerHTML = ''; // Clear current options

            var semesters = (course === 'diploma' || course === 'degree') ? ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2'] : ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];

            semesters.forEach(function(semester) {
                var option = document.createElement('option');
                option.value = semester;
                option.text = semester;
                semesterSelect.appendChild(option);
            });
        }

        // Initialize semester dropdown on page load
        window.onload = function() {
            updateSemesters();
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>