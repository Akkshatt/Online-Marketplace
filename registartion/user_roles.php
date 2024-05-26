<?php
require_once('../connection/connection.php');
session_start();
if(isset($_SESSION['user_id'])){
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id=$_SESSION['user_id'];
    $role = $_POST['role'];

  
    $user_id = $conn->real_escape_string($user_id);
    $role = $conn->real_escape_string($role);


    $checkIfExistsSQL = "SELECT id FROM userroles WHERE user_id = '$user_id' AND role = '$role'";
    $result = $conn->query($checkIfExistsSQL);

    if ($result->num_rows > 0) {
       
        $row = $result->fetch_assoc();
        $id = $row["id"];
        echo "<script>alert('Entry already exists with id: $id');</script>";


        if ($role == 'buyer') {
            header("Location: ../buyer/buyer.php?UserID=" . $user_id . "&RoleID=" . $role);
            exit();
        } elseif ($role == 'seller') {
            header("Location: ../seller/dashboard.php?UserID=" . $user_id . "&RoleID=" . $role);
            exit();
        }
    } else {

        $insertSQL = "INSERT INTO userroles (user_id, role) VALUES ('$user_id', '$role')";
        if ($conn->query($insertSQL) === TRUE) {
            echo "<script>alert('New record inserted successfully');</script>";

           
            if ($role == 'buyer') {
                header("Location: ../buyer/buyer.php?UserID=" . $user_id . "&RoleID=" . $role);
                exit();
            } elseif ($role == 'seller') {
                header("Location: ../seller/dashboard.php?UserID=" . $user_id . "&RoleID=" . $role);  
                exit();
            }
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }


    $conn->close();
}
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/user_role.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>User Role Form</title>
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

        <div class="box">
            <h1>User Role Selection</h1>
            <form action="" method="POST">
                 
                <!-- <p for="user_id"> Username: <?php echo $_GET['username']; ?></p> -->
               
                <!-- <input type="text" id="user_id" name="user_id" ><br><br> -->

                <!-- <label for="role">Role:</label> -->
                <select id="role" name="role" required>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select><br><br>
                <div class="button-container">
                    <input type="submit" name="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>

   

</body>

</html>
