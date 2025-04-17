<?php 
    session_start();
    if(!isset($_SESSION['collegeCode'])){
        header('Location: login_page.php');
        exit(); // Ensure no further execution for unauthorized access
    }
    include 'sql_conn.php';
    $collegeCode = $_SESSION['collegeCode'];

    // Query to fetch distinct skills
    $skillsQuery = "SELECT DISTINCT requirementSkill FROM placements";
    $result = mysqli_query($conn, $skillsQuery);
    $skills = [];
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $skills[] = $row['requirementSkill'];
        }
    }
    sort($skills);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
    <div class="container mt-5">
        <form method="post" action="set_req_qp_process.php">
            <input type="hidden" name="collegeCode" value="<?php echo $_SESSION['collegeCode']; ?>">
            <input type="hidden" name="companyName">
            <input type="hidden" name="requirementSkill">
            <label for="skill">Select Skill</label>
            <select name="skill" id="skill" class="form-control mb-2">
                <?php foreach ($skills as $skill): ?>
                    <option value="<?php echo htmlspecialchars($skill); ?>"><?php echo htmlspecialchars($skill); ?></option>
                <?php endforeach; ?>
            </select>
            <div id="questions-container">
                <!-- Initial question input fields -->
                <div class="question-container">
                    <label for="question">Question</label>
                    <input type="text" name="question[]" class="question form-control mb-2">
                    <input type="text" name="option1[]" placeholder="Enter option 1" class="option form-control mb-2">
                    <input type="text" name="option2[]" placeholder="Enter option 2" class="option form-control mb-2">
                    <input type="text" name="option3[]" placeholder="Enter option 3" class="option form-control mb-2">
                    <input type="text" name="option4[]" placeholder="Enter option 4" class="option form-control mb-2">
                    <input type="text" name="option5[]" placeholder="Enter option 5" class="option form-control mb-2">
                    <input type="text" name="correct[]" placeholder="Enter correct option" class="correct form-control mb-2">
                </div>
            </div>
            <div class="mt-3"></div>
            <button type="button" class="btn btn-success" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-primary">Post Questions</button>
        </form>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
<script>
        function addQuestion() {
            let questionsContainer = document.getElementById('questions-container');

            let questionContainer = document.createElement('div');
            questionContainer.className = 'question-container';

            questionContainer.innerHTML = `
                <label for="question">Question</label>
                <input type="text" name="question[]" class="question form-control mb-2">
                <input type="text" name="option1[]" placeholder="Enter option 1" class="option form-control mb-2">
                <input type="text" name="option2[]" placeholder="Enter option 2" class="option form-control mb-2">
                <input type="text" name="option3[]" placeholder="Enter option 3" class="option form-control mb-2">
                <input type="text" name="option4[]" placeholder="Enter option 4" class="option form-control mb-2">
                <input type="text" name="option5[]" placeholder="Enter option 5" class="option form-control mb-2">
                <input type="text" name="correct[]" placeholder="Enter correct option" class="correct form-control mb-2">
            `;

            questionsContainer.appendChild(questionContainer);
        }
    </script>
</html>