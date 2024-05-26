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
    <link rel="stylesheet" href="return.css">
    <title>orders page

    </title> <!-- ... (your head content remains unchanged) ... -->
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
            <p>Account</p>
            <p class="account user name">account user name</p>
        </div>

        <div class="horizontal"></div>   <!-- ... (your existing HTML content remains unchanged) ... -->

        <div class="main-box">
            <?php
             require_once('conn.php');
             $user_id = $_GET['user_id'];
            // SQL query to fetch data from orders table and related tables
            $sql = "SELECT p.product_name, p.title AS product_title, o.order_time, a.address 
            FROM orders o
            INNER JOIN products p ON o.product_id = p.id
            INNER JOIN addresses a ON o.address_id = a.id
            WHERE o.user_id = $user_id";
            $result = $conn->query($sql);

            // Display orders data
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='details'>";
                    echo "<p>Product Name: " . $row['product_name'] . "</p>";
                    echo "<p>Product Title: " . $row['product_title'] . "</p>";
                    echo "<p>Order Time: " . $row['order_time'] . "</p>";
                    echo "<p>Address: " . $row['address'] . "</p>";
                    echo "</div>";
                    echo "<div class='return-button'>";
                    echo "<button type='button' class='btn btn-primary'>Return</button>";
                    echo "</div>";
                }
            } else {
                echo "No orders found.";
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>

    </div>
</body>

</html>
