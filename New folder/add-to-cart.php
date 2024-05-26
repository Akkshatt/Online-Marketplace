<?php
// require_once('conn.php');

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
//     $product_id = $_POST['product_id'];
//     $quantity = $_POST['quantity'];
   
   
//     $user_id = $_SESSION['user_id']; 
    
//     $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
//     $stmt->bind_param("iii", $user_id, $product_id, $quantity);

//     if ($stmt->execute()) {
//         echo "Product added to cart successfully";
//          header("Location:./cart.php?user_id=".$user_id. "cart_id"=.$id);
//         exit();
//     } else {
//         echo "Error: " . $stmt->error;
//     }

//     $stmt->close();
// }



session_start();
require_once('conn.php');
$user_id=$_SESSION['user_id'];
if(isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['user_id']) && isset($_POST['RoleID'])) {
    $productId = $_POST['id'];
    $quantity = $_POST['quantity'];
    $role = $_POST['RoleID'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO cart (user_id, id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $productId, $quantity);

    // Execute the prepared statement
    // if ($stmt->execute()) {
    //     echo "Product added to the cart successfully.";
    // } else {
    //     echo "Error adding product to the cart: " . $conn->error;
    // }
    if ($stmt->execute()) {
        $cartId = $stmt->insert_id; // Get the ID of the inserted cart item
        echo json_encode(array("success" => true, "cartId" => $cartId));
    } else {
        echo json_encode(array("success" => false, "error" => "Error adding product to the cart: " . $conn->error));
    }
    
    // Close the prepared statement
    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();

?>

