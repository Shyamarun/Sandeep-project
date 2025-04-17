<?php
// Set up error reporting
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This should be at the very top, before any output is sent to the browser
header('Content-Type: application/json');

// Function to extract and return the prefix
function getPrefixFromUserId($user_id) {
    if (preg_match('/^(.*?)(DIR|PRI|VPRI|AO)/', $user_id, $matches)) {
        return strtoupper($matches[1]); // Return the string before DIR, PRI, VPRI, or AO in uppercase
    }
    return false;
}

// Initialize an array to hold the response
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $prefix = getPrefixFromUserId($user_id);
    if ($prefix !== false) {
        // Include the database connection file
        include 'sql_conn.php';

        // Check if $conn is set and is an instance of MySQLi
        if (isset($conn) && $conn instanceof mysqli) {
            // Retrieve distinct clg_code and course
            $clg_courses = $conn->query("SELECT DISTINCT clg_code, course FROM stdreg");

            if ($clg_courses) {
                while ($row = $clg_courses->fetch_assoc()) {
                    $concatenated = strtoupper($row['clg_code'] . $row['course']);

                    if ($concatenated == $prefix) {
                        $courseUpper = strtoupper($row['course']);
                        $branches = $conn->query("SELECT DISTINCT branch FROM stdreg WHERE course = '$courseUpper'");

                        if ($branches) {
                            while ($branch_row = $branches->fetch_assoc()) {
                                $branch_code = strtoupper($prefix . $branch_row['branch']);
                                $branch_upper = strtoupper($branch_row['branch']);
                                // SQL query to get fees data
                                $sql = "SELECT 
                                            SUM(total_fee) AS total, 
                                            SUM(amount_paid) AS paid, 
                                            SUM(due_amount) AS due 
                                        FROM fees 
                                        WHERE class_id LIKE '$branch_code%'";

                                $fee_result = $conn->query($sql);
                                if ($fee_result && $fee_result->num_rows > 0) {
                                    $fee_data = $fee_result->fetch_assoc();
                                    $response[$branch_upper] = [
                                        'total' => $fee_data["total"] ?? 0,
                                        'paid' => $fee_data["paid"] ?? 0,
                                        'due' => $fee_data["due"] ?? 0
                                    ];
                                }
                            }
                        }
                    }
                }
            } else {
                $response['error'] = "Error fetching clg_code and course: " . $conn->error;
            }

            // Close the database connection
            $conn->close();
        } else {
            $response['error'] = "Database connection failed or sql_conn.php does not set \$conn.";
        }
    } else {
        $response['error'] = "Invalid User ID";
    }
} else {
    $response['error'] = "Invalid request method";
}

// Echo the response as JSON
echo json_encode($response);
?>
