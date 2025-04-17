<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style> 
        .icon-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .icon-button {
            text-align: center;
            cursor: pointer;
        }

        .icon-button img {
            width: 100px;
            height: 100px;
        }

        .icon-button span {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Your existing content -->
    <div class="icon-buttons">
        <form class="icon-button" action="faculty_auth.php" method="post">
            <button class="icon-button" type="submit"><img src="add (1).png" alt="Icon 2" class="img-fluid"></button>
            <span>Add CLV</span>
        </form>

        <form class="icon-button" action="faculty_auth_library.php" method="post">
            <button class="icon-button" type="submit"><img src="lib.png" alt="Icon 1" class="img-fluid"></button>
            <span>ADD library</span>
        </form>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
