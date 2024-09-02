<?php
class Visitante extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Visitante_model');
        $this->load->helper('url');
    }

    public function index() {
        $data['visitantes'] = $this->Visitante_model->get_all_visitantes();
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('visitante/lista_visitantes', $data);
        $this->load->view('inc/footer');
    }

    public function agregar() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('Nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('PrimerApellido', 'Primer Apellido', 'required');
        $this->form_validation->set_rules('CiNit', 'CI/NIT', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('visitante/formulario_visitante');
            $this->load->view('inc/footer');
        } else {
            $data = array(
                'Nombre' => $this->input->post('Nombre'),
                'PrimerApellido' => $this->input->post('PrimerApellido'),
                'SegundoApellido' => $this->input->post('SegundoApellido'),
                'CiNit' => $this->input->post('CiNit'),
                'NroCelular' => $this->input->post('NroCelular'),
                'Email' => $this->input->post('Email'),
                'Estado' => $this->input->post('Estado')
            );
            $this->Visitante_model->insert_visitante($data);
            redirect('visitante');
        }
    }

    public function editar($id) {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['visitante'] = $this->Visitante_model->get_visitante_by_id($id);

        $this->form_validation->set_rules('Nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('PrimerApellido', 'Primer Apellido', 'required');
        $this->form_validation->set_rules('CiNit', 'CI/NIT', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('visitante/formulario_visitante', $data);
            $this->load->view('inc/footer');
        } else {
            $data = array(
                'Nombre' => $this->input->post('Nombre'),
                'PrimerApellido' => $this->input->post('PrimerApellido'),
                'SegundoApellido' => $this->input->post('SegundoApellido'),
                'CiNit' => $this->input->post('CiNit'),
                'NroCelular' => $this->input->post('NroCelular'),
                'Email' => $this->input->post('Email'),
                'Estado' => $this->input->post('Estado')
            );
            $this->Visitante_model->update_visitante($id, $data);
            redirect('visitante');
        }
    }

    public function eliminar($id) {
        $this->Visitante_model->delete_visitante($id);
        redirect('visitante/index');
    }
}
?>
