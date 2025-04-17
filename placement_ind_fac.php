<?php
session_start();
// Ensure there's a check for session existence and redirect or error handle if not found
if (!isset($_SESSION['reg_num'])) {
    // Redirect to login or show an error message
    exit('Session not set. Please login first.');
}

$faculty_id = $_SESSION['faculty_id'];
$videoPreviews = $pdfPreviews = [];
$skills = [];
include 'sql_conn.php'; 

// Query to get clg_code from stdreg table where reg_num matches
$clgCodeQuery = "SELECT clg_code FROM master_faculty WHERE faculty_id = ?";
if ($stmt = $conn->prepare($clgCodeQuery)) {
    $stmt->bind_param("s", $reg_num);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $clg_code = $row['clg_code'];
    } else {
        exit('No matching college code found.');
    }
    $stmt->close();
} else {
    exit('Query preparation failed: ' . $conn->error);
}

// Query to get all data from placement table where collegeCode matches clg_code
$placementsQuery = "SELECT * FROM placements WHERE collegeCode = ?";
if ($stmt = $conn->prepare($placementsQuery)) {
    $stmt->bind_param("s", $clg_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $placements = [];
    while ($row = $result->fetch_assoc()) {
        $placements[] = $row;
    }
    $stmt->close();
} else {
    exit('Query preparation failed: ' . $conn->error);
}
$skillsQuery = "SELECT DISTINCT requirementSkill FROM placements WHERE collegeCode = ? AND companyName = ?";
$skillsStmt = $conn->prepare($skillsQuery);
foreach ($placements as &$placement) {
    $skillsStmt->bind_param("ss", $placement['collegeCode'], $placement['companyName']);
    $skillsStmt->execute();
    $skillsResult = $skillsStmt->get_result();
    while ($skillRow = $skillsResult->fetch_assoc()) {
        $skills[] = $skillRow['requirementSkill'];
    }
    $placement['distinctSkills'] = implode(', ', $skills);

    // Assuming videos and PDFs are stored as semicolon-separated values
    $placement['videoPreviews'] = explode(';', trim($placement['video'], ';'));
    $placement['pdfPreviews'] = explode(';', trim($placement['pdf'], ';'));
}
$skillsStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .preview-link {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .preview-link img {
            max-width: 100px; /* Adjust based on your preference */
            height: auto;
            border-radius: 5px;
        }
        .preview-link a {
            display: inline-block;
            vertical-align: top;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Placement Details</h2>
    <?php foreach ($placements as $placement): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($placement['companyName']) ?></h5>
            <p class="card-text"><strong>College Code:</strong> <?= htmlspecialchars($placement['collegeCode']) ?></p>
            <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($placement['description']) ?></p>
            <p class="card-text"><strong>Requirement:</strong> <?= htmlspecialchars($placement['requirement']) ?></p>
            <p class="card-text"><strong>Drive Dates:</strong> <?= htmlspecialchars($placement['start_date']) ?> to <?= htmlspecialchars($placement['end_date']) ?></p>
            <p class="card-text"><strong>Requirement Skill(s):</strong> <?= htmlspecialchars($placement['distinctSkills']) ?></p>
            <p class="card-text"><strong>Company Website:</strong> <a href="<?= htmlspecialchars($placement['uploadLinks']) ?>" target="_blank"><?= htmlspecialchars($placement['uploadLinks']) ?></a></p>
            <!-- Display video previews -->
            <?php if (!empty($placement['video'])): ?>
            <div><strong>Videos:</strong></div>
            <div class="d-flex flex-wrap">
                <!-- Assuming videos are provided as semicolon-separated URLs -->
                <?php $videos = explode(';', trim($placement['video'], ';'));
                foreach ($videos as $video): if (!empty($video)): ?>
                <div class="preview-link">
                    <!-- Placeholder for video preview; adjust as needed -->
                    <a href="<?= htmlspecialchars($video) ?>" target="_blank">View Video</a>
                </div>
                <?php endif; endforeach; ?>
            </div>
            <?php endif; ?>
            <!-- Display PDF previews -->
            <?php if (!empty($placement['pdf'])): ?>
            <div><strong>PDFs:</strong></div>
            <div class="d-flex flex-wrap">
                <!-- Assuming PDFs are provided as semicolon-separated URLs -->
                <?php $pdfs = explode(';', trim($placement['pdf'], ';'));
                foreach ($pdfs as $pdf): if (!empty($pdf)): ?>
                <div class="preview-link">
                    <!-- Placeholder for PDF preview; adjust as needed -->
                    <a href="<?= htmlspecialchars($pdf) ?>" target="_blank">View PDF</a>
                </div>
                <?php endif; endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
