<?php
include 'sql_conn.php';
session_start();
$user_id = $_SESSION['user_id'];
$staffType = $_GET['staffType'] ?? '';
$designation = $_GET['designation'] ?? '';

// Check if user_id contains 'AVEV' and one of the specified values
$allowedIds = ['DIR', 'PRI', 'VPRI', 'AO'];
$isAllowedUser = strpos($user_id, 'AVEV') !== false && in_array(substr($user_id, 4), $allowedIds);

// SQL query with filters
$sql = "SELECT * FROM staff_details";
$conditions = [];

// Apply condition if user is allowed
if ($isAllowedUser) {
    $conditions[] = "user_id = '" . $conn->real_escape_string($user_id) . "'";
}

if ($staffType) {
    $conditions[] = "staffType = '" . $conn->real_escape_string($staffType) . "'";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$staffData = [];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffData[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Staff Display</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function fetchDesignations(staffType) {
            $.ajax({
                url: 'get_designations.php',
                type: 'GET',
                data: {
                    staffType: staffType
                },
                success: function(response) {
                    $('#designationFilter').html('<option value="">Select Designation</option>' + response).prop('disabled', false);
                }
            });
        }

        $(document).ready(function() {
            $('#staffTypeFilter').change(function() {
                var selectedType = $(this).val();
                fetchDesignations(selectedType);
            });
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('library_back.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .filter-form,
        .search-form {
            margin-bottom: 20px;
        }

        .card {
            width: 100%;
            border-radius: 15px;
            border: solid 1px;
            overflow: hidden;
            transition: transform 0.3s;
            position: relative;
            display: flex;
            flex-direction: row;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card img {
            width: 25%;
            height: 100%;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
            object-fit: cover;
            border-right: 2px solid #ddd;
        }

        .card-content {
            padding: 10px;
            box-sizing: border-box;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-content h5 {
            margin-bottom: 5px;
        }

        .card-content p {
            margin-bottom: 10px;
            flex-grow: 1;
        }

        .btn-view-book {
            margin-top: auto;
            display: block;
        }

        .custom-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }

        .custom-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .custom-table th {
            background-color: #007bff;
            color: white;
        }

        .custom-table img {
            width: 100px;
            /* Adjust based on your needs */
            height: auto;
            border-radius: 5px;
            /* Optional for rounded corners */
        }

        .head {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="head">
            <div class="container">
                <h2>Staff Details</h2>
                <form action="staff_display.php" method="get">
                    <div class="form-group">
                        <label for="staffTypeFilter">Staff Type:</label>
                        <select class="form-control" id="staffTypeFilter" name="staffType">
                            <option value="">Select Type</option>
                            <option value="Exam Cell">Exam Cell</option>
                            <option value="Office Staff">Office Staff</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="designationFilter">Designation:</label>
                        <select class="form-control" id="designationFilter" name="designation" disabled>
                            <option value="">Select Designation</option>
                            <!-- Options will be populated based on AJAX request -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Staff Type</th>
                            <th>Designation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffData as $staff) { ?>
                            <tr>
                                <td><?php echo $staff['name']; ?></td>
                                <td><?php echo $staff['contact']; ?></td>
                                <td><?php echo $staff['email']; ?></td>
                                <td><?php echo $staff['staffType']; ?></td>
                                <td><?php echo $staff['designation']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>