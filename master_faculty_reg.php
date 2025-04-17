<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Faculty Registration</title>
</head>

<body>
    <div class="container mt-5">
        <form action="master_faculty_reg_process.php" method="post" id="facultyForm" onsubmit="return verifyPassword()" enctype="multipart/form-data">
            <div class="form-group">
                <label for="regulation">Regulation</label>
                <input type="text" class="form-control" id="regulation" name="regulation" required>
            </div>
            <div class="form-group">
                <label for="clg_code">College Code</label>
                <input type="text" class="form-control" id="clg_code" name="clg_code" required>
            </div>
            <div class="form-group">
                <label for="facultyName">Faculty Name:</label>
                <input type="text" class="form-control" id="facultyName" name="facultyName" required>
            </div>
            <div class="form-group">
                <label for="profilePhoto">Profile Photo:</label>
                <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number:</label>
                <input type="tel" class="form-control" id="contactNumber" name="contactNumber" required>
            </div>
            <div class="form-group">
                <label for="whatsappNumber">WhatsApp Number:</label>
                <input type="tel" class="form-control" id="whatsappNumber" name="whatsappNumber" required>
            </div>
            <div class="form-group">
                <label for="contactEmail">Email ID (Faculty ID):</label>
                <input type="email" class="form-control" id="contactEmail" name="contactEmail" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <label for="stream">Stream:</label>
                <select class="form-control" id="stream" name="stream" required>
                    <option value="">Select Stream</option>
                    <option value="BTECH">B.Tech</option>
                    <option value="DEGREE">Degree</option>
                    <option value="DIPLOMA">Diploma</option>
                    <option value="BPHARMACY">B.Pharmacy</option>
                </select>
            </div>
            <div class="form-group">
                <div id="subjectContainer" class="checkbox-group">
                    <!-- Subjects will be loaded here dynamically -->
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
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
    <script>
        function verifyPassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            // Check password length
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }

            // Check for uppercase, lowercase, and special character
            var upperCase = /[A-Z]/;
            var lowerCase = /[a-z]/;
            var specialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;

            if (!upperCase.test(password)) {
                alert("Password must contain at least one uppercase letter.");
                return false;
            }

            if (!lowerCase.test(password)) {
                alert("Password must contain at least one lowercase letter.");
                return false;
            }

            if (!specialChar.test(password)) {
                alert("Password must contain at least one special character.");
                return false;
            }
            return true;
        }
    </script>


</body>

</html>