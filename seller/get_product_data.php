<?php
session_start();
require_once('../connection/connection.php');

$user_id = $_SESSION['user_id'];


$sql = "SELECT title, quantity FROM project.products WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'title' => $row['title'],
            'quantity' => $row['quantity']
        );
    }


    echo json_encode($data);
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>