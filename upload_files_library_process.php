<?php
session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
include 'sql_conn.php';

    // Function to sanitize inputs
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    // Get form data
    $category = sanitizeInput($_POST["category"]);
    $book_name = sanitizeInput($_POST["book_name"]);
    $description = sanitizeInput($_POST["description"]);

    $course = $branch = $year = null;

    if ($category == "class" || $category == "question") {
        // If the category is "Class Materials" or "Question Papers"
        $course = isset($_POST["course"]) ? sanitizeInput($_POST["course"]) : null;
        $branch = isset($_POST["branch"]) ? sanitizeInput($_POST["branch"]) : null;
        $year = isset($_POST["year"]) && $_POST["year"] !== '' ? sanitizeInput($_POST["year"]) : null;
    }

    // File upload paths
    $uploadDir = "uploads/library/";
    $image_path = $uploadDir . "images/" . uniqid() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $file_path = $uploadDir . "files/" . uniqid() . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    // Check if the target directories exist, if not, create them
    if (!file_exists($uploadDir . "images/")) {
        mkdir($uploadDir . "images/", 0777, true);
    }

    if (!file_exists($uploadDir . "files/")) {
        mkdir($uploadDir . "files/", 0777, true);
    }

    // Move uploaded files to the specified directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
        // If category is not "question," move image file
        if ($category != "question" && move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            // Insert data into the library table using prepared statements
            $stmt = $conn->prepare("INSERT INTO library (category, course, branch, year, book_name, description, image_path, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $category, $course, $branch, $year, $book_name, $description, $image_path, $file_path);
        } else if ($category == "question") {
            // If category is "question," insert without image
            $stmt = $conn->prepare("INSERT INTO library (category, course, branch, year, book_name, description, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $category, $course, $branch, $year, $book_name, $description, $file_path);
        } else {
            echo "<script>alert('Error moving image file to the destination directory.');</script>";
            exit();
        }

        if ($stmt->execute()) {
            echo "<script>alert('File uploaded successfully!');window.location.href='upload_files_library.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error."');window.location.href='upload_files_library.php';</script>";
        }

    } else {
        echo "<script>alert('Error moving files to the destination directory.');window.location.href='upload_files_library.php';</script>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
