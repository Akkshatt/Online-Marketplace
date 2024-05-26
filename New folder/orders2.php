<!DOCTYPE html>
<html>
<head>
    <title>User Order Details</title>
</head>
<body>

<h1>User Order Details</h1>

<?php
$servername = "localhost";
$username = "root";
$password = "akshat12345";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Orders.id AS order_id, Orders.order_date, Products.title, Products.price, OrderItems.quantity,
        PaymentDetails.card_number, Addresses.address_line1, Addresses.city, Addresses.state, Addresses.postal_code
        FROM Orders
        INNER JOIN OrderItems ON Orders.id = OrderItems.order_id
        INNER JOIN Products ON OrderItems.product_id = Products.id
        INNER JOIN PaymentDetails ON Orders.payment_details_id = PaymentDetails.id
        INNER JOIN Addresses ON Orders.address_id = Addresses.id
        WHERE products.id = 2"; // Replace 1 with the actual user_id

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<h2>Order ID: " . $row["order_id"]. "</h2>";
        echo "Order Date: " . $row["order_date"]. "<br>";
        echo "Product: " . $row["title"]. "<br>";
        echo "Price: $" . $row["price"]. "<br>";
        echo "Quantity: " . $row["quantity"]. "<br>";
        echo "Card Number: " . $row["card_number"]. "<br>";
        echo "Address: " . $row["address_line1"]. ", " . $row["city"]. ", " . $row["state"]. ", " . $row["postal_code"]. "<br>";
        echo "---------------------------------------<br>";
    }
} else {
    echo "No orders found for this user.";
}

$conn->close();
?>

</body>
</html>
