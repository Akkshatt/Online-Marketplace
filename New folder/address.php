<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('conn.php');

    // Check if the required parameters are set
    if (isset($_GET['user_id'], $_GET['RoleID'], $_GET['order_id'], $_POST['address_line1'], $_POST['city'], $_POST['state'], $_POST['postal_code'], $_POST['country'])) {
        // Sanitize and retrieve form input values
        $user_id = $_GET['user_id'];
        $RoleID = $_GET['RoleID'];
        $order_id = $_GET['order_id'];
        $address_line1 = $conn->real_escape_string($_POST['address_line1']);
        $address_line2 = $conn->real_escape_string($_POST['address_line2']);
        $city = $conn->real_escape_string($_POST['city']);
        $state = $conn->real_escape_string($_POST['state']);
        $postal_code = $conn->real_escape_string($_POST['postal_code']);
        $country = $conn->real_escape_string($_POST['country']);

        // SQL query to insert data into the Addresses table using prepared statements
        $sql = "INSERT INTO Addresses (user_id, address_line1, address_line2, city, state, postal_code, country)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $user_id, $address_line1, $address_line2, $city, $state, $postal_code, $country);

        if ($stmt->execute()) {
            // Get the ID of the last inserted address
            $address_id = $conn->insert_id;

            // Update the orders table with the address ID using prepared statement
            $updateOrderSql = "UPDATE orders SET address_id = ? WHERE user_id = ? AND order_id = ?";
            $updateStmt = $conn->prepare($updateOrderSql);
            $updateStmt->bind_param("iii", $address_id, $user_id, $order_id);

            if ($updateStmt->execute()) {
                header("Location: ./transcation.php?user_id=".$user_id."&RoleID=".$RoleID."&order_id=".$order_id);
                exit();
            } else {
                echo "Error updating order: " . $updateStmt->error;
            }
        } else {
            echo "Error inserting address: " . $stmt->error;
        }

        $stmt->close();
        $updateStmt->close();
    } else {
        echo "Required parameters are missing.";
    }

    $conn->close();
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="address.css">
    <title>Address Form</title>
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
        <div class="second">
            <div class="container">
                <h1>Address Form</h1>
                <form action="transcation.php" method="get">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="RoleID" value="<?php echo $RoleID; ?>">
                    <input type="text" placeholder="Address Line 1" id="address_line1" name="address_line1" required><br>
                    <input type="text" placeholder="Address Line 2:" id="address_line2" name="address_line2"><br>
                    <input type="text" placeholder="City:" id="city" name="city" required><br>
                    <input type="text" placeholder="State:" id="state" name="state" required><br>
                    <input type="text" placeholder="Postal Code:" id="postal_code" name="postal_code" required><br>
                    <input type="text" placeholder="Country:" id="country" name="country" required><br>
                    <input type="submit" id="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>

  

</body>

</html>
