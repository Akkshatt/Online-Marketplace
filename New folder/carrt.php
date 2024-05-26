<?php
$servername = "localhost";
$username = "root";
$password = "akshat12345";
$database = "project";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['cart_id'])) {
    $cart_item_id = $_GET['cart_id'];
    // Your SQL query and further processing here...
} else {
    // Handle the case when cart_id is not set, for example, redirect to an error page.
    header("Location: error.php");
    exit();
}

// Remove item from cart
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
//     $itemIdToRemove = $_POST['remove_item'];
//     $deleteSql = "DELETE FROM cart WHERE cart_item_id = $itemIdToRemove";
//     $conn->query($deleteSql);
// }

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $itemIdToRemove = $_POST['remove_item'];
    
    // Use prepared statement to prevent SQL injection
    $deleteSql = "DELETE FROM cart WHERE cart_item_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $itemIdToRemove); // "i" indicates integer data type

    // Check if the statement is executed successfully
    if ($stmt->execute()) {
        // Item successfully removed from the cart
        // You can redirect the user to the cart page or display a success message
        header("Location: carrt.php");
        exit();
    } else {
        // Handle the error, for example, by displaying an error message
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Place order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    // Add your order placement logic here
    // For example: header("Location: ./address.php?user_id=".$user_id);
}

$cartItems = [];
$cartSql = "SELECT c.*, p.title, p.price, p.image FROM cart c
            INNER JOIN products p ON c.product_id = p.id";
$cartResult = $conn->query($cartSql);

if ($cartResult->num_rows > 0) {
    while ($row = $cartResult->fetch_assoc()) {
        $cartItems[] = $row;
    }
}

// Calculate total price of items in the cart
$totalPrice = 0;
foreach ($cartItems as $cartItem) {
    $productPrice = $cartItem['price'];
    $quantity = $cartItem['quantity'];
    $totalPrice += $quantity * $productPrice;
}

// Close the database connection
$conn->close();
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
    <link rel="stylesheet" href="cart.css">
    <title>Shopping Cart</title>
</head>

<body>
    <div class="dash">
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
                            <a class="nav-link" href="#">Register Now</a>
                            <a class="nav-link" href="#">Start Selling</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="second-body">
            <h3 class="cart-heading">Shopping Cart</h3>
            <div class="content">
                <div class="cart-box">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-borderless table-shopping-cart">
                                <thead class="text-muted">
                                    <tr class="small text-uppercase">
                                        <th scope="col">Product</th>
                                        <th scope="col" width="120">Quantity</th>
                                        <th scope="col" width="120">Price</th>
                                        <th scope="col" class="text-right d-none d-md-block" width="200"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $cartItem): ?>
                                        <tr>
                                            <td>
                                                <figure class='itemside align-items-center'>
                                                    <div class='image'>
                                                        <img src="<?php echo 'data:image/png;base64,' . base64_encode($cartItem['image']); ?>" alt="Product Image">
                                                    </div>
                                                    <figcaption class='info'>
                                                        <a href='#' class='title' data-abc='true'><?php echo $cartItem['title']; ?></a>
                                                    </figcaption>
                                                </figure>
                                            </td>
                                            <td>
                                                <p class="Quantity">Quantity: <?php echo $cartItem['quantity']; ?></p>
                                            </td>
                                            <td>
                                                <p class="price">$<?php echo number_format($cartItem['quantity'] * $cartItem['price'], 2); ?></p>
                                            </td>
                                            <td class='text-right d-none d-md-block'>
                                                <a data-original-title='Save to Wishlist' title='' href='' class='btn btn-light' data-toggle='tooltip' data-abc='true'>
                                                    <i class='fa fa-heart'></i>
                                                </a>
                                                <form method="post" action="">
    <input type="hidden" name="remove_item" value="<?php echo $cartItem['id']; ?>">
    <button type="submit" class='btn btn-light' data-abc='true'>Remove item</button>
</form>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
                <a href="address.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</body>

</html>
