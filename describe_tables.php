<?php
include 'sql_conn.php';
// Get the list of tables
$result = $conn->query("SHOW TABLES");

$tableCount = 0;
echo "<table border='1'>";

while ($row = $result->fetch_array()) {
    $table = $row[0];
    echo "<tr><th colspan='2'>$table</th></tr>";

    $columns = $conn->query("DESCRIBE $table");

    while ($col = $columns->fetch_array()) {
        echo "<tr><td>" . $col[0] . "</td><td>" . $col[1] . "</td></tr>";
    }

    $tableCount++;
}

echo "</table>";

echo "Number of tables: $tableCount";

$conn->close();
