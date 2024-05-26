<?php


session_start();
$hostname = "localhost";
$username = "root";
$password = "akshat12345";
$dbname = "project";

$conn = new mysqli($hostname, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        $amount = $_POST['amount'];
        $description = $_POST['description'];

      
        $cartQuery = "SELECT * FROM cart WHERE user_id = $user_id";
        $cartResult = mysqli_query($conn, $cartQuery);

        $paymentQuery = "SELECT * FROM paymentdetails WHERE user_id = $user_id";
        $paymentResult = mysqli_query($conn, $paymentQuery);

       
        $rowPayment = mysqli_fetch_assoc($paymentResult);
        $billing_address_id = $rowPayment['billing_address_id'];
        $payment_details_id = $rowPayment['payment_id'];

       
        while ($rowCart = mysqli_fetch_assoc($cartResult)) {
            $product_id = $rowCart['id'];
            $quantity = $rowCart['quantity'];
            $timestamp = date("Y-m-d H:i:s");

            insertIntoOrders($user_id, $product_id,$quantity , $payment_details_id, $billing_address_id, $timestamp);

        
            $deleteCartQuery = "DELETE FROM cart WHERE user_id = $user_id ";
            mysqli_query($conn, $deleteCartQuery);
        }

       
        echo "<script>window.open('completion.php', '_self')</script>";
    }
}

function insertIntoOrders($user_id, $product_id, $quantity, $payment_details_id, $billing_address_id, $timestamp) {
    global $conn;

   
    $insertQuery = "INSERT INTO orders (user_id, product_id, quantity, payment_details_id, address_id, order_time) 
                    VALUES ('$user_id',  '$product_id', '$quantity', '$payment_details_id', '$billing_address_id', '$timestamp')";

    mysqli_query($conn, $insertQuery);

    echo "<script>window.open('completion.php', '_self')</script>";



}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Transaction Page</title>
</head>
<body>

<div class="container">
    <h1>Transaction Details</h1>

    <form action="" method="post">
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>
