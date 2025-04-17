<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <form action="master_sub_upload_process.php" method="post" enctype="multipart/form-data">
                        <label for="regulation">Regulation:</label>
                        <input type="text" class="form-control" id="regulation" name="regulation" placeholder="Enter Regulation">
                        <label for="clg_code">College Code</label>
                        <input type="text" class="form-control" id="clg_code" name="clg_code" placeholder="Enter College Code">
                        <div id="subcategory">
                            <div class="form-group">
                                <label for="stream">Select Course:</label>
                                <select class="form-control" id="stream" name="stream" onchange="changeCourse()">
                                    <option value="">Select Branch</option>
                                    <option value="BTECH">B.Tech</option>
                                    <option value="DEGREE">Degree</option>
                                    <option value="DIPLOMA">Diploma</option>
                                    <option value="BPHARMACY">B.Pharmacy</option>
                                </select>
                            </div>
                            <div id="branchDiv">
                                <div class="form-group">
                                    <label for="branch">Select Branch:</label>
                                    <select class="form-control" id="branch" name="branch">
                                        <option value="">Select Branch</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year">Select Year:</label>
                                    <select class="form-control" id="year" name="year">
                                        <option value="">Select Year</option>
                                        <option value=1>1st Year</option>
                                        <option value=2>2nd Year</option>
                                        <option value=3>3rd Year</option>
                                        <option value=4>4th Year</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="semester">Select Semester</label>
                                    <select class="form-control" id="semester" name="semester">
                                        <option value="">Select Year</option>
                                        <option value=1>1</option>
                                        <option value=2>2</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="section">Select Section</label>
                                    <select class="form-control" id="section" name="section">
                                        <option value="">Select Section</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="subjectContainer" class="checkbox-group">
                                <!-- Subjects will be loaded here dynamically -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>

                <script>
                    function changeCourse() {
                        var course = document.getElementById("stream").value;
                        if (course === "BTECH") {
                            var options = ["CSE", "CSM", "CSD", "MECH", "EEE", "ECE", "AI"];
                        } else if (course === "DEGREE") {
                            var options = ["B.Com commerce", "B.Com computer applications", "B.Sc Mathematics", "B.Sc statistics", "B.Sc computer science", "B.Sc Electronics", "B.Sc Physics"];
                        } else if (course === "DIPLOMA") {
                            var options = ["MECH", "EEE", "CE", "ECE"];
                        } else if (course === "BPHARMACY") {
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
                    var subjectCount = 0;
                    $(document).ready(function() {
                        $('#stream').change(function() {
                            var stream = $(this).val();
                            var clg_code = $('#clg_code').val(); // Get the college code
                            $('#subjectContainer').empty(); // Clear subjects

                            if (stream && clg_code) {
                                $.ajax({
                                    url: 'master_get_sub.php',
                                    type: 'GET',
                                    data: {
                                        course: stream,
                                        collegeCode: clg_code
                                    }, // Send both stream and college code
                                    dataType: 'json',
                                    success: function(subjects) {
                                        subjects.sort(); // Ensure alphabetical order just in case
                                        var rows = $('<div class="form-row"></div>');
                                        subjects.forEach(function(subject, index) {
                                            var checkboxHtml = $(`<div class="col-md-4 form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="subjectTeaching[]" value="${subject}">
                                                        <label class="form-check-label">${subject}</label>
                                                    </div>`);
                                            rows.append(checkboxHtml);
                                            // Append the rows to subjectContainer after every 3 subjects or at the end
                                            if ((index + 1) % 3 === 0 || index === subjects.length - 1) {
                                                $('#subjectContainer').append(rows);
                                                rows = $('<div class="form-row"></div>'); // start a new row
                                            }
                                        });
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.log('Error:', textStatus, errorThrown);
                                    }
                                });
                            }
                        });
                    });
                </script>

</body>

</html>