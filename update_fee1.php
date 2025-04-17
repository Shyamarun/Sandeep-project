
        <?php
        include 'sql_conn.php';
        include 'show_notification.php';
        session_start();
        // Get reg_num parameter from the URL
        $reg_num = isset($_POST['reg_num']) ? $_POST['reg_num'] : '';
        //$param1 = isset($_POST['param1']) ? $_POST['param1'] : '';
        // Validate and sanitize input values (you can add more validation if needed)
        $tution_fee = isset($_POST['tution_fee']) ? intval($_POST['tution_fee']) : 0;
        $building_fund = isset($_POST['building_fund']) ? intval($_POST['building_fund']) : 0;
        $crt_fee = isset($_POST['crt_fee']) ? intval($_POST['crt_fee']) : 0;
        $bus_fee = isset($_POST['bus_fee']) ? intval($_POST['bus_fee']) : 0;
        $hostel_fee = isset($_POST['hostel_fee']) ? intval($_POST['hostel_fee']) : 0;
        $amount_paid = isset($_POST['amount_paid']) ? intval($_POST['amount_paid']) : 0;
        // Calculate total_fee, amount_paid, and due_amount
        $total_fee = $tution_fee + $building_fund + $crt_fee + $bus_fee + $hostel_fee; // You might get this from the form, assuming it's initially 0
        $due_amount = $total_fee - $amount_paid;



        // Assuming 'fees' is your table name
        $update_query = "UPDATE fees SET tution_fee=?, building_fund=?, crt_fee=?, bus_fee=?, hostel_fee=?, total_fee=?, amount_paid=?, due_amount=? WHERE reg_num=?";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("iiiiiiiis", $tution_fee, $building_fund, $crt_fee, $bus_fee, $hostel_fee, $total_fee, $amount_paid, $due_amount, $reg_num);

        if ($stmt->execute()) {
            redirectToPage('Fee updated Successfully', 'fee.php?class_id=' . $class_id);
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
        ?>
