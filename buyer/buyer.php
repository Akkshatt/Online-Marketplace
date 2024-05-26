<?php
session_start();
require_once('../connection/connection.php');
$user_id = $_SESSION['user_id'];
$select_query = "select role from userroles where user_id =$user_id";
$result=mysqli_query($conn,$select_query);
$fetching = mysqli_fetch_assoc($result);
$role=$fetching['role'];

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';


$sql = "SELECT * FROM products WHERE user_id != $user_id";
if (!empty($categoryFilter)) {
    $sql .= " AND category = '$categoryFilter'";
}
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:ital,wght@0,300;0,700;1,400&family=Saira+Extra+Condensed:wght@100;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/buyer.css">
    <title>Buyer</title>
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

                <a href="./orders.php">orders</a>
            </div>
           
        </div>
      
        <!-- <div class="bocs">
         
            <div class="image-carousel">
                <div class="image-slide">
                    <img src="../images/three.jpg" alt="Image 1">
                </div>
                <div class="image-slide">
                    <img src="../images/four.jpg" alt="Image 2">
                </div>
                <div class="image-slide">
                    <img src="../images/three.jpg" alt="Image 3">
                </div>
                <div class="image-slide">
                    <img src="../images/four.jpg" alt="Image 1">
                </div>
                <div class="image-slide">
                    <img src="../images/five.jpg" alt="Image 1">
                </div>
            </div>
        </div> -->

        <div class="filter-container">
            <label for="category">Select Category:</label>
            <select id="category" name="category" onchange="filterProducts(this.value)">
                <option value="" <?php echo ($categoryFilter === '') ? 'selected' : ''; ?>>All Products</option>
                <option value="electronics" <?php echo ($categoryFilter === 'electronics') ? 'selected' : ''; ?>>Electronics</option>
                <option value="books" <?php echo ($categoryFilter === 'books') ? 'selected' : ''; ?>>Books</option>
                <option value="clothing" <?php echo ($categoryFilter === 'clothing') ? 'selected' : ''; ?>>Clothing</option>
            </select>
        </div>
           
      
   
        <div class="product-container">
            <?php
            if ($all_product->num_rows > 0) {
                while ($row = $all_product->fetch_assoc()) {
                   
                    echo '<div class="product-card">
                        <div class="image-card">';
                    if ($row['image'] != NULL){
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="' . $row['title'] . '">';
                    } else {
                        echo '<img src="./images/Image_not_available.png" alt="' . $row['title'] . '">';
                    }
                    echo '</div>
                        <div class="captions">
                            <h2 class="Product-Name">' . $row['title'] . '</h2>
                            <p class="price">' . $row['price'] . '</p>
                        </div>
                        <div class="cart-actions">
                            <button class="quantity-button" id="decrease-' . $row['id'] . '" onclick="changeQuantity(' . $row['id'] . ', -1)"><i class="fas fa-minus" style="color: #e92b47;"></i></button>
                            <span id="quantity-' . $row['id'] . '">0</span>
                            <button class="quantity-button" id="increase-' . $row['id'] . '" onclick="changeQuantity(' . $row['id'] . ', 1)"><i class="fas fa-plus" style="color: #23d416;"></i>
                            </button>
                            <button class="add-to-cart-button" onclick="addToCart(' . $row['id'] . ')">Add to Cart</button>
                        </div>
                    </div>';
                }



            }
            
            
            
            else {
                echo "No products found in the database.";
            }
            ?>
            <script>
                var quantities = {}; 

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
    var userId = <?php echo $user_id; ?>;
    var RoleID = <?php echo json_encode($role); ?>; 

    // Send AJAX request to add-to-cart.php with productId, quantity, userId, and RoleID
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
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
function filterProducts(category) {
        window.location.href = 'buyer.php?category=' + encodeURIComponent(category);
    }
            </script>
        </div>
        <!-- <form action="cart.php" method="get">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="RoleID" value="<?php echo $role; ?>">
            <button type="submit" class="move-to-cart-button" >Move to Cart</button>
        </form> -->
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
