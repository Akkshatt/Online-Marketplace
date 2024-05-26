<?php
// Include your database connection code here
require_once('../connection/connection.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


    $user_query = "SELECT firstname,lastname, email,username, profile_picture FROM users WHERE id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);

    $profile_query = "SELECT profile_picture FROM users WHERE id = $user_id";
    $profile_result = mysqli_query($conn, $profile_query);
    $profile_data = mysqli_fetch_assoc($profile_result);
    $profile_picture = $profile_data['profile_picture'];

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

    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
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
                            <a class="nav-link" href="../seller/dashboard.php">register now </a>
                            <a class="nav-link" href="../seller/dashboard.php">start selling </a>

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
                <a href="forum.php">forum</a>
                <a href="buyer_dashboard.php">profile</a>
                <a href="cart.php">cart</a>

                <a href="./orders.php">orders</a>
            </div>
           
        </div>
        <div class="right-up">
            <p>Account</p>
            <p class="account user name"><?php echo  $user_data['username']; ?></p>
        </div>
        <div class="horizontal"></div>
        <div class="box-part">
         
                <h2>welcome to your profile </h2>
                <div class="bigrow">
                    <div class="bigrow1">

                          <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" alt="Profile Picture">
                        <div class="details">
                        <p><?php echo  $user_data['email']; ?></p>
                        <p><?php echo $user_data['firstname'] . ' ' .  $user_data['lastname']; ?></p>
</div>
                     </div>
                    <div class="bigrow2">
                        <button>Edit Info</button>
                    </div>
                </div>
                <div class="row1">
                    <a class="row1-c1" href="orders.php">
                        <div class="symb">
                            <i class='bx bxs-package'></i>
                        </div>
                        <h5>ORDERS</h5>
                        <p>  previous orders </p>
                       </a>
                       <a class="row1-c1" href="return.php">
                        <div class="symb">
                        <i class='bx bx-sync'></i>
                        </div>
                        <h5>Returns</h5>
                        <p>returned orders </p>
                       </a>
                       <a class="row1-c1" href="cart.php">
                        <div class="symb">
                        <i class='bx bxs-cart'></i>
                        </div>
                        <h5>Cart</h5>
                        <p>check you cart items </p>
                       </a>
                </div>

                <div class="row2">
                <a class="row2-c1" href="saved_address.php">
                        <div class="symb">
                        <i class='bx bx-current-location' ></i>
                        </div>
                        <h5>saved address</h5>
                        <p> </p>
                       </a>
                       <a class="row2-c1" href="">
                        <div class="symb">
                        <i class='bx bxs-heart' ></i>
                        </div>
                        <h5>likes</h5>
                        <p> </p>
                       </a>
                       <a class="row2-c1" href="">
                        <div class="symb">
                        <i class='bx bxs-offer'></i>
                        </div>
                        <h5>coupons</h5>
                        <p> </p>
                       </a>
                </div>
                <div class="row3">
                <a class="row3-c1" href="">
                        <div class="symb">
                        <i class='bx bxs-coupon' ></i>
                        </div>
                        <h5>..</h5>
                        <p> </p>
                       </a>
                       <a class="row3-c1" href="">
                        <div class="symb">
                        <i class='bx bxs-shopping-bags'></i>
                        </div>
                        <h5>...</h5>
                        <p> </p>
                       </a>
                       <a class="row3-c1" href="">
                        <div class="symb">
                        <i class='bx bx-support'></i>
                        </div>
                        <h5>...</h5>
                        <p> </p>
                       </a>
                </div>
         


        </div>

    </div>
</body>
<footer class="white-section" id="footer">
    <div class="container-fluid">
        <i class='bx bxl-facebook'></i>
        <i class='bx bxl-twitter'></i>
        <i class='bx bxl-instagram'></i>
        <i class='bx bxl-gmail'></i>

        <p>© Copyright</p>
    </div>
</footer>


</html>