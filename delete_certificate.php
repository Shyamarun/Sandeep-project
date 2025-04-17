<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Connect to your database
    include 'sql_conn.php';

    // Delete certificate data from student_certificates table
    $sql = "DELETE FROM certificates WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Certificate deleted successfully!');window.location.href='view_certificates.php'</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
