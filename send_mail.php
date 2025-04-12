<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'butunpreneur@gmail.com'; 
        $mail->Password   = 'jccr zsaz kirg wgdd';    // your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('butunpreneur@gmail.com', 'Website Contact Form');
        $mail->addAddress('butunprenuer@email.com'); // destination

        $mail->isHTML(true);
        $mail->Subject = 'New message from website contact form';

        $mail->Body = "
            <h2>New Message</h2>
            <p><strong>From:</strong> {$from}</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message) . "</p>
        ";
        $mail->AltBody = "From: {$from}\n\nMessage:\n{$message}";

        $mail->send();
        echo "<script>alert('Your message has been sent!'); window.history.back();</script>";
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
}
?>
