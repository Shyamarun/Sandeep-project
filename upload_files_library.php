<?php
include 'sql_conn.php';
session_start();
$faculty_id = $_SESSION['faculty_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Upload</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('st.jpg');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .table-container {
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
            border: 2px solid #007bff;
            /* Solid border for a distinct frame */
        }

        table {
            background: transparent;
            /* Transparent background for the table */
        }

        th,
        td {
            color: #333;
            /* Dark text for readability */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            /* Bootstrap's default border color */
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            /* More opaque blue for header background */
            color: white;
            /* White text color for headers */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <h2> </h2>
            <table class="table table-bordered">
                <div class="container">
                    <h2>Upload Books</h2>
                    <form action="upload_files_library_process.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="category">Select Category:</label>
                            <select class="form-control" id="category" name="category" onchange="changeCategory()">
                                <option value="">Select Category</option>
                                <option value="universal">Universal Library</option>
                                <option value="external">External Abilities</option>
                                <option value="class">Class Materials</option>
                                <option value="question">Question Papers</option>
                            </select>
                        </div>
                        <div id="subcategory" style="display: none;">
                            <div class="form-group">
                                <label for="course">Select Course:</label>
                                <select class="form-control" id="course" name="course" onchange="changeCourse()">
                                    <option value="">Select Course</option>
                                    <option value="btech">B.Tech</option>
                                    <option value="degree">Degree</option>
                                    <option value="diploma">Diploma</option>
                                    <option value="pharmacy">Pharmacy</option>
                                </select>
                            </div>
                            <div id="branchDiv" style="display: none;">
                                <!-- Subject Selection is moved outside of this div -->
                            </div>
                        </div>
                        <div class="form-group" id="subjectDiv" style="display: none;">
                            <label for="subject">Subject Name:</label>
                            <select name="subject" class="form-control" required>
                                <option value="">Select Subject</option>
                                <?php
                                // Fetch distinct subjects from master_faculty
                                $sql = "SELECT DISTINCT subject_name FROM master_faculty WHERE faculty_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $faculty_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['subject_name']) . "'>" . htmlspecialchars($row['subject_name']) . "</option>";
                                }

                                $stmt->close();
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="book_name">Book Name</label>
                            <input type="text" class="form-control" id="book_name" name="book_name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Question Paper Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group" id="image-group">
                            <label for="image">Upload Image:</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept=".jpg, .jpeg, .png">
                        </div>
                        <div class="form-group" id="file-group">
                            <label for="file">Upload File:</label>
                            <input type="file" class="form-control-file" id="file" name="file" accept=".pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>

                </div>

                <script>
                    function changeCategory() {
                        var category = document.getElementById("category").value;
                        var bookNameLabel = document.querySelector('label[for="book_name"]');
                        var descriptionLabel = document.querySelector('label[for="description"]');
                        var imageGroup = document.getElementById('image-group');
                        var fileGroup = document.getElementById('file-group');
                        var subjectDiv = document.getElementById('subjectDiv');

                        if (category === "question") {
                            bookNameLabel.textContent = 'Subject Name:';
                            descriptionLabel.textContent = 'Question Paper Description:';
                            imageGroup.style.display = 'none';
                            fileGroup.style.display = 'block';
                        } else {
                            bookNameLabel.textContent = 'Book Name:';
                            descriptionLabel.textContent = 'Book Description:';
                            imageGroup.style.display = 'block';
                            fileGroup.style.display = 'block';
                        }

                        if (category === "class" || category === "question") {
                            subjectDiv.style.display = 'block';
                        } else {
                            subjectDiv.style.display = 'none';
                        }

                        if (category === "universal" || category === "external") {
                            document.getElementById("subcategory").style.display = "none";
                        } else {
                            document.getElementById("subcategory").style.display = "block";
                        }
                    }

                    function changeCourse() {
                        var course = document.getElementById("course").value;
                        if (course === "btech") {
                            var options = ["CSE", "CSM", "CSD", "MACH", "EEE", "ECE", "AI"];
                        } else if (course === "degree") {
                            var options = ["B.Com commerce", "B.Com computer applications", "B.Sc Mathematics", "B.Sc statistics", "B.Sc computer science", "B.Sc Electronics", "B.Sc Physics"];
                        } else if (course === "diploma") {
                            var options = ["MECH", "EEE", "CE", "ECE"];
                        } else if (course === "pharmacy") {
                            var options = ["Pharm.D", "B.Pharma", "M.Pharma"];
                        }

                        var select = document.getElementById("branch");
                        select.innerHTML = "";
                        for (var i = 0; i < options.length; i++) {
                            var opt = options[i];
                            var el = document.createElement("option");
                            el.textContent = opt;
                            el.value = opt;
                            select.appendChild(el);
                        }
                        document.getElementById("branchDiv").style.display = "block";
                    }
                </script>

</body>

</html>