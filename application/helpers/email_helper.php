<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('enviar_correo')) {
    function enviar_correo($destinatario, $asunto, $mensaje) {
        $CI =& get_instance();
        $CI->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'stephanyignacio2000@gmail.com'; 
        $config['smtp_pass'] = 'tu_contraseña'; 
        $config['mailtype'] = 'html';
        $config['charset']  = 'utf-8';
        $config['newline']  = "\r\n";
        $config['wordwrap'] = TRUE;

        $CI->email->initialize($config);

        $CI->email->from($config['smtp_user'], 'Nombre del Remitente');
        $CI->email->to($destinatario);
        $CI->email->subject($asunto);
        $CI->email->message($mensaje);

        return $CI->email->send();
    }
}
