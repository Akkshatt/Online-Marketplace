<?php
require_once('conn.php');

if (isset($_POST['place_order'])) {
    $user_id = $_GET['user_id'];
    $RoleID = $_GET['RoleID'];
    $product_ids = $_GET['product_ids'];

    // Insert order into the order table
    $insertOrderSql = "INSERT INTO orders (user_id, product_id) VALUES ";
    $values = [];
    foreach ($product_ids as $product_id) {
        $values[] = "($user_id, $product_id)";
    }
    $insertOrderSql .= implode(", ", $values);

    if ($conn->query($insertOrderSql)) {
        // Get the ID of the last inserted order
        $order_id = $conn->insert_id;

        // Redirect to order confirmation page along with the order ID
        header("Location: address.php?user_id=".$user_id."&RoleID=".$RoleID."&order_id=".$order_id);

        exit();
    } else {
        // Handle the error, maybe redirect to an error page
        header("Location: error.php");
        exit();
    }
} else {
    // Invalid request, redirect to an error page
    header("Location: error.php");
    exit();
}
