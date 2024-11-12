<?php
class Ticket extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ticket_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['tickets'] = $this->Ticket_model->get_all_tickets_with_visitantes();
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('ticket/lista_tickets', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function detalles($id) {
        $data['ticket'] = $this->Ticket_model->get_ticket_details($id);
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('ticket/detalles_ticket', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function agregar() {
        $this->form_validation->set_rules('tipo', 'Tipo', 'required');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('ticket/agregar_ticket');
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $data = array(
                'IdUsuarioAuditoria' => $this->session->userdata('idUsuarios'),
                'tipo' => $this->input->post('tipo'),
                'precio' => $this->input->post('precio'),
                'descripcion' => $this->input->post('descripcion'),
                'estado' => 'activo',
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            );

            $this->Ticket_model->insert_ticket($data);
            $this->session->set_flashdata('mensaje', 'Ticket agregado correctamente');
            redirect('ticket');
        }
    }

    public function modificar($id) {
        $this->form_validation->set_rules('tipo', 'Tipo', 'required');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['ticket'] = $this->Ticket_model->get_ticket_details($id);
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('ticket/modificar_ticket', $data);
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $data = array(
                'IdUsuarioAuditoria' => $this->session->userdata('idUsuarios'),
                'tipo' => $this->input->post('tipo'),
                'precio' => $this->input->post('precio'),
                'descripcion' => $this->input->post('descripcion'),
                'estado' => $this->input->post('estado'),
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            );

            $this->Ticket_model->update_ticket($id, $data);
            $this->session->set_flashdata('mensaje', 'Ticket modificado correctamente');
            redirect('ticket');
        }
    }
    
    public function eliminarbd($id) {
        $this->Ticket_model->delete_ticket($id);
        $this->session->set_flashdata('mensaje', 'Ticket eliminado correctamente');
        redirect('ticket');
    }
}
?>