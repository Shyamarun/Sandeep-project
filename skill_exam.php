<?php
// Assume skill and reg_num are passed via POST
include 'sql_conn.php';

$skill = $_POST['skill'] ?? '';
$reg_num = $_POST['reg_num'] ?? '';

$query = "SELECT id, question, option1, option2, option3, option4, option5 FROM placement_questions WHERE skill = ? ORDER BY RAND()";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $skill);
$stmt->execute();
$questions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Exam</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Skill Exam: <?= htmlspecialchars($skill) ?></h2>
    <form action="process_exam.php" method="POST">
        <?php while($question = $questions->fetch_assoc()): ?>
            <div class="mb-4">
                <p><?= htmlspecialchars($question['question']) ?></p>
                <?php
                // Output options directly as values
                foreach (['option1', 'option2', 'option3', 'option4', 'option5'] as $optKey => $option): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" id="option<?= $optKey+1 ?>" value="<?= htmlspecialchars($question[$option]) ?>">
                        <label class="form-check-label" for="option<?= $optKey+1 ?>">
                            <?= htmlspecialchars($question[$option]) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endwhile; ?>
        <input type="hidden" name="reg_num" value="<?= htmlspecialchars($reg_num) ?>">
        <input type="hidden" name="skill" value="<?= htmlspecialchars($skill) ?>">
        <button type="submit" class="btn btn-primary">Submit Exam</button>
    </form>
</div>
</body>
</html>
