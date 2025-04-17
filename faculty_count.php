<?php
// Include your database connection script
session_start();
include 'sql_conn.php';

// Function to extract the prefix from user_id
function extractPrefix($user_id)
{
    if (preg_match('/^(.*?)(DIR|PRI|VPRI|AO)/', $user_id, $matches)) {
        return $matches[1];
    }
    return false;
}

// Assuming user_id is stored in the session upon login
$user_id = $_SESSION['user_id'];
$prefix = extractPrefix($user_id);

$subjects = [];
if ($prefix) {
    $query = $conn->prepare("SELECT DISTINCT clg_code, stream FROM master_faculty");
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        $combinedCode = $row['clg_code'] . $row['stream'];

        if ($combinedCode == $prefix) {
            $subQuery = $conn->prepare("SELECT subject_name, COUNT(*) as subject_count FROM master_faculty WHERE clg_code = ? AND stream = ? GROUP BY subject_name");
            $subQuery->bind_param("ss", $row['clg_code'], $row['stream']);
            $subQuery->execute();
            $subResult = $subQuery->get_result();

            while ($subRow = $subResult->fetch_assoc()) {
                $subjects[] = $subRow;
            }
        }
    }
} else {
    echo "<script>alert('Invalid user_id');window.location.href='auth_matrix_home_page.php';</script>";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Subject Count Bar Graph</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <canvas id="subjectBarGraph"></canvas>
                </div>

                <script>
                    var subjects = <?php echo json_encode($subjects); ?>;
                    var labels = subjects.map(function(sub) {
                        return sub.subject_name;
                    });
                    var counts = subjects.map(function(sub) {
                        return sub.subject_count;
                    });

                    var ctx = document.getElementById('subjectBarGraph').getContext('2d');
                    var subjectBarGraph = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Subject Count',
                                data: counts,
                                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                                borderColor: 'rgba(0, 123, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: 20
                                }
                            }
                        }
                    });
                </script>
</body>

</html>