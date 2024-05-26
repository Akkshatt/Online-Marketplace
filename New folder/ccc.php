<?php
require_once('conn.php');

// $user_id = $_GET['user_id'];
// $RoleID = $_GET['RoleID'];
// $CartId = $_GET['CartID'];

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $itemIdToRemove = $_POST['remove_item'];
    $deleteSql = "DELETE FROM cart WHERE cart_id = 30";
    $conn->query($deleteSql);
}

// Place order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    header("Location: ./address.php?user_id=".$user_id."&RoleID=".$RoleID);
    exit();
}

$cartItems = [];
$cartSql = "SELECT c.*, p.title, p.price, p.image FROM cart c
            INNER JOIN products p ON c.product_id = p.id
            WHERE c.user_id = 10"; // Filter products by user ID
$cartResult = $conn->query($cartSql);

if ($cartResult->num_rows > 0) {
    while ($row = $cartResult->fetch_assoc()) {
        $cartItems[] = $row;
    }
}

$totalPrice = 0;
foreach ($cartItems as $cartItem) {
    $productPrice = floatval($cartItem['price']); // Convert price to float if it's not already
    $quantity = intval($cartItem['quantity']); // Convert quantity to integer if it's not already
    $totalPrice += $quantity * $productPrice;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="cart.css">
    <title>cart new ekdum
    </title>
</head>

<body>
    <div class="dash">
        <!-- Your existing HTML content here -->
        <div class="upper" id="upper-part">
            <!-- ... (your existing navigation bar) ... -->
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
        
        <div class="second-body">
        <h3 class="cart-heading">cart</h3>
            <!-- Your existing content here -->
            <div class="cart-box">
                <div class="card">
                    <div class="table-responsive">
                        <form method="post">
                            <!-- Your cart table content here -->
                            <table class="table table-borderless table-shopping-cart">
                            <thead class="text-muted">
                                <tr class="small text-uppercase">
                                    <th scope="col">Product</th>
                                    <th scope="col" width="120">Quantity</th>
                                    <th scope="col" width="120">Price</th>
                                    <th scope="col" class="text-right d-none d-md-block" width="200">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $cartItem): ?>
                                    <tr>
                                        <!-- Cart item details here -->
                                        <td>
                                            <div class='image'>
                                                <img src="<?php echo 'data:image/png;base64,' . base64_encode($cartItem['image']); ?>" alt="Product Image">
                                            </div>
                                            <div class='info'>
                                                <a href='#' class='title' data-abc='true'><?php echo $cartItem['title']; ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="Quantity">Quantity: <?php echo $cartItem['quantity']; ?></p>
                                        </td>
                                        <td>
                                            <p class="price">$<?php echo number_format($cartItem['quantity'] * $cartItem['price'], 2); ?></p>
                                        </td>
                                        <td class='text-right d-none d-md-block'>
                                        <button type="submit" class='btn btn-light' name="remove_item" value="<?php echo $cartItem['cart_item_id']; ?>">Remove item</button>
                
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </form>
                        <!-- Your cart summary and checkout button here -->
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="order-summary">
                <p>Order Summary</p>
                <div class="sub">
                    <p>Subtotal</p>
                    <p>$<?php echo number_format($totalPrice, 2); ?></p>
                </div>
                <div class="tax">
                    <p>Tax</p>
                    <p>$0.00</p>
                </div>
                <div class="shipping">
                    <p>Shipping</p>
                    <p>$0.00</p>
                </div>
                <div class="total">
                    <p>Total</p>
                    <p>$<?php echo number_format($totalPrice, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="checkout-button">
        <form action="address.php" method="get">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="RoleID" value="<?php echo $RoleID; ?>">
                <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
