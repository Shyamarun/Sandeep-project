<?php
include 'sql_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collegeCode = $conn->real_escape_string(strtoupper($_POST['collegeCode']));
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $email = $conn->real_escape_string($_POST['email']);
    $staffType = strtoupper($conn->real_escape_string($_POST['staffType']));
    $password = $conn->real_escape_string($_POST['password']);

    // Abbreviation logic for staff_abb
    $staff_abb = getAbbreviation($staffType);

    $designation = isset($_POST['designation']) ? strtoupper($conn->real_escape_string($_POST['designation'])) : '';
    $desig_abb = getAbbreviation($designation);

    $userID = $collegeCode . $staff_abb . $desig_abb;

    $sql = "INSERT INTO staff_details (collegeCode, name, contact, email, staffType, designation, password, desig_abb, staff_abb, userID) 
            VALUES ('$collegeCode', '$name', '$contact', '$email', '$staffType', '$designation', '$password', '$desig_abb', '$staff_abb', '$userID')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New record created successfully');window.location.href='staff_registration.php';</script>";
    } else {
        echo "<script>alert('Registration unsuccessful');window.location.href='staff_registration.php';</script>";
    }

    $conn->close();
}

function getAbbreviation($input) {
    if (preg_match('/\d/', $input, $matches)) {
        $parts = preg_split('/[^A-Za-z]/', $input, -1, PREG_SPLIT_NO_EMPTY);
        $abb = '';
        if (count($parts) > 0) {
            $abb .= substr($parts[0], 0, 1);
        }
        $abb .= $matches[0];
    } else {
        $words = explode(' ', $input);
        if (count($words) > 1) {
            $abb = substr($words[0], 0, 1) . substr($words[1], 0, 1);
        } else {
            $abb = substr($input, 0, 3);
        }
    }
    return $abb;
}
?>
