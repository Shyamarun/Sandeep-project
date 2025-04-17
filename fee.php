<?php
session_start();
$userID = $_SESSION['user_id'];

// MySQL database connection
include 'sql_conn.php';

// Query staff_details table for additional user details
$staffDetailsQuery = "SELECT collegeCode, staffType, designation FROM admin_details WHERE userID = ?";
$staffDetailsStmt = $conn->prepare($staffDetailsQuery);
$staffDetailsStmt->bind_param("s", $userID);
$staffDetailsStmt->execute();
$staffDetailsResult = $staffDetailsStmt->get_result();

$collegeCode = '';
$staffType = '';
$designation = '';

if ($staffDetailsResult->num_rows > 0) {
    $staffDetailsRow = $staffDetailsResult->fetch_assoc();
    $collegeCode = $staffDetailsRow['collegeCode'];
    $staffType = strtoupper(str_replace(' ', '', $staffDetailsRow['staffType'])); // Remove spaces
    $designation = strtoupper(str_replace(' ', '', $staffDetailsRow['designation']));
}

$staffDetailsStmt->close();

// Only proceed if staffType and designation conditions are met
if ($staffType === 'ADMINISTRATIONSTAFF' && strpos($designation, 'FEE') !== false) {
    $reg_num_search = isset($_GET['reg_num_search']) ? $_GET['reg_num_search'] : '';

    // SQL to select records from fees table
    if (!empty($reg_num_search)) {
        // Filter by reg_num if provided
        $sql = "SELECT * FROM fees WHERE reg_num = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $reg_num_search);
    } else {
        // Display all records where class_id matches collegeCode
        $sql = "SELECT * FROM fees WHERE class_id LIKE CONCAT('%', ?, '%')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $collegeCode);
    }

    if (!$stmt) {
        die("Error in prepare: " . $conn->error);
    }

    $stmt->execute();

    if ($stmt->errno) {
        die("Error in execute: " . $stmt->error);
    }

    $result = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        button {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="search-bar">
        <form action="" method="get">
            <input type="text" name="reg_num_search" placeholder="Enter Registration Number">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <table class="table mt-3">
        <tr>
            <th>Reg Num</th>
            <th>Tuition Fee</th>
            <th>Building Fund</th>
            <th>CRT Fee</th>
            <th>Bus Fee</th>
            <th>Hostel Fee</th>
            <th>Total Fee</th>
            <th>Amount Paid</th>
            <th>Due Amount</th>
            <th>Action</th>
        </tr>
        <?php
        $fee_types = [
            'Tuition Fee' => 'tution_fee',
            'Building Fund' => 'building_fund',
            'CRT Fee' => 'crt_fee',
            'Bus Fee' => 'bus_fee',
            'Hostel Fee' => 'hostel_fee',
            'Total College Fee' => 'total_fee',
            'Amount Paid' => 'amount_paid',
            'Due Amount' => 'due_amount'
        ];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['reg_num'] . "</td>"; // Example for registration number
                foreach ($fee_types as $display_name => $db_field) {
                    echo "<td>" . $row[$db_field] . "</td>";
                }
                $rowDataJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                echo "<td><button class='btn btn-primary' onclick='openUpdateModal(\"" . $row['reg_num'] . "\"," . $rowDataJson . ")'>Update</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No data available</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <!-- Modal Content Here -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Fee Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for updating fees -->
                    <form id="updateFeeForm">
                        <!-- Fields will be added here by JavaScript -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateFeeForm">Update Fees</button>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a href="logout.php" class="navbar-brand">
            <img src="logout.png" width="30" height="30" alt="Profile Icon">
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function openUpdateModal(reg_num, feeData) {
            $('#updateFeeForm').empty();

            const feeTypes = [
                'tution_fee',
                'building_fund',
                'crt_fee',
                'bus_fee',
                'hostel_fee',
                'total_fee' // Include 'total_fee' in the array
            ];

            feeTypes.forEach(feeType => {
                let currentValue = feeData[feeType] || 0;
                let label = feeType === 'total_fee' ? 'Total College Fee' : feeType.replace('_', ' ').toUpperCase();
                $('#updateFeeForm').append(`
            <div class="form-group">
                <label for="${feeType}">${label}</label>
                <input type="number" class="form-control" id="${feeType}" name="${feeType}" value="${currentValue}" required>
            </div>
        `);
            });

            $('#updateFeeForm').append(`
        <input type="hidden" name="reg_num" value="${reg_num}">
    `);

            $("#updateModal").modal("show");
        }

        // Handle form submission
        $('#updateFeeForm').on('submit', function(e) {
            e.preventDefault();

            // Serialize form data
            let formData = $(this).serialize();

            // AJAX call to a PHP script to update fees in the database
            $.ajax({
                type: "POST",
                url: "update_fee_processing.php",
                data: formData,
                success: function(response) {
                    // Handle the response from the server
                    alert(response);
                    $("#updateModal").modal("hide");
                    // Optionally, refresh the page or update the table to reflect new data
                }
            });
        });
    </script>
</body>

</html>