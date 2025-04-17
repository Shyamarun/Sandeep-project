<?php
session_start();
if (isset($_GET['reg_num'])) {
    $reg_num = $_GET['reg_num'];
    $class_id=$_SESSION['class_id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];

        // Connect to your database
        include 'sql_conn.php';

        // Create the necessary folders if they don't exist
        $class_id_folder = "uploads/certificates/$reg_num/";

        if (!is_dir($class_id_folder)) {
            mkdir($class_id_folder, 0777, true);
        }

        // Upload the file to the specified directory
        $target_file = $class_id_folder . basename($_FILES["certificate_file"]["name"]);

        if (move_uploaded_file($_FILES["certificate_file"]["tmp_name"], $target_file)) {
            // Insert certificate data into student_certificates table
            $sql = "INSERT INTO certificates (class_id,reg_num, certificate_name, certificate_description, file_path) VALUES ('$class_id','$reg_num', '$name', '$description', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Certificate added successfully!');window.location.href='display_certificates.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }

        $conn->close();
    }
}
