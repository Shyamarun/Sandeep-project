<?php 
session_start();
$collegeCode = $_SESSION['collegeCode'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="question_paper.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <a href="set_rq_qp.php" class="btn btn-primary post-exam" role="button">Post exam for required skills</a>
        </div>
    </div>
    <form action="placement_upload_process.php" method="post" enctype="multipart/form-data">
        <!-- Add your form fields for notifications here -->
        <input type="hidden" name="collegeCode" value="<?php echo $_SESSION['collegeCode']; ?>">
        <div class="form-group">
            <label for="companyName">Company Name</label>
            <input type="text" class="form-control" name="companyName" required>
        </div>
        <div class="form-group">
            <label for="companyName">Expected Drive Dates</label>
            <input type="date" name="start_date" class="form-control" placeholder="Expected starting date">
            <input type="date" name="end_date" class="form-control" placeholder="Expected ending date">
        </div>
        <div class="form-group">
            <label for="description">Company description</label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="uploadLinks">Company website link</label>
            <input type="text" class="form-control" name="uploadLinks" required>
        </div>
        
        <div class="form-group">
            <label for="requirement">Company requirement</label>
            <textarea class="form-control" name="requirement" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="images">Company images</label>
            <input type="file" class="form-control-file" name="images" accept=".png,.jpg,.jpeg">
        </div>
        <div class="form-group">
            <label for="documents">Company notification documents</label>
            <input type="file" class="form-control-file" name="documents" accept=".pdf">
        </div>
        <div class="mt-3"></div>
        <div class="btns">
            <button type="button" class="btn btn-primary toggle-requirements">Add requirement</button>
            <div class="mt-3"></div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".toggle-requirements").addEventListener("click", function () {
        var newRequirement = document.createElement("div");
        newRequirement.innerHTML = `
            <div class="form-group">
                <label for="requirementSkill">Required Skill</label>
                <input type="text" class="form-control" name="requirementSkill[]" required>
            </div>
            <div class="form-group">
                <label for="video">Skill Related Videos</label>
                <input type="file" class="form-control-file" name="video[]" accept="video/mp4" required>
            </div>
            <div class="form-group">
                <label for="pdf">Skill Related Documents</label>
                <input type="file" class="form-control-file" name="pdf[]" accept=".pdf" required>
            </div>
        `;
        // We need to select the form as the parent element to correctly insert the newRequirement before .btns
        var form = document.querySelector("form"); // Assuming there's only one form, otherwise, use a more specific selector.
        var insertBeforeElement = form.querySelector(".btns"); // Selects the .btns div within the form

        // Correctly insert the newRequirement before the .btns div
        form.insertBefore(newRequirement, insertBeforeElement);
    });
});

</script>

</body>
</html>
