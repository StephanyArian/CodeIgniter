<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('venta_model');
        $this->load->library('ciqrcode');
    }

    public function validate_ticket($ticket_id = NULL) {
        // Verificar que se recibió un ticket_id
        if (!$ticket_id) {
            $response = [
                'success' => false,
                'message' => 'ID de ticket no proporcionado'
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Validar el ticket
        $result = $this->venta_model->validate_ticket($ticket_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function generate_ticket_qr($ticket_id) {
        // Configuración del QR
        $params['data'] = site_url("api/validate_ticket/{$ticket_id}");
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . "uploads/qr/ticket-{$ticket_id}.png";
        
        // Generar QR
        $this->ciqrcode->generate($params);
        
        // Devolver la URL del QR generado
        $qr_url = base_url("uploads/qr/ticket-{$ticket_id}.png");
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['qr_url' => $qr_url]));
    }
}
?>