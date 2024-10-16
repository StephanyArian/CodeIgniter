<?php
class Ticket extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ticket_model');
        $this->load->helper('url');
        $this->load->library('session');
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
}
?>