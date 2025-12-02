<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../datos/phpmailer/src/PHPMailer.php';
require_once '../../datos/phpmailer/src/SMTP.php';
require_once '../../datos/phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
       //Agregar datos del servidor
       $mail->SMTPDebug = SMTP::DEBUG_OFF;                    //Enable verbose debug output
       $mail->isSMTP();                                            //Send using SMTP
       $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
       $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
       $mail->Username   = MAIL_USER;                     //SMTP username
       $mail->Password   = MAIL_PASS;                               //SMTP password
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
       $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(MAIL_USER, 'TruckParts MX');
    $mail->addAddress($correo);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalle de su compra';

    $cuerpo = '<h4>GRACIAS POR SU COMPRA</h4>';
    $cuerpo .= '<p>El ID de su compra es: <b>'. $id_transaccion .'</b></p>';

    $mail->Body    = mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-1');
    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electrónico de la compra: {$mail->ErrorInfo}";
    //exit;
}
