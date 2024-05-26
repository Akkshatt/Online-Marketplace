<?php
         session_start();
         require_once('../connection/connection.php');
         $user_id = $_SESSION['user_id'];
        
$user_query = "SELECT username, profile_picture FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);


$username = $user_data['username'];
$profile_picture = $user_data['profile_picture'];
      ?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,700;1,400;1,600&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="../css/orders.css">
    <title>orders page

    </title> 
</head>

<body>
    <div class="dash">
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

                <a href="./return.php">return</a>
            </div>
           
        </div>
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
                            <a class="nav-link" href="buyer.php">orders more </a>
                            <a class="nav-link" href="return.php">returns </a>

                        </div>
                    </div>
                </div>
            </nav>
        </div> -->


   
        <div class="right-up">
    <!-- <p>Account</p> -->
    <div class="image2">
      
        <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" alt="Profile Picture">
    </div>
 
    <div class="account user name"><?php echo $username; ?></div>
</div>
 <div class="horizontal"></div>   

        <!-- <div class="main-box"> -->
         
<?php


           
            $sql = "SELECT  p.id AS product_id, p.title AS product_title, p.image ,o.order_time, a.address_line1 
            FROM orders o
            INNER JOIN products p ON o.product_id = p.id
            INNER JOIN addresses a ON o.address_id = a.billing_address_id
            WHERE o.user_id = $user_id";
            $result = $conn->query($sql);

          
        
            
               
            
                
             


            // Display orders data
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='main-boxs'>";
                    echo "<div class='details'>";
                    echo "<div class='image'>";
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '">';
                    echo "</div>";
                    echo "<div class='details-text'>";
                    echo "<p> " . $row['product_title'] . "</p>";
                    echo "<p>" . $row['order_time'] . "</p>";
                    echo "<p>" . $row['address_line1'] . "</p>";
                    // echo $row['product_id'];
                    echo "</div>";
                    echo "<div class='return-button'>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
                   echo "<button type='submit' name='return_order' class='btn btn-primary'>Return</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No orders found.";
            }
  

            if (isset($_POST['return_order'])) {
                $product_id = $_POST['product_id'];
            
             
                $insertReturnQuery = "INSERT INTO returns (user_id, product_id, return_date) VALUES ($user_id, $product_id, NOW())";
                if ($conn->query($insertReturnQuery) === TRUE) {
                    $deleteOrderQuery = "DELETE FROM orders WHERE user_id = $user_id AND product_id = $product_id";
                    if ($conn->query($deleteOrderQuery) === TRUE) {
                        echo "Return processed successfully.";
                    } else {
                        echo "Error deleting product from orders table: " . $conn->error;
                    }
                } else {
                    echo "Error inserting into returns table: " . $conn->error;
                }
            }
            



           
            $conn->close();
            ?>
     
    </div>
</body>

</html>
