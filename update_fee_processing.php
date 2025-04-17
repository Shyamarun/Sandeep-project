<?php
include 'sql_conn.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_num = $_POST['reg_num'];
    $tution_fee = $_POST['tution_fee'];
    $building_fund = $_POST['building_fund'];
    $crt_fee = $_POST['crt_fee'];
    $bus_fee = $_POST['bus_fee'];
    $hostel_fee = $_POST['hostel_fee'];
    $total_fee = $_POST['total_fee'];

    // Calculate amount_paid
    $amount_paid = $tution_fee + $building_fund + $crt_fee + $bus_fee + $hostel_fee;

    // Calculate due_amount
    $due_amount = $total_fee - $amount_paid;

    // Prepare your SQL statement
    $stmt = $conn->prepare("UPDATE fees SET tution_fee=?, building_fund=?, crt_fee=?, bus_fee=?, hostel_fee=?, total_fee=?, amount_paid=?, due_amount=? WHERE reg_num=?");
    $stmt->bind_param("iiiiiiiis", $tution_fee, $building_fund, $crt_fee, $bus_fee, $hostel_fee, $total_fee, $amount_paid, $due_amount, $reg_num);

    if ($stmt->execute()) {
        echo "Fees updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
