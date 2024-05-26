<?php
require_once('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        if (isset($_POST['submit'])) {
            // Check if selectedAddresses is set and not empty
            if (isset($_POST['selectedAddresses']) && is_array($_POST['selectedAddresses']) && !empty($_POST['selectedAddresses'])) {
                // Get the array of order IDs
                $order_ids = $_POST['selectedAddresses'];

                // Loop through order IDs
                foreach ($order_ids as $order_id) {
                    // Example: Update 'address_id' for each order ID
                    $updateOrderSql = "UPDATE orders SET address_id = ? WHERE user_id = ? AND id = ?";
                    $stmt = $conn->prepare($updateOrderSql);
                    $stmt->bind_param("iii", $addressId, $user_id, $order_id);
                    $stmt->execute();
                    $stmt->close();
                }

                // Redirect to savedaddress.php with user_id
                header("Location: savedaddress.php?user_id=".$user_id);
                exit();
            } else {
                echo "No addresses selected.";
            }
        } else {
            echo "Form not submitted.";
        }
    } else {
        echo "Invalid input.";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add your CSS and other head content here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="savedaddress.css">
    <title>saved address</title>
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

        <div class="horizontal"></div>

        <div class="main-box">
            <form action="trans.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

                <?php
                require_once('conn.php');

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['order_id'])) {
                    $user_id = $_POST['user_id'];
                    $order_id = $_POST['order_id'];

                    $sql = "SELECT * FROM addresses WHERE user_id = $user_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        foreach ($result as $row) {
                            if (array_key_exists('id', $row)) {
                                $addressId = $row['id'];
                            }

                            echo "<div class='details'>";
                            echo "<p>Address 1: " . $row["address_line1"] . "</p>";
                            echo "<p>Address 2: " . $row["address_line2"] . "</p>";
                            echo "<div class='drow'>";
                            echo "<p>City: " . $row["city"] . "</p>";
                            echo "<p>State: " . $row["state"] . "</p>";
                            echo "<p>Pincode: " . $row["pincode"] . "</p>";
                            echo "</div>";
                            echo "<label>";
                            echo "<input type='checkbox' name='selectedAddresses[]' value='$addressId'>";
                            echo "Address ID: $addressId";
                            echo "</label><br>";
                            echo '</div>';
                        }
                    } else {
                        echo "No addresses found.";
                    }

                    $conn->close();
                } else {
                    echo "Invalid input.";
                }
                ?>

                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>

    <?php
    require_once('conn.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        if (isset($_POST['user_id'], $_POST['order_id'])) {
            $user_id = $_POST['user_id'];
            $order_id = $_POST['order_id'];

            if (isset($_POST['selectedAddresses']) && !empty($_POST['selectedAddresses'])) {
                foreach ($_POST['selectedAddresses'] as $addressId) {
                    $insertOrderSql = "INSERT INTO orders (user_id, address_id) VALUES ($user_id, $addressId)";
                    $conn->query($insertOrderSql);
                }

                header("Location: trans.php?user_id=$user_id&order_id=$order_id");
                exit();
            } else {
                echo "No addresses selected.";
            }
        } else {
            echo "Invalid input.";
        }
    }
    ?>
</body>

</html>
