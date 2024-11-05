<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function send() {
        $this->load->library('phpmailer_lib');
        $mail = $this->phpmailer_lib->load();
        
        // Configura SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'apaza.reychman.124@gmail.com';
        $mail->Password   = 'nexx ryea kwvj mmzu';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Desactivar verificación de certificado SSL
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Configura el correo
        $mail->setFrom('apaza.reychman.124@gmail.com', 'AgroFlori');
        $mail->addReplyTo('apaza.reychman.124@gmail.com', 'AgroFlori');
        $mail->addAddress('ignaciostephany127@gmail.com');
        $mail->Subject = 'Correo enviado desde tu proyecto';

        $mail->isHTML(true);
        $mailContent = "<h1>Correo electrónico de prueba</h1>
                        <p>Verificando que el correo se envía.</p>";
        $mail->Body = $mailContent;

        // Envía el correo
        if (!$mail->send()) {
            echo 'El mensaje no pudo ser enviado.';
            echo 'Error de correo: ' . $mail->ErrorInfo;
        } else {
            echo 'Mensaje enviado correctamente';
        }
    }
}

?>