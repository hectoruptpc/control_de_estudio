<?php
function enviarEmail($email, $nombre, $asunto, $cuerpo) {
    global $footer_correo, $logo;
    
    require_once 'PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer();
    

    // Configuración SMTP con TU cuenta
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = "587";
    $mail->SMTPDebug = 0; // 0 = off (producción), 2 = solo para pruebas
    
    // ==========================================
    // TUS DATOS (los mismos que funcionaron)
    // ==========================================
    $mail->Username = 'hectorlamaquina14@gmail.com';
    $mail->Password = 'tjml yrrt gcum ulgf'; // Tu contraseña de 16 dígitos
    
    // Quién envía el correo
    $mail->setFrom('hectorlamaquina14@gmail.com', 'Gestion de Recargas Telefonicas');
    
    // Destinatarios
    $mail->addAddress($email, $nombre);
    $mail->addBCC('herrejose@gmail.com', 'Control'); // Si aún quieres enviar copia
    
    // Configuración del mensaje
    $mail->Encoding = "base64";
    $mail->CharSet = 'utf-8';
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body = ($logo ?? '') . $cuerpo . ($footer_correo ?? '');
    
    // Enviar
    if ($mail->send()) {
        return true;  // Éxito
    } else {
        error_log("Error al enviar email: " . $mail->ErrorInfo);
        return false; // Error
    }
}
?>