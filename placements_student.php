<?php
session_start();
include 'sql_conn.php'; // Assume db.php connects to your database

$reg_num = $_SESSION['reg_num'];

// Fetch clg_code from stdreg table
$query = "SELECT clg_code FROM stdreg WHERE reg_num = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $reg_num);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$clg_code = $row['clg_code'];

// Fetch placements
$query = "SELECT companyName, description, start_date, end_date FROM placements WHERE collegeCode = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $clg_code);
$stmt->execute();
$placements = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Placement Opportunities</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('st.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .chart-container {
            width: 80%;
            margin: auto;
            margin-bottom: 20px;
        }

        .attendance-table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .attendance-table th,
        .attendance-table td {
            padding: 2px;
            text-align: center;
        }

        .attendance-table th {
            color: #333;
        }

        .color-legend {
            display: inline-block;
            width: 40px;
            height: 15px;
            border: 1px solid #000;
        }

        .attendance-percentage {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .chart-container {
                width: 100%;
                margin-bottom: 20px;
            }

            .attendance-table,
            .attendance-table th,
            .attendance-table td {
                padding: 1px;
            }

            .color-legend {
                width: 30px;
            }
        }

        /* Styles from the second code */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            border: 2px solid #007bff;
        }

        table {
            background: transparent;
        }

        th,
        td {
            color: #333;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <h2> </h2>
            <table class="table table-bordered">
                <div class="container mt-5">
                    <h2>Placement Opportunities</h2>
                    <div class="row">
                        <?php while ($placement = $placements->fetch_assoc()) : ?>
                            <div class="col-md-4 mt-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($placement['companyName']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($placement['description']) ?></p>
                                        <p class="card-text">Start Date: <?= htmlspecialchars($placement['start_date']) ?></p>
                                        <p class="card-text">End Date: <?= htmlspecialchars($placement['end_date']) ?></p>
                                        <a href="details.php?companyName=<?= urlencode($placement['companyName']) ?>&clg_code=<?= htmlspecialchars($clg_code) ?>" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
</body>

</html>