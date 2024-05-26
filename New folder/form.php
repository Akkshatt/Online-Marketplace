<?php
$servername = "localhost";
$username = "root";
$password = "akshat12345";
$database = "project";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    // Get image data
    $image = $_FILES["image"]["tmp_name"];
    $imgData = file_get_contents($image);
    // $imgData = addslashes(file_get_contents($image)); // Convert image to binary data

    // Insert data into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO products (title, description, price, quantity, category, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $title, $description, $price, $quantity, $category, $imgData);
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>
