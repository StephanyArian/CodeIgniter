<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function login() { 
        $this->load->view('auth/login');
    }


    public function login_process() {
        $nombre_usuario = $this->input->post('NombreUsuario');
        $clave = $this->input->post('Clave');
        
        // Obtiene los datos del usuario por su nombre de usuario
        $usuario = $this->user_model->get_user($nombre_usuario,$clave);
        
        // Verifica si el usuario existe y si la contraseña es correcta
        if (!$usuario) {
            $this->session->set_flashdata('error', 'Nombre de usuario o contraseña incorrectos.');
            redirect('auth/login');
            return;
        }
    
        // Verifica la contraseña
        if (!password_verify($clave, $usuario->Clave)) {
            log_message('error', 'Contraseña incorrecta para el usuario: ' . $nombre_usuario);
            $this->session->set_flashdata('error', 'Nombre de usuario o contraseña incorrectos.');
            redirect('auth/login');
            return;
        }
    
        // Verifica si el correo electrónico ha sido verificado
        if ($usuario->TokenVerificacion !== NULL) {
            log_message('error', 'Correo no verificado para el usuario: ' . $nombre_usuario);
            $this->session->set_flashdata('error', 'Debe verificar su email antes de ingresar.');
            redirect('auth/login');
            return;
        }
    
        // Inicia la sesión del usuario
        $this->session->set_userdata('logged_in', true);
        $this->session->set_userdata('usuario_id', $usuario->idUsuarios);
        $this->session->set_userdata('nombre_usuario', $usuario->NombreUsuario);
        
        // Redirige al usuario a la página de inicio o a donde sea necesario
        if ($usuario->ClaveCambiada == FALSE) {
            redirect('usuario/cambiar_contrasena');
        } else {
            redirect('inicio');
        }
    }
    
    

    public function register() {
        $this->load->view('auth/register');
    }

    public function register_process() {
        $data = array(
            'PrimerApellido' => $this->input->post('PrimerApellido'),
            'SegundoApellido' => $this->input->post('SegundoApellido'),
            'Nombres' => $this->input->post('Nombres'),
            'Email' => $this->input->post('Email'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Clave' => password_hash($this->input->post('Clave'), PASSWORD_BCRYPT),
            'Rol' => 'U',
            'Estado' => '1',
            'FechaCreacion' => date('Y-m-d H:i:s'),
            'IdUsuarioAuditoria' => 1 // ID del usuario administrador creando la cuenta
        );
        $this->user_model->insert_user($data);
        $this->session->set_flashdata('success', 'Registration successful. Please login.');
        redirect('auth/login');
    }
    

    public function logout() {
        $this->session->sess_destroy();
        //$this->session->unset_userdata('idUsuarios');
        redirect('auth/login');
    }

    public function verificar($token) {
        $usuario = $this->Usuario_model->verificar_usuario($token);
        
        if ($usuario) {
            $data = array(
                'TokenVerificacion' => NULL
            );
            $this->Usuario_model->modificar_usuario($usuario->idUsuarios, $data);
            echo 'Cuenta verificada exitosamente. Ahora puede iniciar sesión.';
        } else {
            echo 'Token de verificación inválido o expirado.';
        }
    }
    
}
?>
