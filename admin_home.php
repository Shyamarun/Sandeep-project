<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login_page.php"); // Redirect to login if not logged in
    exit();
}

$userID = isset($_SESSION['user_data']['userID']) ? $_SESSION['user_data']['userID'] : null;
// MySQL database connection
include 'sql_conn.php';
$_SESSION['user_id'] = $userID;
// Query the admin_details table
$staffDetailsQuery = "SELECT collegeCode, staffType, designation FROM admin_details WHERE userID = ?";
$staffDetailsStmt = $conn->prepare($staffDetailsQuery);
$staffDetailsStmt->bind_param("s", $userID);
$staffDetailsStmt->execute();
$staffDetailsResult = $staffDetailsStmt->get_result();

$collegeCode = '';
$staffType = '';
$designation = '';

if ($staffDetailsResult->num_rows > 0) {
    $staffDetailsRow = $staffDetailsResult->fetch_assoc();
    $_SESSION['collegeCode'] = $staffDetailsRow['collegeCode'];
    $staffType = strtoupper(str_replace(' ', '', $staffDetailsRow['staffType'])); // Remove spaces and uppercase
    $designation = strtoupper(str_replace(' ', '', $staffDetailsRow['designation'])); // Remove spaces and uppercase
}

$staffDetailsStmt->close();

// Redirect based on staffType and designation
if ($staffType === 'ADMINISTRATIONSTAFF' && strpos($designation, 'FEE') !== false) {
    // Redirect to fee.php and post userID
    header("Location: fee.php?userID=" . urlencode($_SESSION['user_id']));
    exit();
}elseif ($staffType === 'ADMINISTRATIONSTAFF' && strpos($designation, 'PLACEMENT') !== false){
    header("Location: placement_cell_home.php?userID=" . urlencode($_SESSION['user_id']));
    exit();
}
// If the conditions are not met, the script will stay on admin_home.php
$collegeCode = $_SESSION['collegeCode'];
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
                <input type="hidden" name="class_id" value="<?php echo $collegeCode; ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="roll-call.png" alt="Icon 1" class="img-fluid"></button>
                <span>Attendance</span>
            </form-->
            <form class="icon-button" action="import_sub_data.php" method="post">
                <!-- Add your input fields or other form elements here -->
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($collegeCode); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="results.png" alt="Icon 2" class="img-fluid"></button>
                <span>Subject Data</span>

            </form>

            <form class="icon-button" action="placements_upload.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($collegeCode); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="certificate.png" alt="Icon 1" class="img-fluid"></button>
                <span>Placements Uploads</span>
            </form>

            <!-- Icon Buttons Group 2 -->
            <form class="icon-button" action="import_std_data.php" method="get">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($collegeCode); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="meeting.png" alt="Icon 2" class="img-fluid"></button>
                <span>Student Data</span>
            </form>
            <form class="icon-button" action="update_slides.php" method="get">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($collegeCode); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="fee (1).png" alt="Icon 1" class="img-fluid"></button>
                <span>Update Slides</span>
            </form>
            <form class="icon-button" action="admin_class_id.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo $collegeCode; ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="schedule (1).png" width="30" height="30" alt="Icon 1" class="img-fluid">
                </button>
                <span>Admin Class ID</span>
            </form>
            <form class="icon-button" action="upload_external_abilities.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo $collegeCode; ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="placement.png" width="30" height="30" alt="Icon 2" class="img-fluid">
                </button>
                <span>External Abilities</span>
            </form>

            <!--form class="icon-button" action="automatic_table_creation.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($collegeCode); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="employee.png" alt="Icon 1" class="img-fluid"></button>
                <span>Table Creation</span>
            </form>

            <form class="icon-button" action="delete_all_data.php" method="post">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($collegeCode); ?>">
                <button type="submit" style="border: none; background: none;">
                    <img src="feedback.png" alt="Icon 1" class="img-fluid"></button>
                <span>Delete All Data</span>
            </form>
            <form class="icon-button" action="drop_results_table.php" method="post">
                <button type="submit" style="border: none; background: none;">
                    <img src="books.png" alt="Icon 1" class="img-fluid"></button>
                <span>Delete Database</span>
            </form-->
        </div>
        <nav class="navbar fixed-bottom navbar-light bg-light">
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