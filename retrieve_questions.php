<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Retrieval</title>
</head>

<body>

    <h2>Retrieve Questions</h2>

    <form action="retrieve_questions.php" method="get">
        <label for="projectID">Project ID:</label>
        <input type="text" name="projectID" required>

        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <button type="submit">Retrieve Questions</button>
    </form>

</body>

</html>