<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_lib {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
    }

    public function enviar_correo($destinatario, $asunto, $mensaje) {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'stephanyignacio2000@gmail.com';
        $config['smtp_pass'] = '20040317';
        $config['mailtype'] = 'html';
        $config['charset']  = 'utf-8';
        $config['newline']  = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->CI->email->initialize($config);

        $this->CI->email->from($config['smtp_user'], 'Nombre del Remitente');
        $this->CI->email->to($destinatario);
        $this->CI->email->subject($asunto);
        $this->CI->email->message($mensaje);

        return $this->CI->email->send();
    }
}
