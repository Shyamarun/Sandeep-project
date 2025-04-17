<?php
$userID = isset($_POST['userID']) ? $_POST['userID'] : null;
echo $userID;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Import</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">CSV Import</h2>

    <form action="insert_supply_results_process.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name='userID' value="<?php echo $userID; ?>">
        <div class="mb-3">
            <label for="course" class="form-label">Select Course:</label>
            <select name="course" id="course" class="form-select" onchange="updateSemesters()" required>
                <option value="btech">B.Tech</option>
                <option value="degree">Degree</option>
                <option value="diploma">Diploma</option>
                <option value="bpharmacy">B.Pharmacy</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="semester" class="form-label">Select Semester:</label>
            <select name="semester" id="semester" class="form-select" required>
                <!-- Semester options will be dynamically populated -->
            </select>
        </div>
        <div class="mb-3">
            <label for="csvFile" class="form-label">Choose a CSV file:</label>
            <input type="file" name="csvFile" id="csvFile" class="form-control" accept=".csv">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Import Data</button>
    </form>
</div>

<script>
    function updateSemesters() {
        var course = document.getElementById('course').value;
        var semesterSelect = document.getElementById('semester');
        semesterSelect.innerHTML = ''; // Clear current options

        var semesters = (course === 'Diploma' || course === 'Degree') ? ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2'] : ['1_1', '1_2', '2_1', '2_2', '3_1', '3_2', '4_1', '4_2'];

        semesters.forEach(function(semester) {
            var option = document.createElement('option');
            option.value = semester;
            option.text = semester;
            semesterSelect.appendChild(option);
        });
    }

    // Initialize semester dropdown on page load
    window.onload = function() {
        updateSemesters();
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
