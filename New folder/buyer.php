<?php
session_start();
require_once('conn.php');

if(isset($_GET['UserID']) && isset($_GET['RoleID'])) {
    $user_id = $_GET['UserID'];
    $role = $_GET['RoleID'];
} else {
  
    header("Location: error.php");
    exit();
}

// $sql = "SELECT * FROM products";
// $all_product = $conn->query($sql);


$sql = "SELECT * FROM products WHERE user_id != $user_id";
$all_product = $conn->query($sql);




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
    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="buyer.css">
    <title>Buyer</title>
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
                <a href="cart.php">cart</a>

                <a href="">Contact</a>
            </div>
            <div class="nsec3">
                <input type="search" id="gsearch" name="gsearch" placeholder="search ...">


            </div>
        </div>
        <!-- <nav class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand">DOODLE</a>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-dark" type="submit">Search</button>
                </form>
            </div>
        </nav>
        <div class="nn2">
            <div class="n2">
                <div class="hh1">
                    <a><i class='bx bxs-truck'></i></a>
                </div>
                <div class="search-button">
                    <input type="text" id="searchInput" placeholder="Enter search term">
                    <button id="searchButton">Search</button> 
                </div>
                <div class="hh3">
                    <a><i class='bx bx-cart'></i>cart</a>
                </div>
                <div class="hh4">
                    <a><i class='bx bx-package'></i>orders</a>
                </div>
            </div>
        </div> -->
        <!-- <div class="image-carousel">
        <img src="images/one.jpg" alt="Image 1">
        <img src="images/two.jpg" alt="Image 2">
        <img src="images/three.jpg" alt="Image 3">
        
      </div> -->
        <!-- <div class="image-carousel">
        <div class="image-slide">
            <img src="images/one.jpg" alt="Image 1">
        </div>
        <div class="image-slide">
            <img src="images/two.jpg" alt="Image 2">
        </div>
        <div class="image-slide">
            <img src="images/three.jpg" alt="Image 3">
        </div>
        <div class="image-slide">
            <img src="images/four.jpg" alt="Image 1">
        </div>
        <div class="image-slide">
            <img src="images/five.jpg" alt="Image 1">
        </div>
     </div> -->
        <div class="bocs">
            <!-- <img src="images/dd.jpeg" alt="bocs"> -->
            <div class="image-carousel">
                <div class="image-slide">
                    <img src="images/three.jpg" alt="Image 1">
                </div>
                <div class="image-slide">
                    <img src="images/four.jpg" alt="Image 2">
                </div>
                <div class="image-slide">
                    <img src="images/three.jpg" alt="Image 3">
                </div>
                <div class="image-slide">
                    <img src="images/four.jpg" alt="Image 1">
                </div>
                <div class="image-slide">
                    <img src="images/five.jpg" alt="Image 1">
                </div>
            </div>
        </div>

        <div class="product-container">
            <?php
            if ($all_product->num_rows > 0) {
                while ($row = $all_product->fetch_assoc()) {
                    // Replace this part with your actual product card HTML structure
                    echo '<div class="product-card">
                        <div class="image-card">';
                    if ($row['image'] != NULL){
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['title'] . '">';
                    } else {
                        echo '<img src="./images/Image_not_available.png" alt="' . $row['title'] . '">';
                    }
                    echo '</div>
                        <div class="captions">
                            <h2 class="Product Name">' . $row['title'] . '</h2>
                            <p class="price">' . $row['price'] . '</p>
                        </div>
                        <div class="cart-actions">
                            <button class="quantity-button" id="decrease-' . $row['id'] . '" onclick="changeQuantity(' . $row['id'] . ', -1)">-</button>
                            <span id="quantity-' . $row['id'] . '">0</span>
                            <button class="quantity-button" id="increase-' . $row['id'] . '" onclick="changeQuantity(' . $row['id'] . ', 1)">+</button>
                            <button class="add-to-cart-button" onclick="addToCart(' . $row['id'] . ')">Add to Cart</button>
                        </div>
                    </div>';
                }
            } else {
                echo "No products found in the database.";
            }
            ?>
            <script>
                var quantities = {}; // Store quantities for each product

                function changeQuantity(productId, change) {
                    if (!quantities[productId]) {
                        quantities[productId] = 0;
                    }
                    quantities[productId] += change;
                    if (quantities[productId] < 0) {
                        quantities[productId] = 0;
                    }
                    document.getElementById('quantity-' + productId).innerText = quantities[productId];
                }
                function addToCart(productId) {
    var quantity = quantities[productId] || 0;
    var userId = <?php echo $user_id; ?>; // Get user ID from PHP variable
    var RoleID = <?php echo json_encode($role); ?>; // Get RoleID from PHP variable

    // Send AJAX request to add-to-cart.php with productId, quantity, userId, and RoleID
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add-to-cart.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText); // Show response from add-to-cart.php (success or error message)
        }
    };

    // Send product_id, quantity, user_id, and RoleID in the request
    xhr.send("id=" + productId + "&quantity=" + quantity + "&user_id=" + userId + "&RoleID=" + RoleID);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var cartId = response.cartId;
                    alert("Product added to the cart successfully. Cart ID: " + cartId);
                    // You can use the cartId as needed in your buyer page
                } else {
                    alert("Error: " + response.error);
                }
            } else {
                alert("Error occurred while adding product to the cart. Please try again later.");
            }
        }
    };
}

            </script>
        </div>
        <form action="marora.php" method="get">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="RoleID" value="<?php echo $role; ?>">
            <button type="submit" class="move-to-cart-button">Move to Cart</button>
        </form>
        <!-- <div class="container-fluid">
        <i class='bx bxl-facebook'></i>
        <i class='bx bxl-twitter'></i>
        <i class='bx bxl-instagram'></i>
        <i class='bx bxl-gmail'></i>

        <p>© Copyright</p>
    </div> -->
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
