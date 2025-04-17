<?php
include 'sql_conn.php'; // Include your connection script

// Define the path for the directory structure
$directoryPath = "uploads/Admin/placements/";

// Check if the directory already exists
if (!file_exists($directoryPath)) {
    // Attempt to create the directory, including any necessary parent directories
    if (mkdir($directoryPath, 0777, true)) {
    }
}

// SQL to create table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS placements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    collegeCode VARCHAR(255) NOT NULL,
    companyName VARCHAR(255) NOT NULL,
    start_date DATE,
    end_date DATE,
    uploadLinks VARCHAR(255),
    description TEXT,
    requirement TEXT,
    images TEXT,
    documents TEXT,
    requirementSkill TEXT,
    video TEXT,
    pdf TEXT
)";
$conn->query($sql);
// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitization
    $collegeCode = filter_input(INPUT_POST, 'collegeCode' );
    $companyName = filter_input(INPUT_POST, 'companyName' );
    $startDate = filter_input(INPUT_POST, 'start_date' );
    $endDate = filter_input(INPUT_POST, 'end_date' );
    $uploadLinks = filter_input(INPUT_POST, 'uploadLinks', FILTER_SANITIZE_URL);
    $description = filter_input(INPUT_POST, 'description' );
    $requirement = filter_input(INPUT_POST, 'requirement' );
    
    // Initialize variables for file paths
    $images = $documents = '';
    $videosPaths = [];
    $pdfsPaths = [];
    // File upload handling
    // Images
    // Handle single image upload
    if (isset($_FILES['images'])) {
        $targetFilePath = $directoryPath . basename($_FILES['images']['name']);
        if (move_uploaded_file($_FILES['images']['tmp_name'], $targetFilePath)) {
            $image = $targetFilePath; // Assuming $image is defined earlier to store the image path
        }
    }

    // Handle single document upload
    if (isset($_FILES['documents'])) {
        $targetFilePath = $directoryPath . basename($_FILES['documents']['name']);
        $fileType = mime_content_type($_FILES['documents']['tmp_name']);
        if (in_array($fileType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            if (move_uploaded_file($_FILES['documents']['tmp_name'], $targetFilePath)) {
                $document = $targetFilePath; // Assuming $document is defined earlier to store the document path
            }
        }
    }


    if (isset($_POST['requirementSkill']) && is_array($_POST['requirementSkill'])) {
        $requirementSkills = $_POST['requirementSkill']; // Array of skills
        
        // Process each requirementSkill
        for($i = 0; $i < count($requirementSkills); $i++) {
            $skill = filter_var($requirementSkills[$i]); // Sanitize the skill
            
            // Process and upload video
            if (isset($_FILES['video']['name'][$i]) && $_FILES['video']['error'][$i] == 0) {
                $videoFileName = $_FILES['video']['name'][$i];
                $videoTmpName = $_FILES['video']['tmp_name'][$i];
                $videoTargetPath = $directoryPath . basename($videoFileName);
                if (move_uploaded_file($videoTmpName, $videoTargetPath)) {
                    // Store video path for database insertion
                    $videosPaths[] = $videoTargetPath;
                }
            }

            // Process and upload pdf
            if (isset($_FILES['pdf']['name'][$i]) && $_FILES['pdf']['error'][$i] == 0) {
                $pdfFileName = $_FILES['pdf']['name'][$i];
                $pdfTmpName = $_FILES['pdf']['tmp_name'][$i];
                $pdfTargetPath = $directoryPath . basename($pdfFileName);
                if (move_uploaded_file($pdfTmpName, $pdfTargetPath)) {
                    // Store pdf path for database insertion
                    $pdfsPaths[] = $pdfTargetPath;
                }
            }

            // Assuming a new table or adjusted schema for individual requirement entries
            $stmt = $conn->prepare("INSERT INTO placements (collegeCode, companyName, start_date, end_date, uploadLinks, description, requirement, images, documents, requirementSkill, video, pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->bind_param("ssssssssssss", $collegeCode, $companyName, $startDate, $endDate, $uploadLinks, $description, $requirement, $image, $document, $skill, $videoTargetPath, $pdfTargetPath)) {
                $stmt->execute();
            }
        }
    }
    echo "<script>alert('Data uploaded successfully');window.location.href='placements_upload.php';</script>";
    $stmt->close();
    $conn->close();
}else{
    echo "<script>alert('Data not uploaded');window.location.href='placements_upload.php';</script>";
    $conn->close();
    exit();
}
?>
