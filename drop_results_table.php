<?php
$host = 'localhost';
$db   = 'projectx';
$user = 'mrx';
$pass = '2905';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Drop tables starting with 'b_tech%'
$sql = "SHOW TABLES LIKE 'btech%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS " . $table[0];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "Table " . $table[0] . " has been dropped successfully.\n";
}

// Drop tables starting with 'Attendance'
$sql = "SHOW TABLES LIKE 'Attendance%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS " . $table[0];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "Table " . $table[0] . " has been dropped successfully.\n";
}

$sql = "SHOW TABLES LIKE 'bpharmacy%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS " . $table[0];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "Table " . $table[0] . " has been dropped successfully.\n";
}
$sql = "SHOW TABLES LIKE 'degree%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS " . $table[0];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "Table " . $table[0] . " has been dropped successfully.\n";
}
$sql = "SHOW TABLES LIKE 'diploma%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS " . $table[0];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "Table " . $table[0] . " has been dropped successfully.\n";
}
$sql = "SHOW TABLES LIKE '_%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS " . $table[0];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "Table " . $table[0] . " has been dropped successfully.\n";
}

echo "<script>alert('Tables dropped successfully');window.location.href='admin_home.php';</script>";
