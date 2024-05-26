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

function remove_cart_item() {
    global $conn; // Fix: Change $con to $conn
    if (isset($_POST['remove_cart'])) {
        if (isset($_POST['removeitem']) && is_array($_POST['removeitem'])) {
            foreach ($_POST['removeitem'] as $remove_id) {
                $remove_id = (int) $remove_id; // Cast to integer to prevent SQL injection
                $deletequery = "DELETE FROM cart WHERE id = $remove_id";
                $run_delete = mysqli_query($conn, $deletequery);
                if ($run_delete) {
                    echo "<script>window.open('marora.php', '_self')</script>";
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
    <title>Cart Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-info fixed">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="./viewmilkproduct.php"><img src="../logo.webp" alt="" ></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-center mt-2 ml-3" aria-current="page" href="./index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center mt-2" href="./farmer_index.php"> Operations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center mt-2" href="./marora.php"> Cart </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center mt-2" href="./viewmilkproduct.php"> Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center mt-2" href="./equipments.php"> Equipmnts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center mt-2" href="#">Contact</a>
                        </li>
                    </ul>
                    <form class="d-flex align-items-center" method="POST" action="">
                        <input class="form-control me-2" type="text" name="search" placeholder="Search" aria-label="Search" autocomplete="off">
                        <input class="btn btn-outline-light" type="submit" value="Search" autocomplete="off">
                    </form>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <div class="container">
                <ul class="navbar-nav me-2">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo "<li class='nav-item'>
                            <a class='nav-link' href='#'>Welcome $user_id</a>
                        </li>";
                        echo "<li class='nav-item'>
                            <a class='nav-link' href='#'>Logout</a>
                        </li>";
                    }
                     else {
                        echo "<li class='nav-item'>
                            <a class='nav-link' href='#'>Welcome Guest</a>
                        </li>";
                        echo "<li class='nav-item'>
                            <a class='nav-link' href='../login.php'>Login</a>
                        </li>";
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <form action="" method="post">
            <table class="table border-10 table-bordered p-2 mt-4 text-center align-items-center">
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
                            <thead class='border-2 mt-5'>
                                <tr>
                                    <th>Product Title</th>
                                 
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Remove</th>
                                    <th>Operations</th>
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
                                    <!-- <td><img src="./<?php echo $product_image1 ?>" alt="" width="150px" height="150px"></td> -->
                                    <td>
                                        <input type="number" name="qty[<?php echo $product_id ?>]" class="form-input w-50 text-center" value="<?php echo $quantity ?>">
                                    </td>
                                    <td><?php echo $product_values ?>/-</td>
                                    <td><input type="checkbox" name="removeitem[]" value="<?php echo $product_id ?>"></td>
                                    <td class="d-flex">
                                        <input type="submit" value="Remove Cart" class="px-3 py-2 border-0 mx-3 w-50" name="remove_cart">
                                    </td>
                                </tr>
                            <?php }
                        }
                    } else {
                        echo "<h2 class='text-center text-danger'>Cart Is Empty</h2>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="d-flex">
                <h4 class="px-3 mt-5">Total: <strong class="text-info"><?php echo $total_price ?>/-</strong></h4>
                <a href="equipments.php">
                    <button class=" btn bg-info m-5 border-2 font-bold" name="purchase_more_items" value="purchase_more_items">Continue Purchase</button>
                </a>
                <a href="payment.php">
                    <button class="bg-secondary btn mt-5 border-2" name="go_to_payment" value="go_to_payment">Go To Payment</button>
                </a>
            </div>
        </form>
        <?php

        if (isset($_POST['purchase_more_items'])) {
            echo "<script>window.open('equipments.php', '_self')</script>";
        } elseif (isset($_POST['go_to_payment'])) {
            echo "<script>window.open('payment.php', '_self')</script>";
        }
        ?>
        <div class="bg-info p-3 text-center">
            <p>All Rights Reserved - Designed By Mohit</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
</html>