<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>DASSHBOARD</title>
</head>

<body>
    <div class="dash">
        <div class="navb">
            <div class="nsec1">
                Logo
            </div>
            <div class="nsec2">
                <a href="">Home</a>
                <a href="">Shop</a>
                <a href="">Blog</a>
                <a href="">Contact</a>
            </div>
            <div class="nsec3">
                <input type="search" id="gsearch" name="gsearch" placeholder="search ...">


            </div>

        </div>
       
        <div class="image">
            <!-- <img class="fit" src="mint.jpeg" alt="profile"> -->
            <div class="image-text">

            <?php
require_once('conn.php');

// Check if 'user_id' and 'RoleID' are set in the URL parameters
if (isset($_GET['user_id']) && isset($_GET['RoleID'])) {
    $user_id = $_GET['user_id'];
    $RoleID = $_GET['RoleID'];

    // Use prepared statement to avoid SQL injection
    $sql = "SELECT username FROM project.users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row["username"];

        echo "<p>Account: " . $RoleID . "</p>";
        echo "<p class='account user name'>Username: " . $username . "</p>";
    } else {
        echo "No user found with the given user_id";
    }

    $stmt->close();
} else {
    echo "User ID and Role ID are required parameters.";
}
?>
              


                <!-- <h2>seller ID</h2>
                <h2>name</h2>
                <h2>product</h2> -->
            </div>

        </div>
        <div class="box">
            <div class="box-left">


                <?php
      
        
                // Fetch data from the products and paymentdetails tables
                // $sql = "SELECT products.*, paymentdetails.payment_process_time 
                //         FROM project.products 
                //         LEFT JOIN project.paymentdetails ON products.id = paymentdetails.product_id";
                // $sql = "SELECT products.*, paymentdetails.payment_process_time 
                // FROM project.products 
                // LEFT JOIN project.paymentdetails ON products.id = paymentdetails.product_id
                // WHERE products.user_id = $user_id";
                // $sql = "SELECT cart.*, paymentdetails.payment_process_time 
                // FROM project.cart 
                // LEFT JOIN project.paymentdetails ON cart.product_id = paymentdetails.product_id
                // WHERE cart.user_id = $user_id";
        
                $sql = "SELECT * FROM project.products WHERE user_id = $user_id";
                $result = $conn->query($sql);
               
                
               
                
                $result = $conn->query($sql);
        
                // Check if there are any products in the database
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // echo '<div class="main-box">';
                        echo '<div class="product-box">';
                        echo '<div class="product-container">';
                        echo '<div class="image-2">';
                        echo '<img src="abc.jpeg" alt="' .base64_encode( $row['image'] ). '">';
                        echo '</div>';
                        echo '<div class="details">';
                        echo '<p>' . $row['title'] . '</p>';
                        echo '<p>$' . $row['price'] . '</p>';
                        echo '<p>' . $row['quantity'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                         echo '</div>';
                        // echo '</div>';
                    }
                } else {
                    echo "No products found in the database.";
                }
                ?>






                <!-- <div class="image-card">
                    <img src="Duck.jpeg" alt="Product Name">
                </div>
                <div class="captions">
                    <h2 class=" Product Name">Product Name</h2>
                    <p class="price">34</p>
                </div> -->
            </div>
            <div class="checkout-button">
        <form action="formm.php" method="get">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="RoleID" value="<?php echo $RoleID; ?>">
                <button type="submit" class="btn btn-primary">add new product</button>
            </form>
           
        </div>
            <div class="box-right">
                <div class="box-right-item">
                    <h1>orders</h1>
                    <p>order name </p>
                    <p>buyer name</p>
                </div>
            </div>
        </div>



    </div>
</body>

</html>