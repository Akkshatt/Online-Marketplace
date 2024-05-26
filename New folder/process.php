<?php
$servername = "localhost";
$username = "root"; // Yoroour database username
$password = "akshat12345"; // Your database password
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$address_line1 = $_POST['address_line1'];
$address_line2 = $_POST['address_line2'];
$city = $_POST['city'];
$state = $_POST['state'];
$postal_code = $_POST['postal_code'];
$country = $_POST['country'];

// SQL query to insert data into the Addresses table
$sql = "INSERT INTO Addresses (user_id, address_line1, address_line2, city, state, postal_code, country)
VALUES (1, '$address_line1', '$address_line2', '$city', '$state', '$postal_code', '$country')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location:transcation.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
