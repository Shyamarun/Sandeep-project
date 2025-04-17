<?php
$userID = isset($_POST['userID']) ? $_POST['userID'] : null;
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

    $resultsTable = $course . "_" . $semester . "_results";
    $sgpaTable = $course . "_" . $semester . "_sgpa";

    // Retrieve records from the results table where grade is 'F'
    $failedStudentsQuery = "SELECT reg_num, sub_code, sub_name FROM $resultsTable WHERE grade = 'F'";
    $failedStudentsResult = $conn->query($failedStudentsQuery);

    $csvFilePath = $_FILES["csvFile"]["tmp_name"];
    if (($csvFile = fopen($csvFilePath, 'r')) !== FALSE) {
        while (($csvData = fgetcsv($csvFile)) !== FALSE) {
            $reg_num_csv = $csvData[0];
            $sub_code_csv = $csvData[1];
            $sub_name_csv = $csvData[2];
            $grade_csv = $csvData[4];
            $credit_csv = $csvData[5];

            if ($grade_csv != 'ABSENT' && $grade_csv != 'MP' && $grade_csv != 'F') {
                if ($failedStudentsResult) {
                    while ($row = $failedStudentsResult->fetch_assoc()) {
                        if ($row["reg_num"] == $reg_num_csv && $row["sub_code"] == $sub_code_csv && $row["sub_name"] == $sub_name_csv) {
                            // Update grade
                            $updateGradeQuery = "UPDATE $resultsTable SET grade = ?, credit = ? WHERE reg_num = ? AND sub_code = ? AND sub_name = ?";
                            if ($updateStmt = $conn->prepare($updateGradeQuery)) {
                                // Convert $credit_csv to a float
                                $credit_csv = floatval($credit_csv);

                                // Bind parameters - 's' for strings, 'd' for double (float)
                                $updateStmt->bind_param('sdsss', $grade_csv, $credit_csv, $reg_num_csv, $sub_code_csv, $sub_name_csv);

                                // Execute the statement
                                $updateStmt->execute();
                            }

                        }
                        
                    }
                    // Reset pointer to the first element
                    $failedStudentsResult->data_seek(0);
                }
            }
        }
        fclose($csvFile);
    }
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

    // Calculate and update SGPA
    $updateSGPAQuery = "UPDATE $sgpaTable sg,
                        (SELECT reg_num, SUM(grade_point * credit) / SUM(credit) AS sgpa
                        FROM $resultsTable
                        GROUP BY reg_num) calc
                        SET sg.sgpa = calc.sgpa
                        WHERE sg.reg_num = calc.reg_num";

    if ($conn->query($updateSGPAQuery) !== TRUE) {
        echo "Error updating SGPA: " . $conn->error . "<br>";
    }

    echo "<script>alert('Data updated successfully for Semester $semester!');</script>";
    echo "<script>window.location.href = 'insert_supply_results.php?userID=" . $userID . "';</script>";
}
// Close the database connection
$conn->close();
?>