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
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"> <i class='bx bxs-truck'></i></a>
                <a class="navbar-brand" href="#"> doodle</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Sell now </a>
            </li>


          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-dark" type="submit">Search</button>
          </form>
        </div>
            </div>
        </nav>

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
            <option value="books">Books</option>

          </select><br><br>

          <label for="image">Product Image:</label>
          <input type="file" id="image" name="image" accept="image/*" required><br><br>


          <input class="btn btn-primary" type="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "akshat12345";
    $database = "project";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get form data
        $user_id = $_GET['user_id'];
        $role_id = $_GET['RoleID'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $category = $_POST['category'];

        // Get image data
        $image = $_FILES["image"]["tmp_name"];
        $imgData = file_get_contents($image);

        // Insert data into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO products (user_id,  title, description, price, quantity, category, image) VALUES (?,  ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissdiss", $user_id, $title, $description, $price, $quantity, $category, $imgData);
    

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Close the connection
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
