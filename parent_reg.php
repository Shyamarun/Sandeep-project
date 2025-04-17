<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Parent Registration Form</h2>
        <form action="parent_reg_process.php" method="post" enctype="multipart/form-data" onsubmit="return verifyPassword()">
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="mobileNumber">Mobile Number:</label>
                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" required>
            </div>
            <div class="form-group">
                <label for="reg_num">Student Roll Number:</label>
                <input type="text" class="form-control" id="reg_num" name="reg_num" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="rePassword">Re-enter Password:</label>
                <input type="password" class="form-control" id="rePassword" name="rePassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
            function verifyPassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("rePassword").value;
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