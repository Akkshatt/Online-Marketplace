<?php
session_start();

require_once('../connection/connection.php');

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
                echo '<script>alert("Invalid Password")</script>';
                echo "<script>window.location.href ='./login.php'</script>";
                       
                exit;
            }
            else
            {
                $_SESSION['user_id'] = $id;
             
                echo'<script>alert("Login successful") </script>';
                echo "<script>window.location.href ='./user_roles.php'</script>";
                   
               
            }

    } else {
        echo '<script>alert("Invalid Username")
        window.location.assign("login.php")</script>';

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
    <style>
        .register-form {
            display: none;

        }
    </style>
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

                <form id="loginForm" action="" method="POST">
                    <h1>Login</h1>
                    <div class="input-box">
                        <input name="username" type="text" placeholder="username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input name="password" type="password" placeholder="password" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox">Remember me</label>
                        <a href="#">Forgot password</a>
                    </div>
                    <button type="submit" class="btn">Login</button>
                    <div class="register-link">
                        <p>Don't have an account?<a href="registration.php" id="toggleRegister">Sign up</a></p>
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