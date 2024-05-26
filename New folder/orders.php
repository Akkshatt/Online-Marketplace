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
    <link rel="stylesheet" href="orders.css">
    <title>ORDERedITEMS</title>
</head>

<body>
    <div class="dash">

        <div class="upper" id="upper-part">
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
        </div>

        <div class="right-up">
            <?php
            require_once('conn.php');
            // $user_id = $_GET['user_id'];
            // $RoleID = $_GET['RoleID'];
            

            $sql = "SELECT username FROM project.users WHERE id = $user_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $username = $row["username"];
                
                echo "<p>Account: " . $RoleID ."</p>";
                echo "<p class='account user name'>Username: " . $username . "</p>";
            } else {
                echo "No user found with the given user_id";
            }
            ?>
        </div>

        <div class="horizontal"></div>
        <!-- <div class="main-box"> -->
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

        $sql = "SELECT * FROM project.products";
        $result = $conn->query($sql);
       
        
       
        
        $result = $conn->query($sql);

        // Check if there are any products in the database
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // echo '<div class="main-box">';
                echo '<div class="product-box">';
                echo '<div class="product-container">';
                echo '<div class="image">';
                echo '<img src="abc.jpeg" alt="' .base64_encode( $row['image'] ). '">';
                echo '</div>';
                echo '<div class="details">';
                echo '<p>' . $row['title'] . '</p>';
                echo '<p>$' . $row['price'] . '</p>';
                echo '<p>order-time: ' . $row['payment_process_time'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '<div class="return-button">';
                echo '<button type="button" class="btn btn-primary">want to return</button>';
                echo '</div>';
                echo '</div>';
                // echo '</div>';
            }
        } else {
            echo "No products found in the database.";
        }
        ?>

            <!-- <div class="return-button">
                <button type="button" class="btn btn-primary">want to return</button>
            </div>
        </div> -->

    </div>

</body>

</html>
