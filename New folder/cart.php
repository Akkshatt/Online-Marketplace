<?php
require_once('conn.php');

// Function to sanitize user input
function sanitize($input) {
    // Use appropriate sanitization method based on your needs
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

$user_id = isset($_GET['user_id']) ? sanitize($_GET['user_id']) : null;
$RoleID = isset($_GET['RoleID']) ? sanitize($_GET['RoleID']) : null;

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $itemIdToRemove = sanitize($_POST['remove_item']);
    $deleteSql = "DELETE FROM cart WHERE cart_item_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $itemIdToRemove);
    $deleteStmt->execute();
    $deleteStmt->close();
}

// Fetch cart items
$cartItems = [];
$cartSql = "SELECT c.*,p.image, p.title, p.price FROM cart c
            INNER JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
$cartStmt = $conn->prepare($cartSql);
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

while ($row = $cartResult->fetch_assoc()) {
    $cartItems[] = $row;
}

// Handle the checkout process
if (isset($_POST['place_order'])) {
    // Insert cart items into orders table
    // $insertOrderSql = "INSERT INTO orders (user_id, product_id, quantity, order_date)
    //                    VALUES (?, ?, ?, NOW())";

    // $orderIds = []; // Store order IDs

    // foreach ($cartItems as $cartItem) {
    //     $insertOrderStmt = $conn->prepare($insertOrderSql);
    //     $insertOrderStmt->bind_param("iii", $user_id, $cartItem['product_id'], $cartItem['quantity']);
    //     $insertOrderStmt->execute();

    //     // Store the order ID
    //     $orderIds[] = $insertOrderStmt->insert_id;

    //     $insertOrderStmt->close();
    // }

    // Clear cart after transferring to orders
    // $clearCartSql = "DELETE FROM cart WHERE user_id = ?";
    // $clearCartStmt = $conn->prepare($clearCartSql);
    // $clearCartStmt->bind_param("i", $user_id);
    // $clearCartStmt->execute();
    // $clearCartStmt->close();

    // Redirect to the next page with order IDs
    // $orderIdsString = implode(",", $orderIds);
    // header("Location: savedaddress.php?user_id=$user_id&RoleID=$RoleID&order_ids=$orderIdsString");
    // exit();
}

$totalPrice = array_sum(array_map(function ($item) {
    return $item['quantity'] * $item['price'];
}, $cartItems));

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
    <title>cart new ekdum
    </title>
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
                            <a class="nav-link" href="#">register now </a>
                            <a class="nav-link" href="#">start selling </a>

                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="second-body">

            <h3 class="cart-heading">cart</h3>

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
                                        <th scope="col" class="text-right d-none d-md-block" width="200">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="row">
                                        <?php foreach ($cartItems as $cartItem): ?>
                                        <tr>
                                            <td>
                                                <figure class='itemside align-items-center'>
                                                    <div class='image'>
                                                        <img src="<?php echo 'data:image/png;base64,' . base64_encode($cartItem['image']); ?>"
                                                            alt="Product Image">
                                                    </div>
                                                </figure>
                                                <figcaption class='info'> <a href='#' class='title' data-abc='true'>
                                                        <?php echo $cartItem['title']; ?>.
                                                    </a></figcaption>
                                            </td>
                                            <td>
                                                <p class="Quantity">Quantity:
                                                    <?php echo $cartItem['quantity']; ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="price">$
                                                    <?php echo number_format($cartItem['quantity'] * $cartItem['price'], 2); ?>
                                                </p>
                                            </td>
                                            <td class='text-right d-none d-md-block'>
                                               
                                                    <button type="submit" class='btn btn-light' data-abc='true'>Remove
                                                 
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="order-summary">
                    <p>Order Summary</p>
                    <div class="sub">
                        <p>Subtotal</p>
                        <p>$
                            <?php echo number_format($totalPrice, 2); ?>
                        </p>
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
                        <p>$
                            <?php echo number_format($totalPrice, 2); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="checkout-button">
                <form action="savedaddress.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="RoleID" value="<?php echo $RoleID; ?>">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

                    <?php foreach ($cartItems as $cartItem): ?>
                    <input type="hidden" name="product_ids[]" value="<?php echo $cartItem['product_id']; ?>">
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary" name="place_order">Proceed to Checkout</button>
                </form>
            </div>
        </div>
</body>

</html>