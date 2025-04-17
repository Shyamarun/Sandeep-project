<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Registration Form</h2>
        <form action="pri_dir_ao_registration_process.php" method="post" enctype="multipart/form-data" onsubmit="return verifyPassword()">
            <div class="form-group">
                <label for="collegeName">College Name:</label>
                <input type="text" class="form-control" id="collegeName" name="collegeName" required>
            </div>
            <div class="form-group">
                <label for="collegeCode">College Code:</label>
                <input type="text" class="form-control" id="collegeCode" name="collegeCode" required>
            </div>
            <div class="form-group">
                <label for="course">Course</label>
                <select class="form-control" id="course" name="course" required onchange="changeCourse()">
                    <option value="" disabled selected>Select Your Course</option>
                    <option value="btech">B.Tech</option>
                    <option value="bpharmacy">B.Pharmacy</option>
                    <option value="degree">Degree</option>
                    <option value="diploma">Diploma</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Designation:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="designation" id="principal" value="Pri" required>
                    <label class="form-check-label" for="principal">Principal</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="designation" id="director" value="Dir" required>
                    <label class="form-check-label" for="director">Director</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="designation" id="vice_principal" value="VPri" required>
                    <label class="form-check-label" for="vice_principal">Vice Principal</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="designation" id="AO" value="AO" required>
                    <label class="form-check-label" for="AO">AO</label>
                </div>
            </div>
            <div class="form-group">
                <label for="profilePhoto">Profile Photo:</label>
                <input type="file" class="form-control-file" id="profilePhoto" name="profilePhoto" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Re-enter Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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