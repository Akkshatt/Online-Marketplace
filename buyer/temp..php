<?php

session_start();

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../phpqrcode/qrlib.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$hostname = "localhost";
$username = "root";
$password = "akshat12345";
$dbname = "project";

$conn = new mysqli($hostname, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $user_query = "SELECT email FROM project.users where id = $user_id";
    $usertemp = mysqli_query($conn, $user_query);
    $user_temp = mysqli_fetch_assoc($usertemp);
    $useremail = $user_temp['email'];

    // Generate random private and public keys
    $privateKey = bin2hex(random_bytes(32));
    $publicKey = bin2hex(random_bytes(16));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cartQuery = "SELECT * FROM cart WHERE user_id = $user_id";
        $cartResult = mysqli_query($conn, $cartQuery);

        $paymentQuery = "SELECT * FROM paymentdetails WHERE user_id = $user_id";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        $rowPayment = mysqli_fetch_assoc($paymentResult);
        $billing_address_id = $rowPayment['billing_address_id'];
        $payment_details_id = $rowPayment['payment_id'];

        $productData = ''; // Variable to store encoded product data

        while ($rowCart = mysqli_fetch_assoc($cartResult)) {
            $product_id = $rowCart['id'];
            $quantity = $rowCart['quantity'];
            $timestamp = date("Y-m-d H:i:s");

            insertIntoOrders($user_id, $product_id, $quantity, $payment_details_id, $billing_address_id, $timestamp);

            $deleteCartQuery = "DELETE FROM cart WHERE user_id = $user_id ";
            mysqli_query($conn, $deleteCartQuery);

            // Get product details
            $product_query = "SELECT * FROM products WHERE id = $product_id";
            $product_result = mysqli_query($conn, $product_query);
            $product = mysqli_fetch_assoc($product_result);

            // Encode product data using the generated private key
            $encodedProduct = openssl_encrypt(json_encode($product), 'aes-256-cbc', $privateKey, 0, $privateKey);

            // Concatenate encoded product data
            $productData .= $encodedProduct . '|';
        }

        // Add generated public key to the encoded product data
        $combinedData = $publicKey . '|' . $productData;

        // Generate QR code
        $qrImagePath = generateQRCode($combinedData);

        // Send the QR code via email
        sendQRCodeByEmail($qrImagePath, $useremail);

        echo "<script>window.open('completion.php', '_self')</script>";
    }
}

function insertIntoOrders($user_id, $product_id, $quantity, $payment_details_id, $billing_address_id, $timestamp) {
    global $conn;
    $insertQuery = "INSERT INTO orders (user_id, product_id, quantity, payment_details_id, address_id, order_time) 
                    VALUES ('$user_id',  '$product_id', '$quantity', '$payment_details_id', '$billing_address_id', '$timestamp')";

    mysqli_query($conn, $insertQuery);
    
    $updateProductQuery = "UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id";
    mysqli_query($conn, $updateProductQuery);

    echo "<script>window.open('completion.php', '_self')</script>";
}

function generateQRCode($data)
{
    ob_start(); // Start output buffering
    QRcode::png($data, null, QR_ECLEVEL_L, 10, 2); // Generate QR code and output to buffer
    $imageData = ob_get_contents(); // Get the buffered image data
    ob_end_clean(); // End output buffering

    return $imageData;
}

function sendQRCodeByEmail($qrImageData, $recipientEmail)
{
    // Initialize PHPMailer
    $mailer = new PHPMailer(true);

    try {
        $mailer->IsSMTP(); // enable SMTP
        $mailer->SMTPAuth = true; // authentication enabled
        $mailer->SMTPSecure = 'ssl';
        $mailer->Host = 'smtp.gmail.com'; // SMTP server
        $mailer->Port = 465; // SMTP port
        $mailer->Username = 'app.verify.product@gmail.com';
        $mailer->Password = 'uivo fpvr afub okry';

        // Compose email
        $mailer->setFrom('app.verify.product@gmail.com', 'Product Verification');
        $mailer->AddAddress($recipientEmail);
        $mailer->Subject = "QR Code for Your Purchase";
        $mailer->IsHTML(true);

        // Email body with embedded QR code
        $body = "<p>Thank you for your purchase!</p>";
        $body .= "<p><img src='cid:qrcode' alt='QR Code'></p>";
        $body .= "<p>Other email content goes here.</p>";

        $mailer->MsgHTML($body);

        // Add QR code as inline image
        $mailer->AddStringEmbeddedImage($qrImageData, 'qrcode', 'qrcode.png', 'base64', 'image/png');

        // Send email
        $mailer->Send();

        echo '<script>console.log("Email sent successfully.");</script>';

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mailer->ErrorInfo}";
        error_log("Email sending error: {$mailer->ErrorInfo}");
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&family=Dancing+Script&family=Satisfy&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/ok.css">
    <title>QR Code Generator</title>
</head>
<body>
   <div class="dash">


  <div class="upper" id="upper-part">
            <!-- ... (your existing navigation bar) ... -->
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
    <div class="box1">
       <div class="box">
    <?php
     $amount = 0;
     $select_price = "SELECT * FROM cart WHERE user_id = '$user_id'";
     $result = mysqli_query($conn, $select_price);
     $result_count = mysqli_num_rows($result);
     if ($result_count > 0) {
         $quantities = array();
        
         while ($row = mysqli_fetch_array($result)) {
             $product_id = $row['id'];
             $select_products = "SELECT * FROM products WHERE id = $product_id";
             $result_products = mysqli_query($conn, $select_products);
             while ($row_product_price = mysqli_fetch_array($result_products)) {
                 $product_price = (float) $row_product_price['price'];
                 $product_title = $row_product_price['title'];
                 $product_image1 = $row_product_price['image'];
                 $quantity = (int) $row['quantity'];
                 $product_values = $product_price * $quantity;
                 $amount += $product_values;
             // Add any additional charges (e.g., shipping fee)
        $additional_charges = 40;
        $amount += $additional_charges;
                 // Store the quantity for each product in the cart
                 $quantities[$product_id] = $quantity;
             }}}
    // Set seller's information
    $upiId = "kakshat43@oksbi"; // Replace with the seller's actual UPI ID
    $phone = "6307257216";
    // $amount = "600";

    // Format payment information (assuming UPI format, customize as per your payment method)
    $paymentInfo = "upi://pay?pa=$upiId&mc=0000&tid=123456&tn=Payment&am=$amount&cu=INR";

    // Display payment details
    
    echo "<h2>Payment Details:</h2>";
    echo "<p>UPI ID: $upiId </p><br>";
    echo "<p>Phone Number: $phone</p><br>";
    echo "<p>Total Amount: $amount </p><br>";

    // Display QR code (using JavaScript to generate QR code for paymentInfo)
    echo '<div id="qrcode"></div>';
    echo '<script src="qrcode.min.js"></script>';
    echo '<script>';
    echo 'var qr = new QRCode(document.getElementById("qrcode"), {
                text: "' . $paymentInfo . '",
                width: 256,
                height: 256
            });';
    echo '</script>';
    ?>
    </div>
    <form action="" method="post">
       <button type="submit">Submit</button>
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
