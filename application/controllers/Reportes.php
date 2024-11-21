<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Cargar los modelos necesarios
        $this->load->model('Venta_model');
        $this->load->model('Visitante_model');
        $this->load->model('Ticket_model');
        $this->load->model('Horario_model');
        // Cargar helpers
        $this->load->helper('url');
        $this->load->helper('form');
        // Cargar librería de sesiones y PDF
        $this->load->library('session');
        $this->load->library('pdf');
    }

    public function index() {
        $data['fecha_inicio'] = $this->input->post('fecha_inicio') ? $this->input->post('fecha_inicio') : date('Y-m-d', strtotime('-1 month'));
        $data['fecha_fin'] = $this->input->post('fecha_fin') ? $this->input->post('fecha_fin') : date('Y-m-d');

        $data['titulo'] = 'Menú de Reportes';
        
        // Cargar las vistas en orden
        $this->load->view('inc/head', $data);
        $this->load->view('inc/menu');
        $this->load->view('reportes/menu_reportes', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function ventas() {
        $fecha_inicio = $this->input->post('fecha_inicio') ? $this->input->post('fecha_inicio') : date('Y-m-d', strtotime('-1 month'));
        $fecha_fin = $this->input->post('fecha_fin') ? $this->input->post('fecha_fin') : date('Y-m-d');

        $data['fecha_inicio'] = $fecha_inicio;
        $data['fecha_fin'] = $fecha_fin;
        $data['ventas'] = $this->Venta_model->get_ventas_por_periodo($fecha_inicio, $fecha_fin);
        
        // Calcular totales
        $total_ventas = 0;
        $total_tickets = 0;
        foreach ($data['ventas'] as $venta) {
            $total_ventas += $venta['Monto'];
            $total_tickets += $venta['TotalTickets'];
        }
        
        $data['total_ventas'] = $total_ventas;
        $data['total_tickets'] = $total_tickets;
        $data['titulo'] = 'Reporte de Ventas';

        // Cargar las vistas en orden
        $this->load->view('inc/head', $data);
        $this->load->view('inc/menu');
        $this->load->view('reportes/reporte_ventas', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function visitantes() {
        $fecha_inicio = $this->input->post('fecha_inicio') ? $this->input->post('fecha_inicio') : date('Y-m-d', strtotime('-1 month'));
        $fecha_fin = $this->input->post('fecha_fin') ? $this->input->post('fecha_fin') : date('Y-m-d');

        $data['estadisticas'] = $this->Visitante_model->get_estadisticas_visitantes('personalizado', $fecha_inicio, $fecha_fin);
        $data['fecha_inicio'] = $fecha_inicio;
        $data['fecha_fin'] = $fecha_fin;
        $data['titulo'] = 'Reporte de Visitantes';

        // Cargar las vistas en orden
        $this->load->view('inc/head', $data);
        $this->load->view('inc/menu');
        $this->load->view('reportes/reporte_visitantes', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function horarios() {
        $fecha_inicio = $this->input->post('fecha_inicio') ? $this->input->post('fecha_inicio') : date('Y-m-d', strtotime('-1 month'));
        $fecha_fin = $this->input->post('fecha_fin') ? $this->input->post('fecha_fin') : date('Y-m-d');

        $data['horarios'] = $this->Horario_model->get_ocupacion_horarios();
        $data['fecha_inicio'] = $fecha_inicio;
        $data['fecha_fin'] = $fecha_fin;
        $data['titulo'] = 'Reporte de Horarios';

        // Cargar las vistas en orden
        $this->load->view('inc/head', $data);
        $this->load->view('inc/menu');
        $this->load->view('reportes/reporte_horarios', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    // Nuevos métodos para generar PDFs
    public function pdf_ventas() {
    $fecha_inicio = $this->input->get('fecha_inicio');
    $fecha_fin = $this->input->get('fecha_fin');

    $ventas = $this->Venta_model->get_ventas_por_periodo($fecha_inicio, $fecha_fin);
    
    // Calcular totales
    $total_ventas = 0;
    $total_tickets = 0;
    foreach ($ventas as $venta) {
        $total_ventas += $venta['Monto'];
        $total_tickets += $venta['TotalTickets'];
    }

    // Inicializar PDF
    $pdf = new Pdf();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Título del reporte
    $pdf->SectionTitle('Reporte de Ventas');
    $pdf->Ln(5);

    // Información del período
    $pdf->InfoBox('Período del Reporte', 
        'Desde: ' . $fecha_inicio . '     Hasta: ' . $fecha_fin);
    $pdf->Ln(10);

    // Definir headers de la tabla
    $headers = array(
        array('width' => 15, 'text' => 'ID'),
        array('width' => 30, 'text' => 'Fecha'),
        array('width' => 40, 'text' => 'Visitante'),
        array('width' => 25, 'text' => 'CI/NIT'),
        array('width' => 20, 'text' => 'Tickets'),
        array('width' => 25, 'text' => 'Monto'),
        array('width' => 35, 'text' => 'Vendedor')
    );

    $pdf->TableHeader($headers);

    foreach ($ventas as $venta) {
        $nombre_completo = $venta['Nombre'] . ' ' . $venta['PrimerApellido'];
        
        $pdf->TableCell(15, $venta['idVenta'], 'C');
        $pdf->TableCell(30, $venta['FechaCreacion'], 'C');
        $pdf->TableCell(40, $nombre_completo, 'L');
        $pdf->TableCell(25, $venta['CiNit'], 'C');
        $pdf->TableCell(20, $venta['TotalTickets'], 'C');
        $pdf->TableCell(25, number_format($venta['Monto'], 2), 'R');
        $pdf->TableCell(35, $venta['NombreUsuario'], 'L');
        $pdf->Ln();
    }

    // Resumen de totales
    $pdf->Ln(10);
    $pdf->InfoBox('Resumen', 
        'Total Ventas: Bs. ' . number_format($total_ventas, 2) . "\n" .
        'Total Tickets: ' . $total_tickets);

    $pdf->Output('reporte_ventas_' . $fecha_inicio . '_' . $fecha_fin . '.pdf', 'D');
}

    public function pdf_visitantes() {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');

        $estadisticas = $this->Visitante_model->get_estadisticas_visitantes('personalizado', $fecha_inicio, $fecha_fin);

        // Inicializar PDF
        $pdf = new Pdf();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Título del reporte
        $pdf->SectionTitle('Reporte de Visitantes');
        $pdf->Ln(5);

        // Información del período
        $pdf->InfoBox('Período del Reporte', 
            'Desde: ' . $fecha_inicio . '     Hasta: ' . $fecha_fin);
        $pdf->Ln(10);

        // Definir headers de la tabla
        $headers = array(
            array('width' => 40, 'text' => 'Período'),
            array('width' => 35, 'text' => 'Adultos Mayores'),
            array('width' => 35, 'text' => 'Adultos'),
            array('width' => 35, 'text' => 'Infantes'),
            array('width' => 35, 'text' => 'Total')
        );

        $pdf->TableHeader($headers);

        foreach ($estadisticas as $key => $stats) {
            if ($key !== 'estadisticas_generales') {
                $total = $stats['total_adulto_mayor'] + $stats['total_adulto'] + $stats['total_infante'];
                $pdf->TableCell(40, $stats['periodo'], 'L');
                $pdf->TableCell(35, $stats['total_adulto_mayor'], 'C');
                $pdf->TableCell(35, $stats['total_adulto'], 'C');
                $pdf->TableCell(35, $stats['total_infante'], 'C');
                $pdf->TableCell(35, $total, 'C');
                $pdf->Ln();
            }
        }

        $pdf->Output('reporte_visitantes_' . $fecha_inicio . '_' . $fecha_fin . '.pdf', 'D');
    }

    public function pdf_horarios() {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');

        $horarios = $this->Horario_model->get_ocupacion_horarios();

        // Inicializar PDF
        $pdf = new Pdf();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Título del reporte
        $pdf->SectionTitle('Reporte de Horarios');
        $pdf->Ln(5);

        // Información del período
        $pdf->InfoBox('Período del Reporte', 
            'Desde: ' . $fecha_inicio . '     Hasta: ' . $fecha_fin);
        $pdf->Ln(10);

        // Definir headers de la tabla
        $headers = array(
            array('width' => 50, 'text' => 'Horario'),
            array('width' => 45, 'text' => 'Capacidad Total'),
            array('width' => 45, 'text' => 'Ocupación'),
            array('width' => 45, 'text' => 'Disponibilidad')
        );

        $pdf->TableHeader($headers);

        foreach ($horarios as $horario) {
            $disponibilidad = $horario['CapacidadTotal'] - $horario['Ocupacion'];
            $pdf->TableCell(50, $horario['HoraInicio'] . ' - ' . $horario['HoraFin'], 'L');
            $pdf->TableCell(45, $horario['CapacidadTotal'], 'C');
            $pdf->TableCell(45, $horario['Ocupacion'], 'C');
            $pdf->TableCell(45, $disponibilidad, 'C');
            $pdf->Ln();
        }

        $pdf->Output('reporte_horarios_' . $fecha_inicio . '_' . $fecha_fin . '.pdf', 'D');
    }
}