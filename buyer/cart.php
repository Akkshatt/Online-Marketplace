<?php

session_start();
$hostname = "localhost";
$username = "root";
$password = "akshat12345";
$dbname = "project";

$conn = new mysqli($hostname, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$select_query = "select role from userroles where user_id =$user_id";
$result=mysqli_query($conn,$select_query);
$fetching = mysqli_fetch_assoc($result);
$role=$fetching['role'];


$user_query = "SELECT username, profile_picture FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);


$username = $user_data['username'];
$profile_picture = $user_data['profile_picture'];

function remove_cart_item() {
    global $conn; 
    if (isset($_POST['remove_cart'])) {
        if (isset($_POST['removeitem']) && is_array($_POST['removeitem'])) {
            foreach ($_POST['removeitem'] as $remove_id) {
                $remove_id = (int) $remove_id; 
                $deletequery = "DELETE FROM cart WHERE id = $remove_id";
                $run_delete = mysqli_query($conn, $deletequery);
                if ($run_delete) {
                    echo "<script>window.open('cart.php', '_self')</script>";
                }
            }
        }
    }
}

$remove_item = remove_cart_item();
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
    <link rel="stylesheet" href="../css/cart.css">
    <title>cart new ekdum
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
                <a href="buyer.php">buyer</a>
                <a href="buyer_dashboard.php">profile</a>
                <a href="return.php">returns</a>

                <a href="./orders.php">orders</a>
            </div>
           
        </div>


        <div class="right-up">
    <!-- <p>Account</p> -->
    <div class="image">
        <!-- Display user profile picture -->
        <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" alt="Profile Picture">
    </div>
    <!-- Display username -->
    <div class="account user name"><?php echo $username; ?></div>
</div>
            <div class="horizontal">
            </div>
           <form action="" method="post">
            <div class="box-main">
            <div class="box1">
            <table class="table ">
                <tbody>
                    <?php
                    global $con;
                    $total_price = 0;
                    $select_price = "SELECT * FROM cart WHERE user_id = '$user_id'";
                    $result = mysqli_query($conn, $select_price);
                    $result_count = mysqli_num_rows($result);
                    if ($result_count > 0) {
                        $quantities = array();
                        echo "
                            <thead class='border'>
                                <tr>
                                    <th>Title</th>
                                   <th> Image</th>
                                    <th>Quantity</th>
                                    <th> Price</th>
                                    <th>SELECT</th>
                                    <th>REMOVE</th>
                                </tr>
                            </thead>
                        ";
                        while ($row = mysqli_fetch_array($result)) {
                            $product_id = $row['id'];
                            $select_products = "SELECT * FROM products WHERE id = $product_id";
                            $result_products = mysqli_query($conn, $select_products);
                            while ($row_product_price = mysqli_fetch_array($result_products)) {
                                $product_price = (float) $row_product_price['price'];
                                $product_title = $row_product_price['title'];
                                $product_image1 = $row_product_price['image'];
                                $quantity = (int) $row['quantity'];
                                $product_values = $product_price * $quantity;
                                $total_price += $product_values;
                                // Store the quantity for each product in the cart
                                $quantities[$product_id] = $quantity;
                                ?>
                                <tr>
                                    <td><?php echo $product_title ?></td>
                                    <td> <img src="data:image/jpeg;base64,<?php echo base64_encode($product_image1); ?>" ></td>
                                    <td  class="quantity"><?php echo $quantity ?>
                                    </td>
                                    <td><?php echo $product_values ?>/-</td>
                                    <td><input type="checkbox" name="removeitem[]" value="<?php echo $product_id ?>"></td>
                                    <td class="d-flex">
                                        <input type="submit" value="Remove Cart" class="px" name="remove_cart">
                                    </td>
                                </tr>
                            <?php }
                        }
                    } else {
                        echo "<h2 class='text'>Cart Is Empty</h2>";
                    }
                    ?>
                </tbody>
            </table>
            </div>
            <div class="box2">
                <div class="one">
                <p>sub total:</p>
                <p><?php echo $total_price ?></p>
                </div>
        
                <p>tax</p>
                <div class="one">
                <p>shipping:</p>
                <p> 40</p>
                </div>
                <div class="one">
                <p>Total:</p>
                <p> <?php echo $total_price + 40 ?>/-</p>
                </div>
                <!-- <a href="equipments.php">
                    <button class=" btn bg-info d" name="purchase_more_items" value="purchase_more_items">Continue Purchase</button>
                </a> -->
               
            </div>

            </div>
            <div class="btn">
            <a  href="./saved_address.php">
                    <button class="proceed" name="go_to_payment" value="go_to_payment">Proceed To Add Address</button>
                </a>
                </div>
           </form>
         <?php

           if (isset($_POST['purchase_more_items'])) {
              echo "<script>window.open('equipments.php', '_self')</script>";
           } elseif (isset($_POST['go_to_payment'])) {
           echo "<script>window.open('./saved_address.php', '_self')</script>";
           }
           ?>
            <div class="bg-info">
          
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