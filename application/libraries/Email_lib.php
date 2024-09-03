<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_lib {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
    }

    public function enviar_correo($destinatario, $asunto, $mensaje) {
            // Inicializa la configuración de email solo si necesitas sobrescribirla
            $this->CI->email->from($this->CI->config->item('smtp_user'), 'Nombre del Remitente');
            $this->CI->email->to($destinatario);
            $this->CI->email->subject($asunto);
            $this->CI->email->message($mensaje);
    
            if ($this->CI->email->send()) {
                return true;
            } else {
                log_message('error', $this->CI->email->print_debugger());
                return false;
            }
        }
    }
