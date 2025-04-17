<?php
// This script assumes you have passed companyName and clg_code through the URL as GET parameters.
include 'sql_conn.php';
session_start();
$reg_num = $_SESSION['reg_num'];
$companyName = $_GET['companyName'] ?? '';
$clg_code = $_GET['clg_code'] ?? '';
$query = "SELECT DISTINCT requirementSkill, video, pdf FROM placements WHERE companyName = ? AND collegeCode = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $companyName, $clg_code);
$stmt->execute();
$details = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Placement Details: <?= htmlspecialchars($companyName) ?></h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Skill</th>
                <th scope="col">Skill Related Documents</th>
                <th scope="col">Skill Related Videos</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $details->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['requirementSkill']) ?></td>
                <td>
                    <?php if($row['pdf']): ?>
                        <a href="<?= htmlspecialchars($row['pdf']) ?>" target="_blank">View PDF</a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($row['video']): ?>
                        <a href="<?= htmlspecialchars($row['video']) ?>" target="_blank">Watch Video</a>
                    <?php endif; ?>
                </td>
                <td>
                    <form action="skill_exam.php" method="post">
                        <input type="hidden" name="skill" value="<?= htmlspecialchars($row['requirementSkill']) ?>">
                        <input type="hidden" name="reg_num" value="<?= htmlspecialchars($reg_num) ?>">
                        <button type="submit" class="btn btn-primary">Take Exam</button>
                    </form>
                </td>

            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
