<?php
include 'sql_conn.php';
if (isset($_POST['uploadBtn']) && isset($_FILES['csvFile'])) {
    $collegeCode = strtoupper($_POST['collegeCode']);
    $course = $_POST['course'];
    $file = $_FILES['csvFile']['tmp_name'];
    $handle = fopen($file, "r");

    include 'sql_conn.php';

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $subject = $data[0];

        // Check if subject already exists
        $checkQuery = "SELECT * FROM subjects WHERE course='$course' AND subject='$subject'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows == 0) {
            $insertQuery = "INSERT INTO subjects (course, subject,collegeCode) VALUES ('$course', '$subject','$collegeCode')";
            $conn->query($insertQuery);
        }
    }

    fclose($handle);
    $conn->close();

    echo "<script>alert('Subjects uploaded successfully!'); window.location.href='import_sub_data.php';</script>";

}
?>
