<?php
include 'sql_conn.php';
session_start();
// Assuming reg_num is being sent via POST or GET
if (isset($_POST['reg_num']) || isset($_GET['reg_num'])) {
    $reg_num = isset($_POST['reg_num']) ? $_POST['reg_num'] : $_GET['reg_num'];

    // Array of related tables in the database
    $relatedTables = array("table1", "table2", "table3"); // Replace with your actual table names

    // Perform cascading delete on each related table
    foreach ($relatedTables as $table) {
        $deleteQuery = "DELETE FROM $table WHERE reg_num = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $reg_num);

        if (!$stmt->execute()) {
            echo "Error deleting records from $table: " . $stmt->error;
            exit();
        }

        $stmt->close();
    }

    // After deleting from related tables, delete from stdreg table
    $deleteStdregQuery = "DELETE FROM stdreg WHERE reg_num = ?";
    $stmtStdreg = $conn->prepare($deleteStdregQuery);
    $stmtStdreg->bind_param("s", $reg_num);

    if (!$stmtStdreg->execute()) {
        echo "Error deleting record from stdreg: " . $stmtStdreg->error;
        exit();
    }

    $stmtStdreg->close();

    // Close the database connection
    $conn->close();

    echo "Records deleted successfully.";
} else {
    echo "Error: reg_num not provided.";
}
