<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Horario_model');
        $this->load->model('Precios_model');
        $this->load->model('Ticket_model');
        $this->load->model('Visitante_model');
        $this->load->library('Pdf');
    }

    public function index() {
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('reportes/menu_reportes');
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function ocupacion_horarios() {
        $data['horarios'] = $this->Horario_model->get_ocupacion_horarios();
        $html = $this->load->view('reportes/ocupacion_horarios', $data, true);
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->WriteHTML($html);
        $pdf->Output('reporte_ocupacion_horarios.pdf', 'I');
    }

    public function estructura_precios() {
        $data['precios'] = $this->Precios_model->get_precios_activos();
        $html = $this->load->view('reportes/estructura_precios', $data, true);
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->WriteHTML($html);
        $pdf->Output('reporte_estructura_precios.pdf', 'I');
    }

    public function ventas_tickets() {
        $data['ventas'] = $this->Ticket_model->get_ventas_resumen();
        $html = $this->load->view('reportes/ventas_tickets', $data, true);
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->WriteHTML($html);
        $pdf->Output('reporte_ventas_tickets.pdf', 'I');
    }

    public function estadisticas_visitantes() {
        $data['estadisticas'] = $this->Visitante_model->get_estadisticas_visitantes();
        $html = $this->load->view('reportes/estadisticas_visitantes', $data, true);
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->WriteHTML($html);
        $pdf->Output('reporte_estadisticas_visitantes.pdf', 'I');
    }
}