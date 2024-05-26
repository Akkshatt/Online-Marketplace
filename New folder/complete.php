<?php
if(isset($_GET['user_id']) && isset($_GET['RoleID'])) {
    $user_id = $_GET['user_id'];
    $RoleID = $_GET['RoleID'];

    require_once('conn.php'); // Include your database connection file
    $sql = "SELECT username FROM users WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $username = htmlspecialchars($row['username']);
    } else {
        // Handle database error, redirect to an error page, or log the error
        header("Location: error.php");
        exit();
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
    <title>Completed</title>
    <link rel="stylesheet" href="complete.css">
</head>
<body>
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">ORDER COMPLETED !</h4>
        <p><?php echo $username; ?> ORDER successfully ORDERED this important alert message.</p>
        <hr>
        <p class="mb-0">Move to home page <a href="./saved_address.php">buyer</a>.</p>
        <hr>
        <p class="mb-0">Move to dashboard <a href="#">dash</a>.</p>
    </div>
</body>
</html>
