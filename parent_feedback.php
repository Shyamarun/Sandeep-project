<?php
session_start();
$class_id = $_SESSION['class_id'];
$reg_num = $_SESSION['reg_num'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Parent Feedback Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-box {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            /* Adjusted for spacing */
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the project boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chat-message {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            /* Adjusted for spacing */
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the project boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('ch.jpg');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .project-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            /* Adjusted for spacing */
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the project boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Soft shadow for depth */
            transition: transform 0.2s;
            /* Smooth transform effect on hover */
        }

        .project-box:hover {
            transform: scale(1.05);
            /* Slightly scale up the project box on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            /* Increased shadow for a lifting effect */
        }
    </style>
</head>


<body>
    <div class="container mt-5">
        <h2>Parent Feedback Form</h2>
        <form action="parent_feedback_process.php" method="post" class="mt-4">
            <input type="hidden" id="class_id" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
            <input type="hidden" id="reg_num" name="reg_num" value="<?php echo htmlspecialchars($reg_num); ?>">

            <div class="form-group">
                <label for="feedback">Feedback:</label>
                <textarea id="feedback" name="feedback" rows="4" cols="50" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>