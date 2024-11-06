<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Precios_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Precios_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
         // Añadir verificación de permisos
         $this->check_admin_permissions();
    }

     // Método privado para verificar permisos de administrador
     private function check_admin_permissions() {
        // Verificar si el usuario está logueado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Debe iniciar sesión para acceder.');
            redirect('auth/index');
        }

        // Verificar si el usuario es administrador
        if ($this->session->userdata('Rol') !== 'admin') {
            $this->session->set_flashdata('error', 'No tiene permisos para acceder a los reportes.');
            redirect('auth/panel');
        }
    }

    public function index() {
        $data['precios'] = $this->Precios_model->get_precios_activos();
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('precios/lista_precios', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function agregar() {
        $this->form_validation->set_rules('tipo', 'Tipo', 'required|in_list[adulto_mayor,adulto,infante]');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['tipos_permitidos'] = ['adulto_mayor', 'adulto', 'infante'];
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('precios/formulario_precio', $data);
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $this->Precios_model->insert_precio();
            redirect('precios');
        }
    }

    public function editar($id) {
        $this->form_validation->set_rules('tipo', 'Tipo', 'required|in_list[adulto_mayor,adulto,infante]');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['precio'] = $this->Precios_model->get_precio($id);
            $data['tipos_permitidos'] = ['adulto_mayor', 'adulto', 'infante'];
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('precios/formulario_precio', $data);
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $this->Precios_model->update_precio($id);
            redirect('precios');
        }
    }

    public function eliminar($id) {
        $this->Precios_model->delete_precio_logico($id);
        redirect('precios');
    }
}