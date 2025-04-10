<?php
function enviarEmail($email, $nombre, $asunto, $cuerpo) {
  global $footer_correo, $logo;

  require_once 'PHPMailer/PHPMailerAutoload.php';

  $mail = new PHPMailer();

  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Host = 'smtp.gmail.com';
  $mail->Port ="587";

  //Enable SMTP debugging
  // 0 = off (for production use)
  // 1 = client messages
  // 2 = client and server messages
  $mail->SMTPDebug = 0;

  $mail->Username = 'info_virtual@jesuministrosymas.com.ve';

  // $mail->Password = 'lplslibmiknyqcga'; //CLAVE INFO@
  $mail->Password = 'erzylelmyypnawsd';



  //User Email to use for SMTP authentication - Use the same Email used in Google Developer Console
  $mail->oauthUserEmail = "info@jesuministrosymas.com.ve";



  $mail->setFrom('info_virtual@jesuministrosymas.com.ve', 'Gestion de Recargas Telefonicas'); //Modificar
  $addressBCC = "jose@jesuministrosymas.com.ve";
  $mail->AddBCC($addressBCC, 'Control');
  $mail->addAddress($email, $nombre);

  //$mail->Encoding = 'base64';
  // $mail->base64_decode($cuerpo);
  //$mail->addCustomHeader('X-custom-header: custom-value');

  // $mail->WordWrap = 78;
  $mail->Encoding = "base64";
  $mail->CharSet = 'utf-8';
  $mail->MsgHTML($cuerpo);
  $mail->IsHTML(true);

  $mail->Subject = $asunto;
  $mail->Body    = $logo. $cuerpo . $footer_correo;

  //$mail->IsHTML(true);

  //send the message, check for errors
  if (!$mail->send()) {
    return true;
  } else {
    return false;
  }
  $mail->clearAddresses();
  $mail->clearAttachments();
}


?>
