<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $card_number = $_POST['card_number'];
    $cardholder_name = $_POST['cardholder_name'];
    $expiration_date = $_POST['expiration_date'];
    $cvv = $_POST['cvv'];
    

 

    // Your database connection details
    $servername = "localhost";
    $username = "root";
    $password = "akshat12345";
    $dbname = "project";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $timestamp = date('Y-m-d H:i:s', time());
    
    // SQL query to insert data into the PaymentDetails table
    $sql = "INSERT INTO paymentdetails (user_id, card_number, cardholder_name, expiration_date, cvv, payment_process_time)
    VALUES (1, '$card_number', '$cardholder_name', '$expiration_date', '$cvv',  '$timestamp')";

    // Execute SQL query
    if ($conn->query($sql) === TRUE) {
        echo "New payment record created successfully";
        header("Location:./orders.php?timestamp=".$timestamp."&username=".$username);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
