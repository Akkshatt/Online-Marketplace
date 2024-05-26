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
    <link rel="stylesheet" href="orders.css">
    <title>ORDERedITEMS</title>   <!-- Head content goes here -->
</head>

<body>
    <div class="dash">
        <?php
        // Database connection parameters
        $servername = "localhost"; // Replace with your database server name
        $username = "root"; // Replace with your database username
        $password = "akshat12345"; // Replace with your database password
        $database = "project"; // Replace with your database name

        // Get user_id from the URL
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch user's orders from Orders table
            $sql = "SELECT * FROM Orders WHERE user_id = $user_id";
            $result = $conn->query($sql);

            // Check if there are rows in the result
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='main-box'>";
                    echo "<div class='details'>";
                    echo "<p>Order ID: " . $row["id"] . "</p>";
                    echo "<p>User ID: " . $row["user_id"] . "</p>";
                    echo "<p>Order Date: " . $row["order_date"] . "</p>";
                    echo "</div>";
                    echo "<div class='return-button'>";
                    echo "<button type='button' class='btn btn-primary'>Want to return</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No orders found for this user.";
            }

            // Close the database connection
            $conn->close();
        } else {
            echo "Invalid user ID.";
        }
        ?>
    </div>
</body>

</html>
