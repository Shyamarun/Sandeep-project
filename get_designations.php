<?php
include 'sql_conn.php';
session_start();
if (isset($_GET['staffType'])) {
    $staffType = $conn->real_escape_string($_GET['staffType']);

    $sql = "SELECT DISTINCT designation FROM staff_details WHERE staffType = '$staffType'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['designation']) . "'>" . htmlspecialchars($row['designation']) . "</option>";
        }
    }
}
$conn->close();
?>
