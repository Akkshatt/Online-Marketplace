<?php
require_once('../connection/connection.php');
session_start();
$user_id = $_SESSION['user_id'];

$sql = "SELECT DATE(order_time) as order_date, COUNT(DISTINCT product_id) as product_count 
        FROM orders 
        WHERE user_id = $user_id 
        GROUP BY order_date";

$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data['dates'][] = $row['order_date'];
    $data['quantities'][] = $row['product_count'];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
