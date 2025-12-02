<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//clase de envío de correos para las compras y el registro de clientes.

class Mailer
{
    function enviarEmail($correo, $asunto, $cuerpo)
    {
        require_once __DIR__ . '/../config/config.php';
        require  __DIR__ . '/../../datos/phpmailer/src/PHPMailer.php';
        require  __DIR__ . '/../../datos/phpmailer/src/SMTP.php';
        require  __DIR__ . '/../../datos/phpmailer/src/Exception.php';
        
        $mail = new PHPMailer(true);

        try {
            //Agregar datos del servidor
            $mail->SMTPDebug =  SMTP::DEBUG_OFF;  //SMTP::DEBUG_SERVER;                  //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USER;                     //SMTP username
            $mail->Password   = MAIL_PASS;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(MAIL_USER, 'TruckParts MX');
            $mail->addAddress($correo);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;


            $mail->Body = mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-1');

            if($mail->send()){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            echo "Error al enviar el correo electrónico de la compra: {$mail->ErrorInfo}";
            return false;
            //exit;
        }
    }
}