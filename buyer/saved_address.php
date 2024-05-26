<?php
require_once('../connection/connection.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


    
$user_query = "SELECT username, profile_picture FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);


$username = $user_data['username'];
$profile_picture = $user_data['profile_picture'];

    $select_query = "SELECT username FROM users WHERE id = $user_id";
    $result_query = mysqli_query($conn, $select_query);
    $result = mysqli_fetch_assoc($result_query);

    $main_query = "SELECT * FROM addresses WHERE user_id = $user_id";
    $resultant_query = mysqli_query($conn, $main_query);

    if (isset($_POST['submit'])) {
        
        if (isset($_POST['billing_address_id']) && !empty($_POST['billing_address_id'])) {
          
            $billing_address_ids = $_POST['billing_address_id'];
            $billing_address_ids = array_map(function ($id) use ($conn) {
                return mysqli_real_escape_string($conn, $id);
            }, $billing_address_ids);

            foreach ($billing_address_ids as $billing_address_id) {
                $select_query_check = "SELECT * FROM `paymentdetails` WHERE user_id = $user_id AND billing_address_id = '$billing_address_id'";
                $check = mysqli_query($conn, $select_query_check);
                $result_mains = mysqli_fetch_assoc($check);

                if ($result_mains) {
                    // echo "<script>alert('For $user_id, address is already existed with id $billing_address_id')</script>";
                    echo "<script>window.open('trans.php', '_self')</script>";
                } else {
                    $insert_query = "INSERT INTO `paymentdetails`(`user_id`, `billing_address_id`, `payment_process_time`)
                                    VALUES ('$user_id', '$billing_address_id', NOW())";
                    $result_main = mysqli_query($conn, $insert_query);
                    echo "<script>window.open('trans.php', '_self')</script>";

                    if ($result_main) {
                        echo "<script>alert('For $user_id, address is successfully inserted with id $billing_address_id')</script>";
                        echo "<script>window.open('trans.php', '_self')</script>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            }
        } else {
            echo "<script>alert('Please select at least one address')</script>";
        }



    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
            crossorigin="anonymous">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link
            href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="../css/savedaddress.css">
        <title>saved address</title>
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

            <div class="right-up">
    
              <div class="image">
        
            <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" alt="Profile Picture">
          </div>
 
           <div class="account user name"><?php echo $username; ?></div>
            </div>
            <div class="head">
            <p>Select Address Given Below</p><br>
            </div>
            <div class="main-box">
                <form action="" method="POST">
                  
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="address-row">
                    <?php
                    
                    while ($row = mysqli_fetch_assoc($resultant_query)) {
                        
                        $billing_address_id = $row['billing_address_id'];
                        $adress1 = $row['address_line1'];
                        $adress2 = $row['address_line2'];
                        $city = $row['city'];
                        $state = $row['state'];
                        $pincode =$row['pincode'];
                        echo"<div class='address-box'>";
                        echo "<p> $adress1</p>";
                        // echo "<p>$adress2</p><br>";
                        echo "<p> $city</p>";
                        echo "<p> $state</p>";
                        echo "<p> $pincode</p>";

                        echo "<input type='checkbox' name='billing_address_id[]' value='$billing_address_id'><br>";
                        echo"</div>";

                        // echo "<option value='$billing_address_id'>$billing_address_id</option>";
                    }
                    ?>
                    </div>
                    <div class="center-button">
                    <button type="submit" name="submit" value="submit">Submit</button>
                </div>
                </form>
            </div>
        </div>   
    </body>
    <footer class="white-section" id="footer">
    <div class="container-fluid">
        <i class='bx bxl-facebook'></i>
        <i class='bx bxl-twitter'></i>
        <i class='bx bxl-instagram'></i>
        <i class='bx bxl-gmail'></i>
        <p>© Copyright</p>
    </div>
</footer>

    </html>
<?php
} else {
    echo "<script>alert('user_id is not available')</script>";
    echo "User ID not set in the session.";
}
?>
