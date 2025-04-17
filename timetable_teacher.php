<?php
// Include the SQL connection file
include 'sql_conn.php';
session_start();
// Check if class_id is set in POST
$class_id = $_SESSION['class_id'];
// SQL to get distinct days from the timetable for the given class_id
$daysQuery = "SELECT DISTINCT day FROM timetable WHERE class_id = ? ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";
$daysStmt = $conn->prepare($daysQuery);
$daysStmt->bind_param("s", $class_id);
$daysStmt->execute();
$daysResult = $daysStmt->get_result();

// SQL to get faculty data and distinct subjects
$facultyQuery = "SELECT DISTINCT f.facultyName, f.whatsappNumber, f.contactEmail, t.subject 
                     FROM master_faculty f
                     INNER JOIN timetable t ON f.faculty_id = t.faculty_id 
                     WHERE t.class_id = ?";
$facultyStmt = $conn->prepare($facultyQuery);
$facultyStmt->bind_param("s", $class_id);
$facultyStmt->execute();
$facultyResult = $facultyStmt->get_result();

// Start HTML output
echo '<!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <title>Class Timetable</title>
              <!-- Bootstrap CSS -->
              <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
              <style>
          body {
              font-family: Arial, sans-serif;
              background-image: url("tt.jpg"); /* Update with the actual path */
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
              background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
              border-radius: 10px; /* Rounded corners for the table container */
              padding: 20px; /* Padding around the table */
              box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Enhanced shadow for a deeper frame effect */
              margin-top: 20px; /* Margin to distance from top */
          }

          .icon-button {
              position: fixed;
              top: 20px;
              right: 20px;
              z-index: 100;
          }

          table {
              background: transparent; /* Transparent background for the table */
          }

          th, td {
              color: #333; /* Dark text for readability */
          }

          .table-bordered th, .table-bordered td {
              border: 1px solid #dee2e6; 
          }

          .table thead th {
              background-color: rgba(0, 123, 255, 0.7); /* More opaque blue for header background */
              color: white; /* White text color for headers */
          }
      </style>
              
          </head>
          <body>
          <div class="container">
          <div class="table-container">
          <a href="timetable_upload.php?class_id=' . urlencode($class_id) . '" class="icon-button">
          <img src="add-image.png" alt="Icon" style="width: 50px; height: 50px;">
          </a>';

// Check if there is any timetable data
if ($daysResult->num_rows > 0 || $facultyResult->num_rows > 0) {
    // Display the Timetable (Table 1)
    echo "<h2>Timetable</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Day</th>";
    for ($i = 1; $i <= 7; $i++) {
        echo "<th>Period $i</th>";
    }
    echo "</tr></thead>";

    while ($day = $daysResult->fetch_assoc()) {
        echo "<tr><td>" . $day['day'] . "</td>";
        for ($i = 1; $i <= 7; $i++) {
            // Fetch the subject and faculty_id for each period
            $periodQuery = "SELECT DISTINCT subject, faculty_id FROM timetable WHERE class_id = ? AND day = ? AND period = ?";
            $periodStmt = $conn->prepare($periodQuery);
            $periodStmt->bind_param("ssi", $class_id, $day['day'], $i);
            $periodStmt->execute();
            $periodResult = $periodStmt->get_result();
            $periodData = $periodResult->fetch_assoc();
            echo "<td>" . (isset($periodData['subject']) ? $periodData['subject'] : "") . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";

    // Display Faculty Information (Table 2)
    echo '<div class="table-container">';
    echo "<h2>Faculty Information</h2>";
    echo "<table class='table table-bordered'><thead><tr><th>Subject</th><th>Faculty Name</th><th>Contact Number</th><th>Email</th></tr></thead>";
    while ($faculty = $facultyResult->fetch_assoc()) {
        echo "<tr><td>" . $faculty['subject'] . "</td><td>" . $faculty['facultyName'] . "</td><td>" . $faculty['whatsappNumber'] . "</td><td>" . $faculty['contactEmail'] . "</td></tr>";
    }
    echo "</table>";
    echo "</div>";
    // Close statements
    $daysStmt->close();
    $facultyStmt->close();
    $periodStmt->close();
} else {
    echo "<script>alert('No timetable is set');</script>";
}


echo '</div></body></html>';
$conn->close();
