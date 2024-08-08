<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index() {
        $this->load->view('login');
    }

    public function authenticate() {
        $NombreUsuario = $this->input->post('NombreUsuario');
        $Clave = $this->input->post('Clave');

        $user = $this->User_model->get_user($NombreUsuario, $Clave);

        if ($user) {
            $this->session->set_userdata('idUsuarios', $user->idUsuarios);
            redirect('home');
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('login');
        }
    }

    public function logout() {
        $this->session->unset_userdata('idUsuarios');
        redirect('login');
    }
}
