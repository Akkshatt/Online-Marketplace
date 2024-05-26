<?php
session_start();
require_once('../connection/connection.php');
    if(isset($_SESSION['user_id']))   {
    $user_id = $_SESSION['user_id'];
 

   
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $username = htmlspecialchars($row['username']);
    } else {
 
        header("Location: error.php");
        exit();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Completed</title>
    <link rel="stylesheet" href="../css/complete.css">
</head>
<body>
<div class="dash">
<!-- <div class="upper" id="upper-part">
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#"><i class='bx bxs-truck'></i></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <a class="nav-link active" aria-current="page" href="#">
                                    <div class="waviy">
                                        <span style="--i:1">D</span>
                                        <span style="--i:2">0</span>
                                        <span style="--i:3">0</span>
                                        <span style="--i:4">D</span>
                                        <span style="--i:5">L</span>
                                        <span style="--i:6">E</span>
                                        <span style="--i:7">.</span>
                                    </div>
                                </a>
                                <a class="nav-link" href="#">register now </a>
                                <a class="nav-link" href="#">start selling </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div> -->
            <div class="navb">
            <div class="nsec1">
          
            <div class="waviy">  <i class='bx bxs-truck'></i>
                                    <span style="--i:1">D</span>
                                    <span style="--i:2">0</span>
                                    <span style="--i:3">0</span>
                                    <span style="--i:4">D</span>
                                    <span style="--i:5">L</span>
                                    <span style="--i:6">E</span>
                                    <span style="--i:7">.</span>
                                </div>
            </div>
            <div class="nsec2">
                <a href="buyer.php">buyer</a>
                <a href="buyer_dashboard.php">profile</a>
                <a href="return.php">returns</a>

                <a href="./orders.php">orders</a>
            </div>
           
        </div>

    <div class="box">
    <div class="alert " role="alert">.
        <h4 class="alert-heading">ORDER COMPLETED !</h4>
        <p><?php echo $username; ?> ORDER successfully ORDERED</p>
        <!-- <hr> -->
        <div class="details">
        <p class="m">Move to home page <a href="./buyer.php">buyer</a>.</p>
        <!-- <hr> -->
        <p class="m">Move to dashboard <a href="buyer_dashboard.php">profile</a>.</p>
        </div>
    </div>
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
</body>
</html>
