<?php
session_start();

require_once('conn.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
   

    $password = $_POST['password']; 
    $phonenumber = $_POST['phonenumber'];

    // Prepare and execute SQL query to insert data into the users table
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, email, password, PhoneNo) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password,$phonenumber);
     
    if ($stmt->execute()) {
        $_SESSION['user_id']=$user_id;
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();

?>
