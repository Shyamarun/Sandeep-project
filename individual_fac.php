<?php
include 'sql_conn.php';
session_start();
$faculty_id = isset($_SESSION['user_data']['faculty_id']) ? $_SESSION['user_data']['faculty_id'] : null;
$_SESSION['faculty_id'] = $faculty_id;
$query = "SELECT class_id FROM class_incharges WHERE faculty_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $faculty_id); // Assuming faculty_id is an integer
$stmt->execute();
$result = $stmt->get_result();
$class_id = null;
if ($result->num_rows > 0) {
    // Fetch the class_id
    $row = $result->fetch_assoc();
    $class_id = $row['class_id'];
    $_SESSION['class_id'] = $class_id;
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('class.webp');
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

            <!-- Your existing content -->
            <div class="icon-buttons">
                <form class="icon-button" action="clv.php" method="post">
                    <input type="hidden" name="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="add (1).png" alt="Icon 2" class="img-fluid"></button>
                    <span>CLV</span>
                </form>

                <form class="icon-button" action="upload_files_library.php" method="post">
                    <input type="hidden" name="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="lib.png" alt="Icon 1" class="img-fluid"></button>
                    <span>Library</span>
                </form>

                <form class="icon-button" action="individual_faculty_attendance.php" method="post">
                    <input type="hidden" name="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="roll-call.png" alt="Icon 1" class="img-fluid"></button>
                    <span>Attendance</span>
                </form>
                <form class="icon-button" action="permission_ind_fac.php" method="post">
                    <input type="hidden" name="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <img src="permission.png" alt="Icon 1" class="img-fluid"></button>
                    <span>Permissions</span>
                </form>


                <?php if ($class_id !== null) : ?>
                    <form class="icon-button" action="teacher_home_page.php" method="post">
                        <input type="hidden" name="class_id" value="<?php echo $_SESSION['class_id']; ?>">
                        <button type="submit" style="border: none; background: none;">
                            <img src="inclass.webp" alt="Icon 1" class="img-fluid"></button>
                        <span>Incharge</span>
                    </form>
                <?php endif; ?>
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
            <div id="studentModal" class="modal">
                <div id="studentInfo" class="modal-content">
                    <!-- Content loaded from student_id_display.php will go here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
        function openStudentIdPopup(facultyID) {
            var url = "faculty_id_display.php?faculty_id=" + $_SESSION['faculty_id'];

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