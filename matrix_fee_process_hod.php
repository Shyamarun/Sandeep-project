<?php
// Set up error reporting
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This should be at the very top, before any output is sent to the browser
header('Content-Type: application/json');

function getPrefixFromUserId($user_id)
{
    if (preg_match('/^(.*?)HOD/', $user_id, $matches)) {
        return $matches[1]; // Return the string before 'HOD'
    }
    return false;
}


$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $prefix = getPrefixFromUserId($user_id);
    if ($prefix !== false) {
        include 'sql_conn.php';

        if (isset($conn) && $conn instanceof mysqli) {
            $classSql = "SELECT DISTINCT class_id FROM fees WHERE class_id LIKE '$prefix%'";

            $classResult = $conn->query($classSql);
            if ($classResult) {
                while ($classRow = $classResult->fetch_assoc()) {
                    $classId = $classRow['class_id'];

                    $feeSql = "SELECT 
                                   SUM(total_fee) AS total, 
                                   SUM(amount_paid) AS paid, 
                                   SUM(due_amount) AS due
                               FROM fees
                               WHERE class_id = '$classId'";

                    $feeResult = $conn->query($feeSql);

                    if ($feeResult && $feeRow = $feeResult->fetch_assoc()) {
                        $response[$classId] = [
                            'total' => $feeRow["total"] ? $feeRow["total"] : 0,
                            'paid' => $feeRow["paid"] ? $feeRow["paid"] : 0,
                            'due' => $feeRow["due"] ? $feeRow["due"] : 0
                        ];
                    }
                }
            } else {
                $response['error'] = "SQL Error: " . $conn->error;
            }

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

echo json_encode($response);
?>
