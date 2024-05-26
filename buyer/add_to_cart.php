<?php



session_start();
require_once('../connection/connection.php');
$user_id=$_SESSION['user_id'];
if(isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['user_id']) && isset($_POST['RoleID'])) {
    $productId = $_POST['id'];
    $quantity = $_POST['quantity'];
    $role = $_POST['RoleID'];

   
    $stmt = $conn->prepare("INSERT INTO cart (user_id, id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $productId, $quantity);

   
    if ($stmt->execute()) {
        $cartId = $stmt->insert_id; 
        echo json_encode(array("success" => true, "cartId" => $cartId));
    } else {
        echo json_encode(array("success" => false, "error" => "Error adding product to the cart: " . $conn->error));
    }
    
  
    $stmt->close();
} else {
    echo "Invalid request.";
}


$conn->close();

?>

