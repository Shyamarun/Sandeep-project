<?php
include 'sql_conn.php';
session_start();
// SQL query to retrieve records from a table
$sql = "SELECT * from teacher_info";

// Execute the query
$result = mysqli_query($connection, $sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['subjectcode'] . "</td>";
        echo "<td>" . $row['subjectname'] . "</td>";
        echo "<td>" . $row['facultyname'] . "</td>";
        echo "<td>" . $row['contactinfo'] . "</td>";
        echo "<td><form action='delete_teacherinfo.php' method='post'><input type='hidden' name='contactinfo' value='" . $row['contactinfo'] . "'><button type='submit' class='btn btn-danger'>Delete</button></form></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data available</td></tr>";
}
$connection->close();
?>