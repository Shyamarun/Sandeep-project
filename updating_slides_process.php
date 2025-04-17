<?php
include 'sql_conn.php';

// Check if slideId is provided
if (!isset($_POST['slideId'])) {
    echo "Slide ID is required.";
    exit;
}

$slideId = $conn->real_escape_string($_POST['slideId']);
$title = $conn->real_escape_string($_POST['title']);
$content = $conn->real_escape_string($_POST['content']);

// Check for existing entries in the slides table
$checkSql = "SELECT COUNT(*) AS count FROM slides";
$result = $conn->query($checkSql);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // No entries exist, insert duplicate data for 6 IDs
    for ($i = 1; $i <= 6; $i++) {
        $insertSql = "INSERT INTO slides (id, title, content, image_path) VALUES (?, 'Default Title', 'Default Content', 'default.jpg')";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("i", $i);
        $insertStmt->execute();
        $insertStmt->close();
    }
}

// Proceed with file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $targetDir = "uploads/slides/" . $slideId . "/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imagePath = $targetFile;
    } else {
        echo "<script>alert('Error uploading file.'); window.location.href='update_slides.php';</script>";
        exit;
    }
} else {
    $imagePath = ''; // No image file detected or upload error
}

// SQL to update slide
$sql = "UPDATE slides SET title=?, content=?, image_path=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $title, $content, $imagePath, $slideId);

if ($stmt->execute()) {
    echo "<script>alert('Slide updated successfully.'); window.location.href='update_slides.php';</script>";
} else {
    echo "<script>alert('Error updating record: " . $stmt->error . "'); window.location.href='update_slides.php';</script>";
}

$stmt->close();
$conn->close();
?>
