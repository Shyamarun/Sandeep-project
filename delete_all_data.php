<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php

        // Include the database connection file
        include 'sql_conn.php';

        class DatabaseManager {
            private $conn;

            public function __construct($conn) {
                $this->conn = $conn;
                $this->checkConnection();
            }

            private function checkConnection() {
                try {
                    if ($this->conn->connect_error) {
                        throw new Exception("Connection failed: " . $this->conn->connect_error);
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                    exit;
                }
            }

            public function clearStudentTables() {
                $tables = $this->getStudentTables();
                if ($tables) {
                    foreach ($tables as $table) {
                        $this->deleteTableData($table);
                    }
                } else {
                    echo "<script>alert('No tables found');window.location.href='admin_home.php';</script>";
                }
            }

            private function getStudentTables() {
                $sql = "SHOW TABLES FROM student";
                $result = $this->conn->query($sql);

                if (!$result) {
                    echo "<script>alert('No tables found');window.location.href='admin_home.php';</script>";
                    return false;
                }

                if ($result->num_rows == 0) {
                    return false;
                }

                $tables = [];
                while ($row = $result->fetch_assoc()) {
                    $tables[] = $row['Tables_in_student'];
                }
                return $tables;
            }

            private function deleteTableData($tableName) {
                $deleteQuery = "DELETE FROM `$tableName`"; // Wrap table name in backticks

                if ($this->conn->query($deleteQuery)) {
                    echo '<div class="alert alert-success" role="alert">Deleted data from table: ' . htmlspecialchars($tableName) . '</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error deleting data from table: ' . htmlspecialchars($tableName) . '. SQL Error: ' . $this->conn->error . '</div>';
                }
            }

            public function closeConnection() {
                $this->conn->close();
            }
        }

        // Using the class
        try {
            $dbManager = new DatabaseManager($conn);
            $dbManager->clearStudentTables();
        } finally {
            $dbManager->closeConnection();
        }

        ?>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
