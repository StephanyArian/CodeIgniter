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
        $this->load->library('pdf');
    }

    public function index() {
        $data['ventas'] = $this->Venta_model->get_all_ventas();
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/lista_ventas', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }
    //redireccion de
    

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
            $idHorarios = $this->input->post('idHorarios');
            $cantidadTotal = $this->input->post('CantAdultoMayor') + $this->input->post('CantAdulto') + $this->input->post('CantInfante');
            
            $disponibilidad = $this->Horario_model->verificar_disponibilidad($idHorarios);
            
            if ($disponibilidad === false || $disponibilidad < $cantidadTotal) {
                $this->session->set_flashdata('error', 'No hay suficiente disponibilidad en el horario seleccionado.');
                redirect('venta/nueva_venta');
                return;
            }
    
            $venta_data = array(
                'idVisitante' => $this->input->post('idVisitante'),
                'idHorarios' => $idHorarios,
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
        if (!$id_venta) {
            $this->session->set_flashdata('error', 'ID de venta no válido');
            redirect('venta');
            return;
        }
    
        $venta = $this->Venta_model->get_venta_details($id_venta);
        
        if (!$venta) {
            $this->session->set_flashdata('error', 'Venta no encontrada');
            redirect('venta');
            return;
        }
    
        // Cargar la librería PDF
        $this->load->library('pdf');
    
        // Crear un nuevo documento PDF
        $pdf = new FPDF();
        $pdf->AddPage();
    
        // Configurar la fuente
        $pdf->SetFont('Arial', 'B', 16);
    
        try {
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
            if ($venta['CantAdultoMayor'] > 0) {
                $pdf->Cell(60, 10, 'Adulto Mayor', 1);
                $pdf->Cell(30, 10, $venta['CantAdultoMayor'], 1);
                $pdf->Cell(50, 10, number_format($venta['PrecioAdultoMayor'], 2) . ' Bs.', 1);
                $pdf->Cell(50, 10, number_format($venta['CantAdultoMayor'] * $venta['PrecioAdultoMayor'], 2) . ' Bs.', 1);
                $pdf->Ln();
            }
    
            // Adulto
            if ($venta['CantAdulto'] > 0) {
                $pdf->Cell(60, 10, 'Adulto', 1);
                $pdf->Cell(30, 10, $venta['CantAdulto'], 1);
                $pdf->Cell(50, 10, number_format($venta['PrecioAdulto'], 2) . ' Bs.', 1);
                $pdf->Cell(50, 10, number_format($venta['CantAdulto'] * $venta['PrecioAdulto'], 2) . ' Bs.', 1);
                $pdf->Ln();
            }
    
            // Infante
            if ($venta['CantInfante'] > 0) {
                $pdf->Cell(60, 10, 'Infante', 1);
                $pdf->Cell(30, 10, $venta['CantInfante'], 1);
                $pdf->Cell(50, 10, number_format($venta['PrecioInfante'], 2) . ' Bs.', 1);
                $pdf->Cell(50, 10, number_format($venta['CantInfante'] * $venta['PrecioInfante'], 2) . ' Bs.', 1);
                $pdf->Ln();
            }
    
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
    
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error al generar el PDF: ' . $e->getMessage());
            redirect('venta');
        }

    }

    public function imprimir_tickets($id_venta) {
        $this->load->model('Ticket_model');
        $this->load->model('Venta_model');
    
        $venta = $this->Venta_model->get_venta_details($id_venta);
    
        if (!$venta) {
            $this->session->set_flashdata('error', 'Venta no encontrada');
            redirect('venta');
            return;
        }
    
        $tickets = $this->Ticket_model->get_tickets_by_venta($id_venta);
    
        if (!$tickets) {
            $this->session->set_flashdata('error', 'No se encontraron tickets para la venta');
            redirect('venta');
            return;
        }
    
        try {
            // Crear un único PDF con múltiples páginas
            $pdf = new FPDF();
            
            foreach ($tickets as $ticket) {
                $pdf->AddPage();
                
                // Configurar la fuente
                $pdf->SetFont('Arial', 'B', 16);
                
                // Título
                $pdf->Cell(0, 10, 'Comprobante de Ticket', 0, 1, 'C');
                $pdf->Ln(10);
    
                // Información del ticket
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, utf8_decode('ID Ticket: ' . $ticket['idTickets']), 0, 1);
                $pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y H:i', strtotime($venta['FechaCreacion'])), 0, 1);
                $pdf->Cell(0, 10, utf8_decode('Cliente: ' . $venta['Nombre'] . ' ' . $venta['PrimerApellido'] . ' ' . $venta['SegundoApellido']), 0, 1);
                $pdf->Cell(0, 10, 'CI/NIT: ' . $venta['CiNit'], 0, 1);
                $pdf->Cell(0, 10, utf8_decode('Tipo de Entrada: ' . $ticket['descripcion']), 0, 1);
                $pdf->Ln(10);
    
                // Tabla de detalles
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(80, 10, 'Concepto', 1);
                $pdf->Cell(50, 10, 'Precio Unitario', 1);
                $pdf->Cell(50, 10, 'Subtotal', 1);
                $pdf->Ln();
    
                $pdf->SetFont('Arial', '', 12);
    
                $precio = $this->Venta_model->get_precio_by_id($ticket['idPrecios']);
                if ($precio) {
                    $pdf->Cell(80, 10, utf8_decode($ticket['descripcion']), 1);
                    $pdf->Cell(50, 10, number_format($precio['precio'], 2) . ' Bs.', 1);
                    $pdf->Cell(50, 10, number_format($precio['precio'], 2) . ' Bs.', 1);
                    $pdf->Ln();
    
                    // Total
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(130, 10, 'Total', 1);
                    $pdf->Cell(50, 10, number_format($precio['precio'], 2) . ' Bs.', 1);
                }
            }
    
            // Generar un único PDF con todos los tickets
            $pdf->Output('Tickets_Venta_' . $id_venta . '.pdf', 'D');
    
        } catch (Exception $e) {
            log_message('error', 'Error al generar PDF de tickets: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error al generar el PDF: ' . $e->getMessage());
            redirect('venta');
        }
    }

    public function buscar_visitante_ajax() {
        // Verificar si es una petición AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
    
        $termino = $this->input->post('termino');
        
        if (empty($termino)) {
            echo json_encode([]);
            return;
        }
    
        $this->load->model('visitante_model');
        $visitantes = $this->visitante_model->buscar_visitante($termino);
        
        echo json_encode($visitantes);
    }
    
    
