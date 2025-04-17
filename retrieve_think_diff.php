<?php
include 'sql_conn.php';
session_start();
$reg_num = $_SESSION['reg_num'];
$class_id = $_SESSION['class_id'];

// Retrieve data from the database
$sql = "SELECT * FROM file_uploads WHERE reg_num='$reg_num'";
$result = $conn->query($sql);

$projects = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store each project in an array
        $projects[] = array(
            "id" => $row["id"],
            "username" => $row["username"],
            "description" => $row["description"],
            "file_path" => $row["file_path"],
            "upload_timestamp" => $row["upload_timestamp"]
        );
    }
}
echo "<script>";
echo "const projects = " . json_encode($projects) . ";";
echo "</script>";
// Close the initial database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="retrieve_think_diff.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('clv.webp');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        #projects-container {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }

        #projects-container-projects {
            background: rgba(255, 255, 255, 20);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }

        #commentModal {
            background: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the table container */
            padding: 20px;
            /* Padding around the table */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Enhanced shadow for a deeper frame effect */
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <div class="container mt-5" id="projects-container">
        <h1 class="text-center mb-4">WHY NOT</h1>
        <div class="row" id="projects-container">
            <!-- Projects will be dynamically added here -->
        </div>

    </div>
    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="commentForm" method="post">
                        <!-- Add a hidden input field for reg_num -->
                        <input type="hidden" id="commentRegNum" name="reg_num" value="<?php echo $_SESSION['reg_num']; ?>">

                        <div class="form-group">
                            <label for="comment">Your Comment:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <input type="hidden" id="commentProjectId" name="commentProjectId">
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar">
        <ul class="nav justify-content-around">
            <li class="nav-item">
                <a class="nav-link" href="think_diff_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>&class_id=<?php echo $_SESSION['class_id']; ?>" class="btn btn-primary">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="upload_think_diff_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>&class_id=<?php echo $_SESSION['class_id']; ?>" class="btn btn-primary">Add</a>
            </li>

        </ul>
    </nav>

    <script src="retrieve_think_diff.js"></script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentProjectId']) && isset($_POST['comment'])) {
        include 'sql_conn.php';
        $reg_num = isset($_POST['reg_num']) ? $_POST['reg_num'] : '';
        $commentProjectId = $_POST['commentProjectId'];
        $commentDescription = $_POST['comment'];

        $insertCommentSql = "INSERT INTO think_diff_comments (reg_num, description, id) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($insertCommentSql);
        $stmt->bind_param("ssi", $reg_num, $commentDescription, $commentProjectId);

        if ($stmt->execute()) {
            echo "<script>alert('Comment added successfully!');window.location.href='retrieve_think_diff.php';</script>";
        } else {
            echo "<script>alert('Failed to add comment.');window.location.href='retrieve_think_diff.php';</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>

</html>