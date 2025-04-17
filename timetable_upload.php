<?php
session_start();
$class_id = $_SESSION['class_id'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Timetable Upload</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
            <h2>Timetable Upload</h2>
            <button type="button" onclick="location.href='timetable_update.php?class_id=<?php echo $class_id; ?>'" class="btn btn-secondary">Update Timetable</button>
            <form id="timetableForm">
                <div class="form-group">
                    <input type="hidden" class="form-control" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
                </div>
                <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach ($days as $index => $day) :
                ?>
                    <div id="<?php echo $day; ?>" class="day" style="<?php echo $index > 0 ? 'display:none;' : '' ?>">
                        <h3><center><?php echo $day; ?></center></h3>
                        <?php for ($period = 1; $period <= 7; $period++) : ?>
                            <div class="card" id="table-container">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Period <?php echo $period; ?> Subject:</label>
                                        <input type="text" class="form-control" name="subject_<?php echo $day; ?>[]" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Faculty ID:</label>
                                        <input type="text" class="form-control" name="faculty_id_<?php echo $day; ?>[]" required>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                        <div class="mt-3"></div>
                        <button type="button" class="btn btn-primary nextDay" data-current-day="<?php echo $day; ?>" data-next-day="<?php echo $days[$index + 1] ?? ''; ?>">Next Day</button>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.nextDay').click(function() {
                var currentDay = $(this).data('current-day');
                var nextDay = $(this).data('next-day');
                var data = $('#timetableForm').serializeArray();

                // Filter out data related to the current day only
                data = data.filter(function(item) {
                    return item.name.indexOf(currentDay) !== -1 || item.name === 'class_id';
                });

                $.ajax({
                    url: 'timetable_upload_process.php',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        alert(response); // Or handle the response as needed
                        $('#' + nextDay).show();
                        $('#' + currentDay).hide();
                    },
                    error: function() {
                        alert('Error while saving data');
                    }
                });
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>