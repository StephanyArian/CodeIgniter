<?php
class Venta extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Venta_model');
        $this->load->model('Visitante_model');
        $this->load->model('Ticket_model');
        $this->load->model('Horario_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('pdf');
        $this->load->library('ci_qrcode');
    }

    // Al inicio de la clase, después del constructor
    private function ensure_qr_directory() {
        $qr_path = APPPATH . 'uploads/qr/';
        if (!file_exists($qr_path)) {
            mkdir($qr_path, 0777, true);
        }
        return $qr_path;
        } 
    
       // En Venta.php (Controlador)
    public function index() {
        // Agregamos permisos de sesión
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        $data['ventas'] = $this->Venta_model->get_all_ventas();
        $data['title'] = 'Lista de Ventas';
        
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/lista_ventas', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function nueva_venta() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Solo obtener horarios activos
        $data['horarios'] = $this->Horario_model->get_horarios_disponibles();
        // Solo obtener tickets activos
        // In Venta.php, line 49, change:
$data['tickets'] = $this->Ticket_model->get_active_tickets();
        $data['title'] = 'Nueva Venta';

        $this->load->view('inc/head', $data);
        $this->load->view('inc/menu');
        $this->load->view('venta/nueva_venta', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function buscar_visitante_ajax() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $termino = $this->input->post('termino');
        $visitantes = $this->Visitante_model->buscar_visitante($termino);
        echo json_encode($visitantes);
    }

    public function verificar_disponibilidad_ajax() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $id_horario = $this->input->post('id_horario');
        $id_ticket = $this->input->post('id_ticket');
        $cantidad = $this->input->post('cantidad');

        $disponible = $this->Venta_model->verificar_stock_tickets(
            $id_ticket, 
            $cantidad, 
            $id_horario
        );

        echo json_encode(['disponible' => $disponible]);
    }

    public function procesar_venta() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    
        // Validate required fields
        $this->form_validation->set_rules('id_visitante', 'Visitante', 'required');
        $this->form_validation->set_rules('id_horario', 'Horario', 'required');
        
        // Validate that at least one ticket is selected
        if (!$this->input->post('tickets') || !is_array($this->input->post('tickets'))) {
            $this->session->set_flashdata('error', 'Debe seleccionar al menos un ticket');
            redirect('venta/nueva_venta');
            return;
        }
    
        // Remove empty entries from tickets and quantities arrays
        $tickets = array_filter($this->input->post('tickets'));
        $cantidades = array_filter($this->input->post('cantidades'));
    
        // Validate that we have valid tickets and quantities
        if (empty($tickets) || empty($cantidades)) {
            $this->session->set_flashdata('error', 'Debe especificar tickets y cantidades válidas');
            redirect('venta/nueva_venta');
            return;
        }
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('venta/nueva_venta');
            return;
        }
    
        $data_venta = array(
            'idVisitante' => $this->input->post('id_visitante'),
            'idUsuarios' => $this->session->userdata('idUsuarios'),
            'idHorarios' => $this->input->post('id_horario'),
            'Estado' => 1 // Activo por defecto
        );
    
        $detalles = array();
        
        // Process only valid entries
        foreach ($tickets as $key => $ticket_id) {
            if (isset($cantidades[$key]) && $cantidades[$key] > 0) {
                // Verify stock availability
                if (!$this->Venta_model->verificar_stock_tickets(
                    $ticket_id, 
                    $cantidades[$key], 
                    $data_venta['idHorarios']
                )) {
                    $this->session->set_flashdata('error', 'No hay disponibilidad suficiente para el horario seleccionado');
                    redirect('venta/nueva_venta');
                    return;
                }
    
                $detalles[] = array(
                    'idTickets' => $ticket_id,
                    'Cantidad' => $cantidades[$key],
                    'Estado' => 'Comprado',
                    'NroTicket' => $this->Venta_model->get_next_ticket_number($data_venta['idHorarios'])
                );
            }
        }
    
        // Verify we have at least one valid detail
        if (empty($detalles)) {
            $this->session->set_flashdata('error', 'No se encontraron detalles válidos para la venta');
            redirect('venta/nueva_venta');
            return;
        }
    
        $id_venta = $this->Venta_model->crear_venta($data_venta, $detalles);
    
        if ($id_venta) {
            $this->session->set_flashdata('success', 'Venta realizada con éxito');
            redirect('venta/detalle/' . $id_venta);
        } else {
            $this->session->set_flashdata('error', 'Error al procesar la venta');
            redirect('venta/nueva_venta');
        }
    }
    public function detalle($id_venta) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    
        $data['venta_details'] = $this->Venta_model->get_venta_details($id_venta);
        
        if (empty($data['venta_details'])) {
            $this->session->set_flashdata('error', 'Venta no encontrada');
            redirect('venta');
        }
    
        $data['title'] = 'Detalle de Venta #' . $id_venta;
    
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('venta/detalle_venta', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }
    
    public function anular($id_venta) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    
        if ($this->Venta_model->actualizar_estado_venta($id_venta, 0)) {
            $this->session->set_flashdata('success', 'Venta anulada correctamente');
        } else {
            $this->session->set_flashdata('error', 'Error al anular la venta');
        }
    
        redirect('venta/detalle/' . $id_venta);
    }

    // En el controlador Venta.php, agregar este nuevo método:

    public function imprimir($id_venta) {
        // Prevenir cualquier salida anterior
        ob_clean();
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }
    
        $this->load->helper('numero_literal');
        $venta_details = $this->Venta_model->get_venta_details($id_venta);
        
        if (empty($venta_details)) {
            $this->session->set_flashdata('error', 'Venta no encontrada');
            redirect('venta');
            return;
        }
    
        try {
            // Inicializar PDF
            $pdf = new Pdf();
            $pdf->AliasNbPages();
            $pdf->AddPage();
    
            // Título de la sección
            $pdf->SectionTitle('Comprobante de Venta #' . $id_venta);
    
            // Información de la empresa
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 6, 'AGROFLORI', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 5, 'Cochabamba - Bolivia', 0, 1, 'C');
            $pdf->Cell(0, 5, utf8_decode('Av. Blanco Galindo Km. 12.5'), 0, 1, 'C');
            $pdf->Cell(0, 5, 'Quillacollo', 0, 1, 'C');
            $pdf->Cell(0, 5, utf8_decode('Teléfono: 4-4318787'), 0, 1, 'C');
            $pdf->Ln(5);
    
            // Información de la Venta
            $pdf->InfoBox('Informacion de la Venta', 
                "Fecha de Venta: " . date('d/m/Y H:i', strtotime($venta_details[0]['FechaCreacion'])) . "\n" .
                "Vendedor: " . $venta_details[0]['NombreVendedor'] . "\n" .
                "Monto Total: Bs. " . number_format($venta_details[0]['Monto'], 2)
            );
    
            // Información del Visitante
            $visitor_info = "Nombre: " . $venta_details[0]['Nombre'] . ' ' . $venta_details[0]['PrimerApellido'] . "\n" .
                           "CI/NIT: " . $venta_details[0]['CiNit'] . "\n";
            
            if (!empty($venta_details[0]['NroCelular'])) {
                $visitor_info .= "Celular: " . $venta_details[0]['NroCelular'] . "\n";
            }
            if (!empty($venta_details[0]['Email'])) {
                $visitor_info .= "Email: " . $venta_details[0]['Email'];
            }
            
            $pdf->InfoBox('Informacion del Visitante', $visitor_info);
    
            // Tabla de Tickets
            $pdf->Ln(10);
            $pdf->SectionTitle('Detalle de Tickets');
            
            // Definir encabezados de la tabla
            $headers = array(
                array('width' => 40, 'text' => 'Tipo'),
                array('width' => 50, 'text' => 'Descripcion'),
                array('width' => 25, 'text' => 'Cant.'),
                array('width' => 30, 'text' => 'P.Unit.'),
                array('width' => 30, 'text' => 'Subtotal')
            );
            
            $pdf->TableHeader($headers);
    
            // Contenido de la tabla
            $total = 0;
            foreach ($venta_details as $detalle) {
                $subtotal = $detalle['precio'] * $detalle['CantidadTotal'];
                $total += $subtotal;
                
                $pdf->TableCell(40, $detalle['TipoTicket']);
                $pdf->TableCell(50, $detalle['DescripcionTicket']);
                $pdf->TableCell(25, $detalle['CantidadTotal'], 'C');
                $pdf->TableCell(30, number_format($detalle['precio'], 2), 'R');
                $pdf->TableCell(30, number_format($subtotal, 2), 'R');
                $pdf->Ln();
            }
    
            // Total
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(145, 8, 'Total:', 1, 0, 'R');
            $pdf->Cell(30, 8, 'Bs. ' . number_format($total, 2), 1, 1, 'R');
            
            // Monto en literal
            $pdf->SetFont('Arial', '', 10);
            // Asegurarse de que el total sea un número válido y dentro del rango
            $total_rounded = round($total, 2);
            $literal = numero_a_letras($total_rounded);
            $pdf->Cell(0, 8, 'Son: ' . ucfirst($literal) . ' Bolivianos', 0, 1, 'L');
    
            // Información adicional
            $pdf->Ln(10);
            $pdf->InfoBox('Notas', 
                "- Este comprobante es un documento valido de su compra.\n" .
                "- Conserve este documento para cualquier reclamo o consulta.\n" .
                "- Los tickets son validos solo para la fecha y horario especificados.\n" .
                "- No se aceptan devoluciones."
            );
    
            // Generar el PDF
            $pdf->Output('Comprobante_Venta_' . $id_venta . '.pdf', 'I');
            
        } catch (Exception $e) {
            log_message('error', 'Error generando PDF: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error al generar el PDF');
            redirect('venta');
            return;
        }
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

    public function imprimir_tickets($id_venta) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    
        $this->load->model('Ticket_model');
        $this->load->model('Venta_model');
        
        $venta_details = $this->Venta_model->get_venta_details($id_venta);
        
        if (empty($venta_details)) {
            $this->session->set_flashdata('error', 'Venta no encontrada');
            redirect('venta');
            return;
        }
    
        try {
            // Ajustamos el tamaño de la hoja a un formato más pequeño (60mm x 100mm)
            $pdf = new FPDF('P', 'mm', array(60, 100));
            $ticket_counter = 1;
            
            foreach ($venta_details as $detalle) {
                for ($i = 0; $i < $detalle['CantidadTotal']; $i++) {
                    $pdf->AddPage();
                    $pdf->SetMargins(3, 2, 3);
                    
                    // Logo
                    if(file_exists(FCPATH . 'uploads/agroflori.jpg')) {
                        $pdf->Image(FCPATH . 'uploads/agroflori.jpg', 22, 5, 15);
                    }
                    
                    $pdf->Ln(18);
                    
                    // Encabezado con nombre y dirección
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(0, 4, 'AGROFLORI', 0, 1, 'C');
                    
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->Cell(0, 3, 'Cochabamba - Bolivia', 0, 1, 'C');
                    
                    // Nueva línea para la dirección
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->Cell(0, 3, utf8_decode('Av. Blanco Galindo Km. 12.5'), 0, 1, 'C');
                    $pdf->Cell(0, 3, utf8_decode('Quillacollo'), 0, 1, 'C');
                    $pdf->Cell(0, 3, utf8_decode('Telf: 4-4318787'), 0, 1, 'C');
                    
                    // Línea separadora
                    $pdf->Line(3, $pdf->GetY(), 57, $pdf->GetY());
                    $pdf->Ln(1);
                    
                    // Información del ticket
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(12, 4, 'Fecha:', 0);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(0, 4, date('d/m/Y H:i', strtotime($detalle['FechaCreacion'])), 0, 1);
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(12, 4, 'Visitante:', 0);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(0, 4, utf8_decode($detalle['Nombre'] . ' ' . $detalle['PrimerApellido']), 0, 1);
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(12, 4, 'CI/NIT:', 0);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(0, 4, $detalle['CiNit'], 0, 1);
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(12, 4, 'Tipo:', 0);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(0, 4, utf8_decode($detalle['TipoTicket']), 0, 1);
                    
                    $pdf->Ln(1);
                    
                    // Información adicional
                    $pdf->SetFillColor(240, 240, 240);
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(0, 4, utf8_decode('¡IMPORTANTE!'), 0, 1, 'C', true);
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->MultiCell(0, 3, utf8_decode("Este ticket es válido solo para el día y horario comprado.\nConserve este ticket hasta finalizar su visita."), 0, 'C', true);
                    
                    // Número de ticket
                    $pdf->SetFont('Arial', 'B', 6);
                    $pdf->Cell(0, 4, 'Ticket #' . $id_venta . '-' . $ticket_counter, 0, 1, 'C');
                    
                    $ticket_counter++;
                }
            }
            
            $pdf->Output('Tickets_Venta_' . $id_venta . '.pdf', 'D');
            
        } catch (Exception $e) {
            log_message('error', 'Error al generar PDF de tickets: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error al generar los tickets: ' . $e->getMessage());
            redirect('venta');
        }
    }
