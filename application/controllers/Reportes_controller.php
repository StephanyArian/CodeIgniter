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
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('reportes/menu_reportes');
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function ocupacion_horarios() {
        $horarios = $this->Horario_model->get_ocupacion_horarios();
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Reporte de Ocupacion de Horarios',0,1,'C');
        $pdf->Ln(10);

        $header = array('Horario', 'Capacidad', 'Ocupados', 'Disponibles');
        
        // Colors, line width and bold font
        $pdf->SetFillColor(173,216,230); // Light blue
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(100,149,237); // Cornflower blue
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(50, 30, 30, 30);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(240,248,255); // Alice blue
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        foreach($horarios as $horario) {
            $pdf->Cell($w[0],6,$horario['HoraEntrada'] . ' - ' . $horario['HoraCierre'],'LR',0,'L',$fill);
            $pdf->Cell($w[1],6,$horario['MaxVisitantes'],'LR',0,'R',$fill);
            $pdf->Cell($w[2],6,$horario['visitantes_actuales'],'LR',0,'R',$fill);
            $pdf->Cell($w[3],6,$horario['MaxVisitantes'] - $horario['visitantes_actuales'],'LR',0,'R',$fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Cell(array_sum($w),0,'','T');

        $pdf->Output('reporte_ocupacion_horarios.pdf', 'I');
    }

    public function estructura_precios() {
        $precios = $this->Precios_model->get_precios_activos();
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Estructura de Precios',0,1,'C');
        $pdf->Ln(10);

        $header = array('Tipo', 'Precio', 'Ultima Actualizacion', 'Estado');
        
        // Colors, line width and bold font
        $pdf->SetFillColor(173,216,230); // Light blue
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(100,149,237); // Cornflower blue
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(40, 30, 60, 30);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(240,248,255); // Alice blue
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        foreach($precios as $precio) {
            $pdf->Cell($w[0],6,$precio['tipo'],'LR',0,'L',$fill);
            $pdf->Cell($w[1],6,'Bs. ' . $precio['precio'],'LR',0,'R',$fill);
            $pdf->Cell($w[2],6,$precio['fecha_actualizacion'],'LR',0,'C',$fill);
            $pdf->Cell($w[3],6,$precio['estado'] ? 'Activo' : 'Inactivo','LR',0,'C',$fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Cell(array_sum($w),0,'','T');

        $pdf->Output('reporte_estructura_precios.pdf', 'I');
    }

    public function ventas_tickets($fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio || !$fecha_fin) {
            $fecha_inicio = date('Y-m-01');
            $fecha_fin = date('Y-m-t');
        }

        $ventas = $this->Ticket_model->get_ventas_resumen($fecha_inicio, $fecha_fin);
        
        $pdf = new Pdf();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Ventas de Tickets', 0, 1, 'C');
    $pdf->Ln(10);

    $header = array('Fecha', 'Total Tickets', 'Adulto Mayor', 'Adulto', 'Infante', 'Ingresos Totales');
    
    // Colors, line width and bold font
    $pdf->SetFillColor(173, 216, 230);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(100, 149, 237);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('', 'B');
    
    // Adjust column widths
    $w = array(30, 25, 25, 25, 25, 40);
    
    // Header
    for($i = 0; $i < count($header); $i++)
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
    $pdf->Ln();
    
    // Color and font restoration
    $pdf->SetFillColor(240, 248, 255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('', '', 10);
    
    // Data
    $fill = false;
    $total_ventas = 0;
    $total_tickets = 0;
    foreach($ventas as $venta) {
        $pdf->Cell($w[0], 6, $venta['fecha'], 1, 0, 'L', $fill);
        $pdf->Cell($w[1], 6, $venta['total_tickets'], 1, 0, 'R', $fill);
        $pdf->Cell($w[2], 6, $venta['total_adulto_mayor'], 1, 0, 'R', $fill);
        $pdf->Cell($w[3], 6, $venta['total_adulto'], 1, 0, 'R', $fill);
        $pdf->Cell($w[4], 6, $venta['total_infante'], 1, 0, 'R', $fill);
        $pdf->Cell($w[5], 6, 'Bs. ' . number_format($venta['ingresos_totales'], 2), 1, 0, 'R', $fill);
        $pdf->Ln();
        $fill = !$fill;
        $total_ventas += $venta['ingresos_totales'];
        $total_tickets += $venta['total_tickets'];
    }
    
    // Totals
    $pdf->SetFont('', 'B');
    $pdf->Cell(array_sum(array_slice($w, 0, 5)), 6, 'Total:', 1, 0, 'R');
    $pdf->Cell($w[5], 6, 'Bs. ' . number_format($total_ventas, 2), 1, 0, 'R');
    $pdf->Ln();
    $pdf->Cell(array_sum(array_slice($w, 0, 5)), 6, 'Total Tickets:', 1, 0, 'R');
    $pdf->Cell($w[5], 6, $total_tickets, 1, 0, 'R');

    $pdf->Output('reporte_ventas_tickets_' . $fecha_inicio . '_' . $fecha_fin . '.pdf', 'I');
    }

    public function estadisticas_visitantes($periodo = 'mensual') {
        $periodos_validos = ['diario', 'semanal', 'mensual', 'anual'];
        if (!in_array($periodo, $periodos_validos)) {
            $periodo = 'mensual';
        }
    
        $estadisticas = $this->Visitante_model->get_estadisticas_visitantes($periodo);
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Estadisticas de Visitantes - ' . ucfirst($periodo), 0, 1, 'C');
        $pdf->Ln(10);
    
        $header = array('Periodo', 'Total Visitantes', 'Adulto Mayor', 'Adulto', 'Infante');
        
        // Colors, line width and bold font
        $pdf->SetFillColor(173, 216, 230);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(100, 149, 237);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('', 'B');
        
        // Adjust column widths
        $w = array(40, 35, 35, 35, 35);
        
        // Header
        for($i = 0; $i < count($header); $i++)
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(240, 248, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('', '', 10);
        
        // Data
        $fill = false;
        $total_visitantes = 0;
        $total_adulto_mayor = 0;
        $total_adulto = 0;
        $total_infante = 0;
        foreach($estadisticas as $estadistica) {
            $pdf->Cell($w[0], 6, isset($estadistica['periodo']) ? $estadistica['periodo'] : 'N/A', 1, 0, 'L', $fill);
            $pdf->Cell($w[1], 6, isset($estadistica['total_visitantes']) ? $estadistica['total_visitantes'] : '0', 1, 0, 'R', $fill);
            $pdf->Cell($w[2], 6, isset($estadistica['total_adulto_mayor']) ? $estadistica['total_adulto_mayor'] : '0', 1, 0, 'R', $fill);
            $pdf->Cell($w[3], 6, isset($estadistica['total_adulto']) ? $estadistica['total_adulto'] : '0', 1, 0, 'R', $fill);
            $pdf->Cell($w[4], 6, isset($estadistica['total_infante']) ? $estadistica['total_infante'] : '0', 1, 0, 'R', $fill);
            $pdf->Ln();
            $fill = !$fill;
            $total_visitantes += isset($estadistica['total_visitantes']) ? $estadistica['total_visitantes'] : 0;
            $total_adulto_mayor += isset($estadistica['total_adulto_mayor']) ? $estadistica['total_adulto_mayor'] : 0;
            $total_adulto += isset($estadistica['total_adulto']) ? $estadistica['total_adulto'] : 0;
            $total_infante += isset($estadistica['total_infante']) ? $estadistica['total_infante'] : 0;
        }
        $pdf->Cell(array_sum($w),0,'','T');
        
        // Totals
        $pdf->SetFont('', 'B');
        $pdf->Cell($w[0], 6, 'Total:', 1, 0, 'L');
        $pdf->Cell($w[1], 6, $total_visitantes, 1, 0, 'R');
        $pdf->Cell($w[2], 6, $total_adulto_mayor, 1, 0, 'R');
        $pdf->Cell($w[3], 6, $total_adulto, 1, 0, 'R');
        $pdf->Cell($w[4], 6, $total_infante, 1, 0, 'R');
    
        $pdf->Output('reporte_estadisticas_visitantes_' . $periodo . '.pdf', 'I');
    }
}