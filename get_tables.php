<?php
// Database credentials
$hostname = "localhost";
$username = "mrx";
$password = "2905";
$database = "projectx";

// Connect to the database
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Select the database to use
$mysqli->select_db($database);

// Get list of tables from the database
$tables = $mysqli->query("SHOW TABLES");

// Prepare file for writing SQL statements
$file = fopen("updated_database.php", "w");
fwrite($file, "<?php\n");

while ($table = $tables->fetch_array()) {
    $tableName = $table[0];

    // Fetching columns and types for each table
    $columns = $mysqli->query("DESCRIBE $tableName");

    $createTableSQL = "CREATE TABLE IF NOT EXISTS $tableName (";

    $colDetails = [];
    while ($column = $columns->fetch_assoc()) {
        $colDetails[] = $column['Field'] . " " . $column['Type'];
    }

    $createTableSQL .= implode(", ", $colDetails) . ");\n";

    // Write the SQL statement to file
    fwrite($file, $createTableSQL);
}

fwrite($file, "?>");
fclose($file);

$mysqli->close();
