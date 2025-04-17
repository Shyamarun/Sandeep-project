<?php
session_start();
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
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
        }
        ?>