<?php
session_start();
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="container">
                    <h2>Fees Details</h2>
                    <canvas id="feesChart"></canvas>
                </div>

                <script>
                    fetch('matrix_fee_process.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'user_id=<?php echo $_SESSION['user_id']; ?>'
                        })
                        .then(response => {
                            if (!response.ok) {
                                alert('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data); // Debugging line to see what data is received
                            var ctx = document.getElementById('feesChart').getContext('2d');
                            var branchLabels = Object.keys(data);
                            var totalAmounts = branchLabels.map(branch => data[branch].total || 0); // Handle nulls
                            var paidAmounts = branchLabels.map(branch => data[branch].paid || 0); // Handle nulls
                            var dueAmounts = branchLabels.map(branch => data[branch].due || 0); // Handle nulls

                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: branchLabels,
                                    datasets: [{
                                        label: 'Total Amount',
                                        backgroundColor: 'rgb(255, 99, 132)',
                                        data: totalAmounts
                                    }, {
                                        label: 'Amount Paid',
                                        backgroundColor: 'rgb(54, 162, 235)',
                                        data: paidAmounts
                                    }, {
                                        label: 'Due Amount',
                                        backgroundColor: 'rgb(255, 205, 86)',
                                        data: dueAmounts
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred: ' + error.message);
                            window.location.href = 'auth_matrix_home_page.php';
                        });
                </script>

</body>

</html>