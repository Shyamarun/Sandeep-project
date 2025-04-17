<!DOCTYPE html>
<html>

<body>
    <h1>
        <center>Student Records</center>
    </h1>

    <?php
    include 'sql_conn.php';

    // SQL query to retrieve records from a table
    $sql = "SELECT * from stdreg";

    // Execute the query
    $result = mysqli_query($connection, $sql);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    // Display the records in an HTML table
    echo "<center><table border='1'>
        <tr>
            <th>Name</th>
            <th>Registration Number</th>
            <th>Year</th>
            <th>Semester</th>
            <th>Branch</th>
            <th>Section</th>
            <th>Phone number</th>
            <th>Email</th>
            <th>Blood group</th>
            <th>Password</th>
            <th>Confirmed Password</th>
            <th>Submitted Time</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>" . $row["full_name"] . "</td>
            <td>" . $row["reg_num"] . "</td>
            <td>" . $row["sem_year"] . "</td>
            <td>" . $row["semester"] . "</td>
            <td>" . $row["branch"] . "</td>
            <td>" . $row["section"] . "</td>
            <td>" . $row["phone_num"] . "</td>
            <td>" . $row["email"] . "</td>
            <td>" . $row["blood_g"] . "</td>
            <td>" . $row["user_pass"] . "</td>
            <td>" . $row["cnf_pass"] . "</td>
            <td>" . $row["sub_time"] . "</td>
        </tr>";
    }

    echo "</table></center>";

    // Close the database connection
    mysqli_close($connection);
    ?>

</body>

</html>