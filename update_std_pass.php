<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Update</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript">
        function verifyPassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
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
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <?php
                        include 'sql_conn.php'; // Include your database connection
                        session_start();
                        if (!isset($_SESSION['reg_num'])) {
                            echo "<script>alert('Registration number not provided.');window.location.href='student.php';</script>";
                            exit;
                        }

                        $reg_num = $_SESSION['reg_num'];

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $existingPassword = $_POST['existingPassword'];
                            $newPassword = $_POST['newPassword'];

                            // Query to fetch user password
                            $stmt = mysqli_prepare($conn, "SELECT user_pass FROM stdreg WHERE reg_num = ?");
                            mysqli_stmt_bind_param($stmt, "s", $reg_num);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $userData = mysqli_fetch_assoc($result);

                            if ($userData && $existingPassword === $userData['user_pass']) {
                                if ($newPassword != $existingPassword) {
                                    // Handle file upload
                                    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
                                        $targetDir = "uploads/profile/";
                                        if (!is_dir($targetDir)) {
                                            mkdir($targetDir, 0777, true);
                                        }
                                        $fileName = time() . '_' . basename($_FILES["profilePhoto"]["name"]);
                                        $targetFilePath = $targetDir . $fileName;
                                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                                        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                                        if (in_array($fileType, $allowTypes)) {
                                            if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $targetFilePath)) {
                                                $updateStmt = mysqli_prepare($conn, "UPDATE stdreg SET user_pass = ?, profile_photo = ? WHERE reg_num = ?");
                                                mysqli_stmt_bind_param($updateStmt, "sss", $newPassword, $targetFilePath, $reg_num);
                                            } else {
                                                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                                            }
                                        } else {
                                            echo "<script>alert('Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.');</script>";
                                        }
                                    } else {
                                        // Update without changing profile photo
                                        $updateStmt = mysqli_prepare($conn, "UPDATE stdreg SET user_pass = ? WHERE reg_num = ?");
                                        mysqli_stmt_bind_param($updateStmt, "ss", $newPassword, $reg_num);
                                    }

                                    $updateSuccess = mysqli_stmt_execute($updateStmt);
                                    if ($updateSuccess) {
                                        echo "<script>alert('Password and profile photo updated successfully'); window.location.href='logout.php';</script>";
                                    } else {
                                        echo "<script>alert('Password updation failed'); window.location.href='student.php';</script>";
                                    }
                                } else {
                                    echo "<script>alert('Existing password cannot be new password.')</script>";
                                }
                            } else {
                                echo "<script>alert('Existing password is incorrect.')</script>";
                            }
                        }
                        ?>
                        <form method="post" onsubmit="return verifyPassword();" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="existingPassword">Existing Password</label>
                                <input type="password" class="form-control" name="existingPassword" id="existingPassword" placeholder="Existing Password" required>
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control" name="newPassword" id="password" placeholder="New Password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm New Password" required>
                            </div>
                            <div class="form-group">
                                <label for="profilePhoto">Profile Photo</label>
                                <input type="file" class="form-control-file" id="profilePhoto" name="profilePhoto">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and its dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
