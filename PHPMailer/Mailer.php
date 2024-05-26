<?php

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'app.verify.product@gmail.com';
        $mail->Password   = 'uivo fpvr afub okry';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('app.verify.product@gmail.com', 'Product Verification');
        $mail->addAddress($to);     // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// // Example usage
// $result = sendEmail('recipient@example.com', 'Test Subject', 'This is a test email.');

// if ($result === true) {
//     echo 'Email sent successfully.';
// } else {
//     echo 'Error: ' . $result;
// }

?>
