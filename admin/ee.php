<?php
// Import PHPMailer. This may change if you're not using composer
require_once '../funciones/PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->Host = 'smtp1.s.ipzmarketing.com';
    $mail->Username = 'ybpgmvpgvmej';
    $mail->Password = 'jvtfqm6o'; // Set your SMTP password here

    $mail->setFrom('jose@jesuministrosymas.com.ve');
    $mail->addAddress('jose@jesuministrosymas.com.ve');

    $mail->isHTML(true);
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
?>
