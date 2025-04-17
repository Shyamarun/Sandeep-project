<!DOCTYPE html>
<html>
<head>
    <title>HOD Registration</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container center">
        <h2 class="text-center">HOD Registration</h2>
        <form id="registrationForm" action='hod_reg_process.php' method="post" enctype="multipart/form-data" onsubmit="return verifyPassword()">
            <div class="form-group">
                <label for="collegeName">College Name:</label>
                <input type="text" class="form-control" id="collegeName" name="collegeName" required>
            </div>
            <div class="form-group">
                <label for="collegeCode">College Code:</label>
                <input type="text" class="form-control" id="collegeCode" name="collegeCode" required>
            </div>
            <div class="form-group">
                <label for="stream">stream</label>
                <select class="form-control" id="stream" name="stream" required onchange="changestream()">
                    <option value="" disabled selected>Select Your stream</option>
                    <option value="btech">B.Tech</option>
                    <option value="bpharmacy">B.Pharmacy</option>
                    <option value="degree">Degree</option>
                    <option value="diploma">Diploma</option>
                </select>
            </div>
            <div class="form-group">
                <label for="branch">Branch:</label>
                <select class="form-control" id="branch" name="branch" required>
                    <!-- Options will be loaded based on stream selection -->
                </select>
            </div>
            <!-- Other form fields -->
            <div class="form-group">
                <label for="profile_photo">Profile Photo</label>
                <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/jpeg, image/png, image/gif, image/bmp, image/tiff" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
    <script>
            $(document).ready(function () {
                // Update branch options based on stream selection
                $('#stream').change(function () {
                    var stream = $(this).val();
                    $('#branch').empty();
                    $('#branch').append('<option value="">Select Branch</option>');
                    var branches = [];
                    if (stream == 'btech') {
                        branches = ['CSE', 'CSM', 'CSD', 'MECH', 'EEE', 'ECE', 'AI'];
                    } else if (stream == 'diploma') {
                        branches = ['MECH', 'EEE', 'CSE', 'ECE'];
                    } else if (stream == 'degree') {
                        branches = ['B.Com commerce', 'B.Com computer applications', 'B.Sc Mathematics', 'B.Sc statistics', 'B.Sc computer science', 'B.Sc Electronics', 'B.Sc Physics'];
                    } else if (stream == 'bpharmacy') {
                        branches = ['Pharm.D', 'B.Pharma', 'M.Pharma'];
                    }
                    branches.forEach(function (branch) {
                        $('#branch').append('<option value="' + branch + '">' + branch + '</option>');
                    });
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

