<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student ID Card</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            background: #f7f7f7;
        }

        .id-card {
            width: 300px;
            font-family: Arial, sans-serif;
            color: #000;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: auto;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .id-card .card-header {
            background-color: #FFFFFF;
            color: #B5161B;
            font-family: 'Times New Roman';
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .id-card .college-logo {
            max-width: 100%;
            /* Full width of the container */
            max-height: 100px;
            /* Maximum height */
            object-fit: contain;
            /* To prevent distortion */
            width: auto;
            /* Auto width to maintain aspect ratio */
            height: auto;
            /* Auto height to maintain aspect ratio */
        }

        .id-card img.student-photo {
            width: 80px;
            height: 100px;
            margin-top: 10px;
            border-radius: 2px;
            object-fit: cover;
        }

        .id-card .details {
            padding: 10px;
        }

        .id-card h9 {
            font-size: 18px;
            margin: 8px 0;
            text-align: center;
        }

        .id-card p {
            font-size: 12px;
            margin: 2px;
            text-align: center;
        }

        .id-card .card-footer {
            background-color: #f7f7f7;
            color: #333;
            font-size: 10px;
            text-align: center;
            padding: 8px;
            border-top: 1px solid #ddd;
            position: relative;
            bottom: 0;
            width: 100%;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        @media (max-width: 768px) {
            .id-card .college-logo {
                max-height: 40px;
                /* Smaller max-height for smaller screens */
            }

            .id-card img.student-photo {
                width: 60px;
                height: 80px;
            }
        }
    </style>
</head>

<body>
        <!-- ID Card will be generated by PHP here -->
        <?php
        include 'sql_conn.php';
        $faculty_id = $_GET['faculty_id'];
        // Fetch the student data
        $sql = "SELECT DISTINCT profile_photo, stream, facultyName, contactNumber FROM master_faculty WHERE faculty_id='$faculty_id'";
        $result = $conn->query($sql);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="id-card text-center">';
                echo '<div class="card-header">';
                echo '<img src="avev.jpg"  class="college-logo">';
                echo '</div>';
                echo '<img src="' . $row["profile_photo"] . '" alt="Faculty Photo" class="student-photo">';
                echo '<div class="details">';
                echo '<h9 class="mt-2">' . $row["facultyName"] . '</h9>';
                echo '<p><strong>stream:</strong> ' . $row["stream"] . '</p>';
                echo '<p><strong>Contact:</strong> ' . $row["contactNumber"] . '</p>';
                echo '</div>';
                echo '<div class="barcode"></div>';
                echo '<div class="card-footer">';
                echo 'Cherukupally (V), Near Tagarapuvalasa Bridge, <br>';
                echo 'Vizianagaram Dist., Andhra Pradesh. <br>';
                echo 'Ph no. 08922 245077, Cell: 7997903696';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-center">No student data found.</p>';
        }

        $conn->close();
        ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>