public function validar_ticket($ticket_id_str) {
    // Verificar que el formato del ticket sea válido
    if (!preg_match('/^\d+_\d+$/', $ticket_id_str)) {
        $data = [
            'status' => 'error',
            'message' => 'Formato de ticket inválido',
            'color' => '#dc3545'
        ];
        $this->load->view('venta/validacion_resultado', $data);
        return;
    }

    // Obtener la información del ticket
    $ticket = $this->Ticket_model->get_ticket_validation_status($ticket_id_str);

    if (!$ticket) {
        $data = [
            'status' => 'error',
            'message' => 'Ticket no encontrado',
            'color' => '#dc3545'
        ];
    } else if ($ticket['EstadoTicket'] === 'Usado') {
        $data = [
            'status' => 'error',
            'message' => 'Este ticket ya ha sido utilizado',
            'color' => '#dc3545',
            'ticket_info' => $ticket
        ];
    } else if (!$ticket['es_valido']) {
        $data = [
            'status' => 'error',
            'message' => 'Ticket no válido para este horario',
            'color' => '#dc3545',
            'ticket_info' => $ticket
        ];
    } else {
        // Marcar el ticket como usado
        $parts = explode('_', $ticket_id_str);
        $detalle_venta_id = $ticket['idDetalleVenta'];
        
        if ($this->Ticket_model->marcar_ticket_usado($detalle_venta_id)) {
            $data = [
                'status' => 'success',
                'message' => 'Ticket válido - Acceso permitido',
                'color' => '#28a745',
                'ticket_info' => $ticket
            ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Error al procesar el ticket',
                'color' => '#dc3545',
                'ticket_info' => $ticket
            ];
        }
    }

    // Cargar la vista con los resultados
    $this->load->view('venta/validacion_resultado', $data);
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
            redirect('venta/nueva_venta'); // Esta es la ruta correcta
        } else {
            $this->session->set_flashdata('error', 'Error al registrar el visitante. Por favor intente nuevamente.');
            redirect('venta/agregar_visitante');
        }
    }
}
?>