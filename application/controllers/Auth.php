<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }
    
    public function index()
    {
        $data['msg'] = $this->uri->segment(3);

        if ($this->session->userdata('logged_in'))
        {
            // El usuario ya está logueado
            redirect('auth/panel', 'refresh');
        }
        else
        {
            // Usuario no está logueado
            $this->load->view('auth/login', $data);
        
        }
    }
    public function validar()
    {
        $NombreUsuario = $this->input->post('NombreUsuario');
        $Clave =  $this->input->post('Clave');
        
        $consulta = $this->user_model->validar($NombreUsuario, $Clave);

        if ($consulta === FALSE) {
            echo 'Error en la consulta';
            return;
        }

        if ($consulta->num_rows() > 0) {
            $row = $consulta->row();
            $this->session->set_userdata('idUsuarios', $row->idUsuarios);
            $this->session->set_userdata('logged_in', TRUE);
            $this->session->set_userdata('NombreUsuario', $row->NombreUsuario);
            $this->session->set_userdata('Rol', $row->Rol);
            $this->session->set_userdata('Foto', $row->foto);
            
            redirect('auth/panel', 'refresh');
        } else {
            redirect('auth/index/2', 'refresh');
        }
    }

    public function panel()
    {
        if ($this->session->userdata('logged_in'))
        {
            if ($this->session->userdata('Rol') == 'admin')
            {
                // El usuario es administrador
                redirect('dashboard/index', 'refresh');
            }
            else
            {
                redirect('cajero', 'refresh');
            }
        }
        else
        {
            // Usuario no está logueado
            redirect('auth/index/3', 'refresh');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/index/1', 'refresh');
    }
}
?>