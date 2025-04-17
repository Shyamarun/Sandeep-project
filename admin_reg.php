<!DOCTYPE html>
<html>

<head>
    <title>Staff Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Staff Registration</h2>
        <form action="admin_reg_process.php" method="post" onsubmit="return verifyPassword()">
            <div class="form-group">
                <label>College Code</label>
                <input type="text" class="form-control" name="collegeCode" required>
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label>Contact</label>
                <input type="text" class="form-control" name="contact" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label>Staff Type</label>
                <select class="form-control" name="staffType" id="staffType" onchange="toggleDesignation()">
                    <option>Select Staff Type</option>
                    <option value="ADMINISTRATION STAFF">ADMINISTRATION MEMBERS</option>
                </select>
            </div>
            <div class="form-group" id="designationField">
                <select class="form-control" name="designation" id="designation" onchange="toggleDesignation()">
                    <option>Select designation</option>
                    <option value="PLACEMENT MANAGER">PLACEMENT MANAGER</option>
                    <option value="FEE MANAGER">FEE MANAGER</option>
                    <option value="SOFTWARE MANAGER">SOFTWARE MANAGER</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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