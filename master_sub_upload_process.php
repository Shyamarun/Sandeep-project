<?php
// Include the database connection file
include('sql_conn.php');
session_start();
// Retrieve data from the HTML form
$year = 0;
$semester = 0;
$regulation=strtoupper($_POST['regulation']);
$clg_code = strtoupper($_POST['clg_code']); // Assuming you are using POST method
$stream = $_POST['stream'];
$branch = $_POST['branch'];
$year = $_POST['year'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$subjectTeaching = isset($_POST['subjectTeaching']) ? $_POST['subjectTeaching'] : []; // This will be an array

foreach ($subjectTeaching as $subject_name) {
        $subject_name = strtoupper($conn->real_escape_string($subject_name));
        $stream = strtoupper(str_replace('.', '', $stream));

        // Check if the subject name contains a number
        if (preg_match('/\d/', $subject_name, $matches)) {
            // If the subject name contains a number, split the string at non-alphabetic characters
            $parts = preg_split('/[^A-Za-z]/', $subject_name, -1, PREG_SPLIT_NO_EMPTY);

            $subject_abb = '';
            if (count($parts) > 0) {
                // Use the first letter of the first word
                $subject_abb .= substr($parts[0], 0, 1);
            }
            // Append the first numeric character found
            $subject_abb .= $matches[0];
        } else {
            // Original logic
            $words = explode(' ', $subject_name);

            if (count($words) > 1) {
                // If there is a space separating words
                $firstLetters = substr($words[0], 0, 1) . substr($words[1], 0, 1);
            } else {
                // If there is no space separating words
                $firstLetters = substr($subject_name, 0, 3);
            }

            $subject_abb = $firstLetters;
        }

        $subject_abb = strtoupper($subject_abb);
        $subject_code = strtoupper($regulation . $stream . $subject_abb);
        $class_id = $_SESSION['class_id'];

    // SQL query to insert data into master_sub table
    $sql = "INSERT INTO master_sub (regulation,clg_code ,stream, branch, year, semester, section, subject_name, subject_code, subject_abb, class_id)
            VALUES ('$regulation', '$clg_code', '$stream','$branch', '$year', '$semester', '$section', '$subject_name', '$subject_code', '$subject_abb', '$class_id')";

    // Execute the query for each subject
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

echo "<script>alert('Subjects uploaded successfully!'); window.location.href='master_sub_upload.php';</script>";

// Close the database connection
$conn->close();
?>
