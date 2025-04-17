<?php
session_start();
$class_id = $_SESSION['class_id'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Timetable Update</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("tt.jpg");
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .icon-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            justify-content: center;
            align-items: center;
            padding: 0;
            margin-top: 20px;
            /* Reduced top margin to bring closer to slides */
        }

        .icon-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            border-radius: 15px;
            /* Curved edges for the frame */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background for the frame */
            padding: 10px;
            /* Adjust padding to your liking */
            margin: 5px;
            /* Space between icon frames */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for depth */
        }

        .icon-button img {
            width: 60px;
            height: 60px;
            margin-bottom: 5px;
            border-radius: 5%;
            /* Soften the edges of the images if desired */
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .icon-button img:hover {
            transform: scale(1.1);
            /* Optional: Scale up icons on hover for a nice effect */
        }

        .icon-button span {
            font-size: 0.9rem;
            color: #333;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
        }

        #table-container {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
            /* Margin to distance from top */
        }

        .icon-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
        }

        table {
            background: transparent;
            /* Transparent background for the table */
        }

        th,
        td {
            color: #333;
            /* Dark text for readability */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.7);
            /* More opaque blue for header background */
            color: white;
            /* White text color for headers */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="table-container">
            <h2>Update Timetable</h2>
            <form action="timetable_update_process.php" method="post">
                <div class="form-group">
                    <input type="hidden" class="form-control" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
                </div>
                <div class="form-group">
                    <label for="day">Day:</label>
                    <select class="form-control" name="day" id="day" required>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="period">Period:</label>
                    <input type="number" class="form-control" name="period" id="period" min="1" max="7" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject Name:</label>
                    <input type="text" class="form-control" name="subject" id="subject" required>
                </div>
                <div class="form-group">
                    <label for="faculty_id">Faculty ID:</label>
                    <input type="text" class="form-control" name="faculty_id" id="faculty_id" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>