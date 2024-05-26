<?php
session_start();

require_once('../connection/connection.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
  
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
   

    $password = $_POST['password']; 
    $phonenumber = $_POST['phonenumber'];

    
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, email, password, PhoneNo) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password,$phonenumber);
     
    if ($stmt->execute()) {
      $user_id=  $_SESSION['user_id'];
        echo "<script>alert('Registration For $first_name $last_name is Sucuessful ')</script>";
        echo "<script>window.location.href ='./login.php'</script>";
    } else {
        echo "<script>alert('Entry For $first_name $last_name is Already Exist In database')</script>";
    }

    $stmt->close();
}
$conn->close();

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/login-register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Login</title>
    
</head>


<body>
    <div class="dash">
        <!-- <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">doodle</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">...</a>
                        </li>

                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-info" type="submit">Search</button>
                        </form>
                </div>
            </div>
        </nav> -->
        <div class="heading">
            <h1>doodle</h1>
        </div>
        <div class="sec1">
            <div class="wrapper">

                <form id="registerForm" class="register-form" action="" method="POST">
                    <h1>Sign up</h1>
                    <div class="input-box">
                        <input name="first_name" type="text" placeholder=" First Name" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input name="last_name" type="text" placeholder=" Last Name" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input name="username" type="text" placeholder=" Select Username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input name="phonenumber" type="tel" placeholder="phonenumber" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input name="email" type="email" placeholder="Email" required>
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <div class="input-box">
                        <input name="password" type="password" placeholder="Password" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>
                    <!-- <div class="input-box">
                    <input type="password" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div> -->

                    <button type="submit" class="btn">Sign up</button>
                    <div class="register-link">
                        <p>Already have an account?<a href="login.php" id="toggleLogin">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
        <footer class="white-section" id="footer">
            <div class="container-fluid">
                <i class='bx bxl-facebook'></i>
                <i class='bx bxl-twitter'></i>
                <i class='bx bxl-instagram'></i>
                <i class='bx bxl-gmail'></i>

                <p>© Copyright</p>
            </div>
        </footer>
    </div>
</body>

</html>