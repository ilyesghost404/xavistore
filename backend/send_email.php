<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
require_once 'phpmailer/src/Exception.php';

function sendMailToClient($to, $name, $subject, $bodyHtml) {
    $mail = new phpmailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Gmail SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hmidilyes607@gmail.com';  // Replace with your Gmail address
        $mail->Password   = '1289ilyes';     // Replace with your generated app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        
        //Recipients
        $mail->setFrom('hmidilyes607@gmail.com', 'XaviStore');
        $mail->addAddress('hmidilyes4442@gmail.com', 'ilyes');

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur d'envoi de mail: {$mail->ErrorInfo}");
    }
}