public function agregar_visitante() {
    // Verificar si el usuario está logueado
    $idUsuarios = $this->session->userdata('idUsuarios');
    if (!$this->session->userdata('logged_in')) {
        redirect('auth/login');
    }

    $data['title'] = 'Registro Rápido de Visitante';
    
    // Cargar las vistas
    $this->load->view('inc/head');
    $this->load->view('inc/menu');
    $this->load->view('venta/agregar_visitante');
    $this->load->view('inc/footer');
    $this->load->view('inc/pie');
}

public function guardar_visitante() {
    // Verificar si el usuario está logueado
    $idUsuarios = $this->session->userdata('idUsuarios');
    if (!$this->session->userdata('logged_in')) {
        redirect('auth/login');
    }

    // Validación de campos
    $this->load->library('form_validation');
    
    $this->form_validation->set_rules('CiNit', 'CI/NIT', 'required|trim');
    $this->form_validation->set_rules('Nombre', 'Nombre', 'required|trim');
    $this->form_validation->set_rules('PrimerApellido', 'Primer Apellido', 'required|trim');
    
    if ($this->form_validation->run() === FALSE) {
        // Si la validación falla, volver al formulario
        $this->agregar_visitante();
        return;
    }
    
    // Preparar datos del visitante
    $visitante_data = array(
        'CiNit' => strtoupper($this->input->post('CiNit')),
        'Nombre' => ucwords(strtolower($this->input->post('Nombre'))),
        'PrimerApellido' => ucwords(strtolower($this->input->post('PrimerApellido'))),
        'SegundoApellido' => ucwords(strtolower($this->input->post('SegundoApellido'))),
        'NroCelular' => $this->input->post('NroCelular'),
        'Estado' => 1
    );
    
    $resultado = $this->Visitante_model->insert_visitante($visitante_data);
    
    if ($resultado) {
        // Corregir la redirección a la ruta correcta
        redirect('venta/buscar_visitante'); // Esta es la ruta correcta
    } else {
        $this->session->set_flashdata('error', 'Error al registrar el visitante. Por favor intente nuevamente.');
        redirect('venta/agregar_visitante');
    }
}
    
}
?>