<?php
           session_start();
           require_once('../connection/connection.php');
           $user_id = $_SESSION['user_id'];

            $sql = "SELECT * FROM project.users WHERE id = $user_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $username = $row["username"];
                $profile_picture = $row["profile_picture"];
                $firstname = $row["firstname"];
                $lastname =$row["lastname"];
                $email =$row["email"];

              } else {
                echo "No user found with the given user_id";
            }
            if (isset($_POST['submit'])) {
                echo "<script>window.open('formm.php', '_self')</script>";
             } 
             ?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="../buyer/buyer.php">buyers profile</a>
            </div>
            <div class="nsec3">
                <!-- <input type="search" id="gsearch" name="gsearch" placeholder="search ..."> -->
                <a href="">logout</a>

            </div>

        </div>

        <div class="image">
            <img class="fit" src="mint.jpeg" alt="profile">
            <div class="image-text">



                <div class="image2">

                    <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>"
                        alt="Profile Picture">
                </div>

                <div class="account user name">
                    <p>
                        <?php echo $username; ?>
                    </p>
                    <p>
                        <?php echo  $email ?>
                    </p>
                    <p>
                        <?php echo $firstname. ' ' .  $lastname; ?>
                    </p>
                </div>

                <div class="button">
                    <form action="" method="post">
                        <button class="submit" name="submit" type="submit" class="btn btn-primary">add new
                            product</button>
                    </form>
                </div>
                <!-- <h2>seller ID</h2>
                <h2>name</h2>
                <h2>product</h2> -->
            </div>

        </div>
        <div class="box">
            <div class="box-left">


                <?php
      
        
                
        
                $sql = "SELECT * FROM project.products WHERE user_id = $user_id";
                $result = $conn->query($sql);
               
                
               
                
                $result = $conn->query($sql);
                echo'<p class="abc">your products</p>';
              
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      
                     
                        echo '<div class="product-box">';
                       
                        echo '<div class="product-container">';
                        echo '<div class="image-2">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '">';

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







            </div>





            <div class="box-right">
                <h1>orders</h1>
                <div class="box-right-item">


                </div>

                <canvas class="myChart" id="myChart"></canvas>
                <canvas class="myChart2" id="ordersChart"></canvas>
            </div>
        </div>
    </div>



    <script src="product_chart.js"></script>
    <script src="second.js"></script>

</body>

</html>