<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model'); // Cargar el modelo Usuario_model
        $this->load->library('session'); 
    }

    public function lista_usuarios() {
        $listaUsuarios = $this->Usuario_model->lista_usuarios();
        $data['usuarios'] = $listaUsuarios;

        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('lista_usuarios', $data);
        $this->load->view('inc/footer');
    }

    public function agregar() {
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('formulario_usuario');
        $this->load->view('inc/footer');
    }

    public function agregarbd() {
        $token = bin2hex(random_bytes(32)); // Generar un token seguro
        $data = array(
            'PrimerApellido' => strtoupper($this->input->post('PrimerApellido')),
            'SegundoApellido' => strtoupper($this->input->post('SegundoApellido')),
            'Nombres' => strtoupper($this->input->post('Nombres')),
            'Email' => $this->input->post('Email'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Clave' =>  password_hash($this->input->post('Clave'), PASSWORD_BCRYPT),
            'Rol' => 'A',
            'Estado' => '1',
            'FechaCreacion' => date('Y-m-d H:i:s'),
            'IdUsuarioAuditoria' => 1, // ID del usuario que crea el registro
            'TokenVerificacion' => $token
        );
      
        $this->Usuario_model->agregar_usuario($data);

       // $this->enviar_correo_verificacion($this->input->post('Email'), $token);

        redirect('usuario/lista_usuarios', 'refresh');
    }
    //email
    private function enviar_correo_verificacion($email, $token) {
        $this->load->library('email');
    
        $this->email->from('stephanyignacio2000@gmail.com', 'Agroflori');
        $this->email->to($email);
        $this->email->subject('Verificación de correo electrónico');
        $this->email->message('Haz clic en el siguiente enlace para verificar tu correo: ' . base_url('usuario/verificar/' . $token));
    
        $this->email->send();
    }
    

    public function modificar($idUsuarios) {
        $data['infousuario'] = $this->Usuario_model->recuperar_usuario($idUsuarios);
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('form_modificar_usuario', $data);
        $this->load->view('inc/footer');
    }

    public function modificarbd() {
        $idUsuarios = $this->input->post('idUsuarios');
        $data = array(
            'PrimerApellido' => strtoupper($this->input->post('PrimerApellido')),
            'SegundoApellido' => strtoupper($this->input->post('SegundoApellido')),
            'Nombres' => strtoupper($this->input->post('Nombres')),
            'Email' => $this->input->post('Email'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Clave' => password_hash($this->input->post('Clave'), PASSWORD_BCRYPT),
            'Rol' => 'A',
            'Estado' => '1'
        );

        $this->Usuario_model->modificar_usuario($idUsuarios, $data);
        redirect('usuario/lista_usuarios', 'refresh');
    }

    public function eliminarbd($idUsuarios) {
        $this->Usuario_model->eliminar_usuario($idUsuarios);
        redirect('usuario/lista_usuarios', 'refresh');
    }
    //email
    public function cambiar_contrasena() {
        $this->load->view('inc/head');
        $this->load->view('cambiar_contrasena');
        $this->load->view('inc/footer');
    }

    public function cambiar_contrasena_bd() {
        $usuario_id = $this->session->userdata('usuario_id');
        $nueva_contrasena = password_hash($this->input->post('Clave'), PASSWORD_BCRYPT);

        $data = array(
            'Clave' => $nueva_contrasena
        );

        $this->Usuario_model->modificar_usuario($usuario_id, $data);
        $this->session->set_flashdata('success', 'Contraseña actualizada exitosamente.');
        redirect('inicio');
    }

    public function verificar($token) {
        $usuario = $this->Usuario_model->verificar_usuario($token);
    
        if ($usuario) {
            $data = array(
                'TokenVerificacion' => NULL // Limpiar el token
            );
            $this->Usuario_model->modificar_usuario($usuario->idUsuarios, $data);
            $this->session->set_flashdata('success', 'Cuenta verificada exitosamente. Ahora puede iniciar sesión.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Token de verificación inválido o expirado.');
            redirect('auth/login');
        }
    }
    
    
}
?>
