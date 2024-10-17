<?php
class Venta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Venta_model');
        $this->load->model('Visitante_model');
        $this->load->model('Ticket_model');
        $this->load->model('Horario_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['ventas'] = $this->Venta_model->get_all_ventas();
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/lista_ventas', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function nueva_venta() {
        
        $data['horarios'] = $this->Horario_model->get_horarios_disponibles();
        $data['precios'] = $this->Venta_model->get_precios_activos();

        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/nueva_venta', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function buscar_visitante() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('termino', 'Término de búsqueda', 'required|min_length[2]');
    
        if ($this->form_validation->run() == FALSE) {
            $data['mensaje'] = 'Por favor, ingrese al menos 2 caracteres para la búsqueda.';
        } else {
            $termino = $this->input->post('termino');
            $visitantes = $this->Visitante_model->buscar_visitante($termino);
    
            if (empty($visitantes)) {
                $data['mensaje'] = 'No se encontraron visitantes que coincidan con "' . $termino . '"';
            } else {
                $data['visitantes'] = $visitantes;
            }
        }
    
        // Cargar datos para el formulario de nueva venta
        $data['horarios'] = $this->Horario_model->get_horarios_disponibles();
        $data['precios'] = $this->Venta_model->get_precios_activos();
    
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/buscar_visitante', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function procesar_venta() {
        $this->form_validation->set_rules('idVisitante', 'Visitante', 'required');
        $this->form_validation->set_rules('idHorarios', 'Horario', 'required|callback_check_horario_disponible');
        $this->form_validation->set_rules('CantAdultoMayor', 'Cantidad Adulto Mayor', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('CantAdulto', 'Cantidad Adulto', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('CantInfante', 'Cantidad Infante', 'required|integer|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->nueva_venta();
        } else {
            $venta_data = array(
                'idVisitante' => $this->input->post('idVisitante'),
                'idHorarios' => $this->input->post('idHorarios'),
                'CantAdultoMayor' => $this->input->post('CantAdultoMayor'),
                'CantAdulto' => $this->input->post('CantAdulto'),
                'CantInfante' => $this->input->post('CantInfante'),
                'Comentario' => $this->input->post('Comentario'),
                'idUsuarios' => $this->session->userdata('idUsuarios')
            );

            $id_venta = $this->Venta_model->insert_venta($venta_data);

            if ($id_venta) {
                $this->session->set_flashdata('mensaje', 'Venta realizada con éxito. ID de venta: ' . $id_venta);
                redirect('venta');
            } else {
                $this->session->set_flashdata('error', 'Error al realizar la venta. Por favor, inténtelo de nuevo.');
                redirect('venta/nueva_venta');
            }
        }
    }

    public function detalle($id_venta) {
        $this->load->model('Venta_model');
        $data['venta'] = $this->Venta_model->get_venta_details($id_venta);
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/detalles_venta', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function check_horario_disponible($idHorarios) {
        $disponibilidad = $this->Horario_model->verificar_disponibilidad($idHorarios);
        
        if ($disponibilidad === false) {
            $this->form_validation->set_message('check_horario_disponible', 'Error al verificar la disponibilidad del horario');
            return FALSE;
        } elseif ($disponibilidad <= 0) {
            $this->form_validation->set_message('check_horario_disponible', 'El horario seleccionado está lleno');
            return FALSE;
        }
        
        return TRUE;
    }

    public function imprimir($id_venta) {
        $this->load->model('Venta_model');
        $venta = $this->Venta_model->get_venta_details($id_venta);
    
        // Cargar la librería PDF
        $this->load->library('pdf');
    
        // Crear un nuevo documento PDF
        $pdf = new FPDF();
        $pdf->AddPage();
    
        
        // Configurar la fuente
        $pdf->SetFont('Arial', 'B', 16);
    
        // Título
        $pdf->Cell(0, 10, 'Comprobante de Venta', 0, 1, 'C');
        $pdf->Ln(10);
    
        // Información de la venta
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'ID Venta: ' . $venta['idVenta'], 0, 1);
        $pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y H:i', strtotime($venta['FechaCreacion'])), 0, 1);
        $pdf->Cell(0, 10, 'Cliente: ' . $venta['Nombre'] . ' ' . $venta['PrimerApellido'] . ' ' . $venta['SegundoApellido'], 0, 1);
        $pdf->Cell(0, 10, 'CI/NIT: ' . $venta['CiNit'], 0, 1);
        $pdf->Ln(10);
    
        // Tabla de detalles
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Tipo', 1);
        $pdf->Cell(30, 10, 'Cantidad', 1);
        $pdf->Cell(50, 10, 'Precio Unitario', 1);
        $pdf->Cell(50, 10, 'Subtotal', 1);
        $pdf->Ln();
    
        $pdf->SetFont('Arial', '', 12);
        
        // Adulto Mayor
        $pdf->Cell(60, 10, 'Adulto Mayor', 1);
        $pdf->Cell(30, 10, $venta['CantAdultoMayor'], 1);
        $pdf->Cell(50, 10, number_format($venta['PrecioAdultoMayor'], 2) . ' Bs.', 1);
        $pdf->Cell(50, 10, number_format($venta['CantAdultoMayor'] * $venta['PrecioAdultoMayor'], 2) . ' Bs.', 1);
        $pdf->Ln();
    
        // Adulto
        $pdf->Cell(60, 10, 'Adulto', 1);
        $pdf->Cell(30, 10, $venta['CantAdulto'], 1);
        $pdf->Cell(50, 10, number_format($venta['PrecioAdulto'], 2) . ' Bs.', 1);
        $pdf->Cell(50, 10, number_format($venta['CantAdulto'] * $venta['PrecioAdulto'], 2) . ' Bs.', 1);
        $pdf->Ln();
    
        // Infante
        $pdf->Cell(60, 10, 'Infante', 1);
        $pdf->Cell(30, 10, $venta['CantInfante'], 1);
        $pdf->Cell(50, 10, number_format($venta['PrecioInfante'], 2) . ' Bs.', 1);
        $pdf->Cell(50, 10, number_format($venta['CantInfante'] * $venta['PrecioInfante'], 2) . ' Bs.', 1);
        $pdf->Ln();
    
        // Total
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(140, 10, 'Total', 1);
        $pdf->Cell(50, 10, number_format($venta['Monto'], 2) . ' Bs.', 1);
    
        // Comentario
        if (!empty($venta['Comentario'])) {
            $pdf->Ln(20);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Comentario:', 0, 1);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCell(0, 10, $venta['Comentario'], 0, 'L');
        }
    
        // Generar el PDF
        $pdf->Output('Comprobante_Venta_' . $id_venta . '.pdf', 'D');
    }
}
?>