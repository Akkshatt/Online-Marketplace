<?php
session_start();

require_once('conn.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($id, $dbUsername, $dbPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
            if($password != $dbPassword) {
                echo '<script>alert("Invalid Password")
                window.location.assign("main.html")</script>';  
                       
                exit;
            }
            else
            {
                $_SESSION['user_id'] = $id;
             
                echo'<script>alert("Login successful!") </script>';
                header("Location:./userroles.php?UserId=".$id);
            
                   
               
            }
        // Redirect to a dashboard or home page
        // header("Location: user_roles.php");
    } else {
        echo '<script>alert("Invalid Username")
        window.location.assign("login-register.html")</script>';

    }

    $stmt->close();
}

$conn->close();
?>
