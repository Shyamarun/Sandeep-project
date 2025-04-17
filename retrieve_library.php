<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('library_back.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .filter-form,
        .search-form {
            margin-bottom: 20px;
        }

        .card {
            width: 100%;
            border-radius: 15px;
            border: solid 1px;
            overflow: hidden;
            transition: transform 0.3s;
            position: relative;
            display: flex;
            flex-direction: row;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card img {
            width: 25%;
            height: 100%;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
            object-fit: cover;
            border-right: 2px solid #ddd;
        }

        .card-content {
            padding: 10px;
            box-sizing: border-box;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-content h5 {
            margin-bottom: 5px;
        }

        .card-content p {
            margin-bottom: 10px;
            flex-grow: 1;
        }

        .btn-view-book {
            margin-top: auto;
            display: block;
        }

        .custom-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }

        .custom-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .custom-table th {
            background-color: #007bff;
            color: white;
        }

        .custom-table img {
            width: 100px;
            /* Adjust based on your needs */
            height: auto;
            border-radius: 5px;
            /* Optional for rounded corners */
        }

        .head {
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
    </style>
</head>

<body>
    <div class="container">
        <div class="head">
            <form class="search-form" action="" method="post">
                <div class="form-group">
                    <label for="search">Search:</label>
                    <input type="text" class="form-control" id="search" name="search">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <form class="filter-form" action="" method="post">
                <div class="form-group">
                    <label for="category">Select Category:</label>
                    <select class="form-control" id="category" name="category" onchange="changeCategory()">
                        <option value="">Select Category</option>
                        <option value="universal">Universal Library</option>
                        <option value="external">External Abilities</option>
                        <option value="class">Class Materials</option>
                        <option value="question">Question Papers</option>
                    </select>
                </div>
                <div id="subcategory" style="display: none;">
                    <div class="form-group">
                        <label for="course">Select Course:</label>
                        <select class="form-control" id="course" name="course" onchange="changeCourse()">
                            <option value="">Select Course</option>
                            <option value="btech">B.Tech</option>
                            <option value="degree">Degree</option>
                            <option value="diploma">Diploma</option>
                            <option value="pharmacy">Pharmacy</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </div>
        <!-- Display Records in Cards -->
        <div class="row">
            <?php
            include 'sql_conn.php';
            $category = isset($_POST["category"]) ? $_POST["category"] : '';
            $course = isset($_POST["course"]) ? $_POST["course"] : '';
            // Check if the search form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["search"])) {
                    $search = '%' . $_POST["search"] . '%';
                    $searchQuery = "SELECT * FROM library WHERE (book_name LIKE ? OR description LIKE ?) AND category != 'question'";
                    $stmt = $conn->prepare($searchQuery);
                    $stmt->bind_param("ss", $search, $search);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $query = "SELECT * FROM library WHERE category = ?";
                    if ($category !== 'universal' && $category !== 'external') {
                        $query .= " AND course = ?";
                    }

                    $stmt = $conn->prepare($query);

                    if ($category !== 'universal' && $category !== 'external') {
                        $stmt->bind_param("ss", $category, $course);
                    } else {
                        $stmt->bind_param("s", $category);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();
                }

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    if ($category !== 'question') {
                        echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="Book Image">';
                    }
                    echo '<div class="card-content">
                            <h5 class="card-title">';
                    if ($category === 'question') {
                        echo 'Subject Name:';
                    } else {
                        echo 'Title:';
                    }
                    echo ' ' . $row['book_name'] . '</h5>
                            <p class="card-text">';
                    if ($category === 'question') {
                        echo 'Question Paper Description:';
                    } else {
                        echo 'Description:';
                    }
                    echo ' ' . $row['description'] . '</p>
                            <a href="' . $row['file_path'] . '" class="btn btn-primary btn-view-book" target="_blank">';
                    if ($category === 'question') {
                        echo 'View Question Paper';
                    } else {
                        echo 'View Full Book';
                    }
                    echo '</a>
                        </div>
                    </div><br>';
                }

                $stmt->close();
            } else {
                // Show all records from the library table except category != 'question'
                $query = "SELECT * FROM library WHERE category != 'question'";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="Book Image">';
                    echo '<div class="card-content">
                            <h5 class="card-title">';
                    echo 'Title:';
                    echo ' ' . $row['book_name'] . '</h5>
                            <p class="card-text">';
                    echo 'Description:';
                    echo ' ' . $row['description'] . '</p>
                            <a href="' . $row['file_path'] . '" class="btn btn-primary btn-view-book" target="_blank">';
                    echo 'View Full Book';
                    echo '</a>
                        </div>
                    </div><br>';
                }
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function changeCategory() {
            var category = document.getElementById("category").value;
            if (category === "universal" || category === "external") {
                document.getElementById("subcategory").style.display = "none";
            } else {
                document.getElementById("subcategory").style.display = "block";
            }
        }
    </script>
</body>

</html>