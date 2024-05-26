<?php
require_once('../connection/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["request_id"])) {
    $request_id = $_GET["request_id"];

    $updateSql = "UPDATE forum SET likes = likes + 1 WHERE request_id = $request_id";

    if ($conn->query($updateSql) === TRUE) {
        echo "Product liked successfully";
        echo "<script>window.open('forum.php', '_self')</script>";
    } else {
        echo "Error updating likes: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request";
}
?>
