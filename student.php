<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login_page.php"); // Redirect to login if not logged in
    exit();
}

// Assuming reg_num and class_id are stored in the session upon login
$reg_num = isset($_SESSION['user_data']['reg_num']) ? $_SESSION['user_data']['reg_num'] : null;
$class_id = isset($_SESSION['user_data']['class_id']) ? $_SESSION['user_data']['class_id'] : null;
$_SESSION['reg_num'] = $reg_num;
$_SESSION['class_id'] = $class_id;
include 'sql_conn.php';
?>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpage with Sliding Text Bars</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('std_home.webp');
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
    <div class="background-overlay"></div>
    <div class="content-wrapper">
        <!-- Dynamic Carousel for Slide Cards -->
        <div class="container mt-4">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
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
            <div class="icon-buttons">
                <form class="icon-button" action="attd_retrive.php" method="post">
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="roll-call.png" width="30" height="30" alt="Icon 1" class="img-fluid">
                    </button>
                    <span>Attendance</span>
                </form>
                <form class="icon-button" action="timetable_student.php" method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="schedule (1).png" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>Timetable</span>
                </form>

                <form class="icon-button" action="retrieve_library.php" method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="library (1).png" width="30" height="30" alt="Icon 1" class="img-fluid">
                    </button>
                    <span>Library</span>
                </form>

                <!-- Form Group 2 -->
                <form class="icon-button" action="std_display_results.php" method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="results.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>Results</span>
                </form>

                <form class="icon-button" action="clv_display_student.php" method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="teacher (1).png" width="30" height="30" alt="Icon 1" class="img-fluid">
                    </button>
                    <span>CLV</span>
                </form>

                <form class="icon-button" action="retrieve_think_diff.php" method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="thinking.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>Think</span>
                </form>

                <!-- Form Group 3 -->
                <form class="icon-button" action="club_home.php" method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="book-club.png" width="30" height="30" alt="Icon 1" class="img-fluid">

                    </button>
                    <span>Innovate</span>
                </form>

                <form class="icon-button" action='view_certificate_std.php' method="post">
                    <!-- Add your input fields or other form elements here -->
                    <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="certificate.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>Certificates</span>
                </form>
                <form class="icon-button" action='placement_skill_student.php' method="post">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="qwe.jpg" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>technical</span>
                </form>
                <form class="icon-button" action='placements_student.php' method="post">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="placement.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>Placements</span>
                </form>

                <form class="icon-button" action="display_external_abilities.php" method="post">
                    <button type="submit" style="border: none; background: none;">
                        <img src="idea.png" width="30" height="30" alt="Icon 1" class="img-fluid">
                    </button>
                    <span>Skills</span>
                </form>
                <form class="icon-button" action='permission_student.php' method="post">
                    <input type="hidden" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="permission.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                    </button>
                    <span>Permissions</span>
                </form>


                <!-- Include more icon buttons as needed -->
            </div>
        </div>

    </div>

    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a href="student_id_display.php?reg_num=<?php echo $_SESSION['reg_num']; ?>" class="navbar-brand">
            <img src="user.png" width="30" height="30" alt="Profile Icon">
        </a>
        <a href="thoughts_home_student.php?reg_num=<?php echo $_SESSION['reg_num']; ?> " class="navbar-brand">
            <img src="youtube.png" width="30" height="30" alt="Video Icon">
        </a>
        <a href="update_std_pass.php?reg_num=<?php echo $_SESSION['reg_num']; ?>" class="navbar-brand">
            <img src="reset-password.png" width="30" height="30" alt="Video Icon">
        </a>
        <a href="logout.php" class="navbar-brand">
            <img src="logout.png" width="30" height="30" alt="Profile Icon">
        </a>
    </nav>
    </div>
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <!-- Content loaded from student_id_display.php will go here -->
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        // When the window is loaded, replace the current history item
        window.onload = function() {
            window.history.replaceState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.go(1);
            };
        };
    </script>
    <script>
        function openStudentIdPopup(regNum) {
            var url = "student_id_display.php?reg_num=" + $_SESSION['reg_num'];

            // AJAX call to load the content
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("studentInfo").innerHTML = this.responseText + '<button class="close-btn" onclick="closeModal()">Close</button>';
                }
            };
            xhr.open("GET", url, true);
            xhr.send();

            // Display the modal
            var modal = document.getElementById("studentModal");
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("studentModal");
            modal.style.display = "none";
        }

        // Close Modal when clicking outside of it
        window.onclick = function(event) {
            var modal = document.getElementById("studentModal");
            if (event.target == modal)
                modal.style.display = "none";
        }
    </script>
</body>

</html>