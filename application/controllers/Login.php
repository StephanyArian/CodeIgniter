<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
    }

    public function index() {
        $this->load->view('login');
    }

    public function authenticate() {
        $NombreUsuario = $this->input->post('NombreUsuario');
        $Clave = $this->input->post('Clave');

        $usuario = $this->User_model->get_user($NombreUsuario, $Clave);

        if ($usuario) {
            $this->session->set_userdata('idUsuarios', $user->idUsuarios);
            redirect('home');
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
