<?php
class Visitante extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Visitante_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
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

        $this->form_validation->set_rules('Nombre', 'Nombre', 'required|max_length[45]');
        $this->form_validation->set_rules('PrimerApellido', 'Primer Apellido', 'required|max_length[45]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/]');
        $this->form_validation->set_rules('SegundoApellido', 'Segundo Apellido', 'max_length[45]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/]');
        $this->form_validation->set_rules('CiNit', 'CI/NIT', 'required|alpha_numeric|max_length[45]|is_unique[visitante.CiNit]');
        $this->form_validation->set_rules('NroCelular', 'Número Celular', 'required|numeric|max_length[45]');
        $this->form_validation->set_rules('Email', 'Email', 'valid_email|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('visitante/formulario_visitante');
            $this->load->view('inc/footer');
        } else {
            $visitante_data = array(
                'Nombre' => $this->input->post('Nombre'),
                'PrimerApellido' => $this->input->post('PrimerApellido'),
                'SegundoApellido' => $this->input->post('SegundoApellido'),
                'CiNit' => $this->input->post('CiNit'),
                'NroCelular' => $this->input->post('NroCelular'),
                'Email' => $this->input->post('Email'),
                'Estado' => 1
            );
            
            if ($this->Visitante_model->insert_visitante($visitante_data)) {
                $this->session->set_flashdata('mensaje', 'Visitante agregado con éxito.');
                redirect('visitante');
            } else {
                $this->session->set_flashdata('error', 'Error al agregar el visitante. Por favor, inténtelo de nuevo.');
                redirect('visitante/agregar');
            }
        }
    }

    public function editar($id) {
        $this->load->helper('form');

        $data['visitante'] = $this->Visitante_model->get_visitante_by_id($id);

        $this->form_validation->set_rules('Nombre', 'Nombre', 'required|max_length[45]');
        $this->form_validation->set_rules('PrimerApellido', 'Primer Apellido', 'required|max_length[45]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/]');
        $this->form_validation->set_rules('SegundoApellido', 'Segundo Apellido', 'max_length[45]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/]');
        $this->form_validation->set_rules('CiNit', 'CI/NIT', 'required|alpha_numeric|max_length[45]');
        $this->form_validation->set_rules('NroCelular', 'Número Celular', 'required|numeric|max_length[45]');
        $this->form_validation->set_rules('Email', 'Email', 'valid_email|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('visitante/formulario_visitante', $data);
            $this->load->view('inc/footer');
        } else {
            $visitante_data = array(
                'Nombre' => $this->input->post('Nombre'),
                'PrimerApellido' => $this->input->post('PrimerApellido'),
                'SegundoApellido' => $this->input->post('SegundoApellido'),
                'CiNit' => $this->input->post('CiNit'),
                'NroCelular' => $this->input->post('NroCelular'),
                'Email' => $this->input->post('Email'),
                'Estado' => $this->input->post('Estado')
            );
            
            if ($this->Visitante_model->update_visitante($id, $visitante_data)) {
                $this->session->set_flashdata('mensaje', 'Visitante actualizado con éxito.');
                redirect('visitante');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar el visitante. Por favor, inténtelo de nuevo.');
                redirect('visitante/editar/'.$id);
            }
        }
    }

    public function eliminar($id) {
        if ($this->Visitante_model->delete_visitante($id)) {
            $this->session->set_flashdata('mensaje', 'Visitante eliminado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Error al eliminar el visitante. Por favor, inténtelo de nuevo.');
        }
        redirect('visitante');
    }
}
?>