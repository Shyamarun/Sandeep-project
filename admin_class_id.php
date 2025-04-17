<?php
session_start();
include 'sql_conn.php';

$collegeCode = $_SESSION['collegeCode'];

// Use prepared statements for database queries
$stmt = $conn->prepare("SELECT class_id, class_id_pass FROM t_auth WHERE class_id LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $collegeCode);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <title>Class Records</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Class Records</h2>

        <!-- Search Bar -->
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search by Class ID" id="searchInput">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="searchRecords()">Search</button>
            </div>
        </div>

        <!-- Records Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class ID Pass</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['class_id'] . "</td>";
                        echo "<td>" . $row['class_id_pass'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<script>alert('No records found.'); window.location.href='admin_home.php';</script>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- Print Button -->
        <button class="btn btn-primary" onclick="printTable()">Print Records</button>
    </div>

    <!-- JavaScript for Search Functionality -->
    <script>
        function searchRecords() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function printTable() {
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print Table</title>');
            printWindow.document.write('<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(document.getElementById('recordsTable').outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close(); // Close the document for writing, so it's ready for printing
            setTimeout(function() {
                printWindow.print(); // Print the content
                printWindow.close(); // Close the print window after printing
            }, 250); // Short delay to ensure the print dialog opens
        }
    </script>
</body>

</html>