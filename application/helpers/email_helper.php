<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('enviar_correo')) {
    function enviar_correo($destinatario, $asunto, $mensaje) {
        $CI =& get_instance();
        $CI->load->library('email');

        $from_email = $CI->config->item('smtp_user');
        if (empty($from_email)) {
            log_message('error', 'El correo de origen (smtp_user) está vacío.');
            return false;
        }

        $CI->email->from($from_email, 'Nombre del Remitente');
        $CI->email->to($destinatario);
        $CI->email->subject($asunto);
        $CI->email->message($mensaje);

        return $CI->email->send();
    }

}
