<?php
session_start();
// Fetch class_id from session variable if it's set there
if (isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id']; // Access the session variable
    // You can now use $class_id as needed
}

include 'sql_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add these links in the head section -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpage with Sliding Text Bars</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('h.jpg');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Set fixed height for slides and curved borders */
        .carousel-inner .carousel-item {
            height: 200px;
            /* Fixed height for the slides */
            border-radius: 15px;
            /* Curved borders for the slides */
            overflow: hidden;
            /* Ensures content fits within the border radius */
        }

        /* Adjust the icons grid layout */
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

        .navbar.fixed-bottom {
            border-radius: 15px;
            /* Curved edges for the navbar */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            margin: 10px 15px;
            /* Adjust margin to ensure the navbar does not stretch fully across */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for depth */
            overflow: hidden;
            /* Ensures content fits within the border radius */
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .icon-button img {
                width: 50px;
                height: 50px;
            }

            .icon-buttons {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Dynamic Carousel for Slide Cards -->
        <div id="slideCardCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                // Query to fetch slides data
                $sql = "SELECT id, title, content, image_path FROM slides"; // Fetch the image_path

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $firstSlide = true;
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="carousel-item ' . ($firstSlide ? 'active' : '') . '">';
                        echo '<div class="card">';
                        // Use the correct column name for the image source
                        echo '<img class="card-img-top" src="' . $row['image_path'] . '" alt="Slide ' . $row['id'] . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                        echo '<p class="card-text">' . $row['content'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        $firstSlide = false;
                    }
                } else {
                    echo '<div class="carousel-item active">';
                    echo '<p>No slides found.</p>';
                    echo '</div>';
                }
                ?>
            </div>
            <!-- Carousel Controls -->
            <a class="carousel-control-prev" href="#slideCardCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#slideCardCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>


        <div class="row justify-content-center mt-4"></div>

        <div class="icon-buttons">
            <!-- Icon Buttons Group 1 -->
            <!--form class="icon-button" action="attd_home.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="roll-call.png" alt="Icon 1" class="img-fluid"></button>
                <span>Attendance</span>
            </form-->
            <form class="icon-button" action="results_teacher.php" method="post">
                <!-- Add your input fields or other form elements here -->
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="results.png" alt="Icon 2" class="img-fluid"></button>
                <span>Results</span>

            </form>

            <form class="icon-button" action="display_certificates.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="certificate.png" alt="Icon 1" class="img-fluid"></button>
                <span>Ceritficate</span>
            </form>

            <!-- Icon Buttons Group 2 -->
            <form class="icon-button" action="meeting_schedule_home.php?class_id=<?php echo htmlspecialchars($class_id); ?>" method="get">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="meeting.png" alt="Icon 2" class="img-fluid"></button>
                <span>Meeting</span>
            </form>
            <form class="icon-button" action="display_std_fee.php?class_id=<?php echo htmlspecialchars($class_id); ?>" method="get">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="fee (1).png" alt="Icon 1" class="img-fluid"></button>
                <span>Fee</span>
            </form>
            <form class="icon-button" action="timetable_teacher.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="schedule (1).png" width="30" height="30" alt="Icon 1" class="img-fluid">
                </button>
                <span>Timetable</span>
            </form>
            <form class="icon-button" action="placement_teacher.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="placement.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                </button>
                <span>Placements</span>
            </form>
            </form>
            <form class="icon-button" action="display_parent_info.php?class_id=<?php echo htmlspecialchars($class_id); ?>" method="get">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="contacts.png" alt="Icon 1" class="img-fluid"></button>
                <span>Contacts</span>
            </form>
            <form class="icon-button" action="promote_home.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="employee.png" alt="Icon 1" class="img-fluid"></button>
                <span>Promote</span>
            </form>
            <form class="icon-button" action="permission_class_incharge.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="permission.png" alt="Icon 1" class="img-fluid"></button>
                <span>Permission</span>
            </form>
            <form class="icon-button" action="display_parent_feedback.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="feedback.png" alt="Icon 1" class="img-fluid"></button>
                <span>Feedbacks</span>
            </form>
            <form class="icon-button" action="master_sub_upload.php" method="post">
                <button type="submit" style="border: none; background: none;">
                    <img src="books.png" alt="Icon 1" class="img-fluid"></button>
                <span>subjects</span>
            </form>
            <!--form class="icon-button" action="timetable_upload.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="schedule (1).png" alt="Icon 2" class="img-fluid"></button>
                <span>Set Time table</span>
            </form-->
            <!-- Include more icon buttons as needed -->
        </div>
        <nav class="navbar fixed-bottom navbar-light bg-light">
            <a href="#" onclick="openStudentIdPopup('<?php echo $_SESSION['faculty_id']; ?>')" class="navbar-brand">
                <img src="user.png" width="30" height="30" alt="Profile Icon">
            </a>
            <a href="thoughts_home_student.php?reg_num=<?php echo $_SESSION['faculty_id']; ?> " class="navbar-brand">
                <img src="youtube.png" width="30" height="30" alt="Video Icon">
            </a>

            <a href="logout.php" class="navbar-brand">
                <img src="logout.png" width="30" height="30" alt="Profile Icon">
            </a>
        </nav>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="slides_display.php"></script>

    <script>
        function openPage(url) {
            window.location.href = url;
        }
    </script>
    <script type="text/javascript">
        // When the window is loaded, replace the current history item
        window.onload = function() {
            window.history.replaceState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.go(1);
            };
        };
    </script>
</body>

</html>