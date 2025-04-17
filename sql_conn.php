<?php
// Database connection details
$host = "localhost";
$user = "mrx";
$password = "2905";
$database = "projectx";

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
