<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="form.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <title>Product Submission</title>
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

        <div class="form-container">
            <div class="product-box">
                <h3>Product Submission Form</h3>
                <form class="one" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <label for="title">Product Title:</label>
          <input type="text" id="title" name="title" required><br><br>

          <label for="description">Product Description:</label><br>
          <input type="text" id="description" name="description" required><br><br>

          <label for="price">Price:</label>
          <input type="number" id="price" name="price" step="10" required><br><br>

          <label for="quantity">Quantity:</label>
          <input type="number" id="quantity" name="quantity" step="1" required><br><br>

          <label for="category">Category:</label>
          <select id="category" name="category" required>
            <option value="electronics">Electronics</option>
            <option value="clothing">Clothing</option>
            <option value="books">books</option>
            <option value="jewellery">Makeup</option>
            <option value="jewellery">jewellery</option>
            <option value="others">others</option>
          </select><br><br>

          <label for="image">Product Image:</label>
          <input type="file" id="image" name="image" accept="image/*" required><br><br>


          <input class="btn btn-primary" type="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>

    <?php
   
    session_start();
           require_once('../connection/connection.php');
           $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
       
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $category = $_POST['category'];

      
        $image = $_FILES["image"]["tmp_name"];
        $imgData = file_get_contents($image);

        
        $stmt = $conn->prepare("INSERT INTO products (user_id,  title, description, price, quantity, category, image) VALUES (?,  ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdiss", $user_id, $title, $description, $price, $quantity, $category, $imgData);
    

        if ($stmt->execute()) {
            echo "<script>alert('New record created successfully');</script>";
            echo "<script>window.open('formm.php', '_self')</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <footer class="white-section" id="footer">
        <div class="container-fluid">
            <i class='bx bxl-facebook'></i>
            <i class='bx bxl-twitter'></i>
            <i class='bx bxl-instagram'></i>
            <i class='bx bxl-gmail'></i>

            <p>© Copyright</p>
        </div>
    </footer>
</body>

</html>
