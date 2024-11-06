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
    
        $this->load->library('pdf');
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
    
       
    try {
        // Agregar logo
        if(file_exists(FCPATH . 'uploads/agroflori.jpg')) {
            $pdf->Image(FCPATH . 'uploads/agroflori.jpg', 10, 10, 50);
        }

        // Encabezado elegante
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(34, 139, 34); // Verde forestal
        $pdf->Cell(0, 10, 'AGROFLORI', 0, 1, 'C');
        
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, 'Comprobante de Venta', 0, 1, 'C');
        
        // Información de la empresa
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Cochabamba, Bolivia', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Tel: (591) *******', 0, 1, 'C');
        $pdf->Ln(10);

        // Detalles de la venta con diseño mejorado
        $pdf->SetFillColor(240, 248, 255); // Azul muy claro
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'DATOS DE LA VENTA', 1, 1, 'C', true);
        
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(95, 10, 'Fecha: ' . date('d/m/Y H:i', strtotime($venta['FechaCreacion'])), 1, 0);
        $pdf->Cell(95, 10, 'No. Venta: ' . sprintf('%08d', $venta['idVenta']), 1, 1);
        
        $pdf->MultiCell(190, 10, 'Cliente: ' . $venta['Nombre'] . ' ' . $venta['PrimerApellido'] . ' ' . $venta['SegundoApellido'], 1);
        $pdf->Cell(190, 10, 'CI/NIT: ' . $venta['CiNit'], 1, 1);

        // Tabla de detalles con mejor formato
        $pdf->Ln(5);
        $pdf->SetFillColor(46, 139, 87); // Verde mar
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 12);
        
        // Encabezados de tabla
        $pdf->Cell(60, 10, 'Tipo', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
        $pdf->Cell(50, 10, 'Precio Unit.', 1, 0, 'C', true);
        $pdf->Cell(50, 10, 'Subtotal', 1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 11);

        // Detalles con filas alternadas
        $fill = false;
        
        if ($venta['CantAdultoMayor'] > 0) {
            $pdf->Cell(60, 10, 'Adulto Mayor', 1, 0, 'L', $fill);
            $pdf->Cell(30, 10, $venta['CantAdultoMayor'], 1, 0, 'C', $fill);
            $pdf->Cell(50, 10, number_format($venta['PrecioAdultoMayor'], 2) . ' Bs.', 1, 0, 'R', $fill);
            $pdf->Cell(50, 10, number_format($venta['CantAdultoMayor'] * $venta['PrecioAdultoMayor'], 2) . ' Bs.', 1, 1, 'R', $fill);
            $fill = !$fill;
        }
    
            // Adulto
            if ($venta['CantAdulto'] > 0) {
                $pdf->Cell(60, 10, 'Adulto', 1, 0, 'L', $fill);
                $pdf->Cell(30, 10, $venta['CantAdulto'], 1, 0, 'C', $fill);
                $pdf->Cell(50, 10, number_format($venta['PrecioAdulto'], 2) . ' Bs.', 1, 0, 'R', $fill);
                $pdf->Cell(50, 10, number_format($venta['CantAdulto'] * $venta['PrecioAdulto'], 2) . ' Bs.', 1, 1, 'R', $fill);
                $fill = !$fill;
            }
    
            // Infante
            if ($venta['CantInfante'] > 0) {
                $pdf->Cell(60, 10, 'Infante', 1, 0, 'L', $fill);
                $pdf->Cell(30, 10, $venta['CantInfante'], 1, 0, 'C', $fill);
                $pdf->Cell(50, 10, number_format($venta['PrecioInfante'], 2) . ' Bs.', 1, 0, 'R', $fill);
                $pdf->Cell(50, 10, number_format($venta['CantInfante'] * $venta['PrecioInfante'], 2) . ' Bs.', 1, 1, 'R', $fill);
                $fill = !$fill;
            }
    
            // Total con formato destacado
        $pdf->SetFillColor(46, 139, 87);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(140, 10, 'TOTAL', 1, 0, 'R', true);
        $pdf->Cell(50, 10, number_format($venta['Monto'], 2) . ' Bs.', 1, 1, 'R', true);

        // Pie de página
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Gracias por su visita', 0, 1, 'C');
        
        $pdf->Output('Comprobante_Venta_' . $id_venta . '.pdf', 'D');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error al generar el PDF: ' . $e->getMessage());
            redirect('venta');
        }

    }
    
    public function imprimir_tickets($id_venta) {
        $this->load->model('Ticket_model');
        $this->load->model('Venta_model');
        $this->load->library('ci_qrcode');
        
        // Obtener datos de la venta
        $venta = $this->Venta_model->get_venta_details($id_venta);
    
        if (!$venta) {
            $this->session->set_flashdata('error', 'Venta no encontrada');
            redirect('venta');
            return;
        }
    
        // Obtener tickets
        $tickets = $this->Ticket_model->get_tickets_by_venta($id_venta);
    
        if (!$tickets) {
            $this->session->set_flashdata('error', 'No se encontraron tickets para la venta');
            redirect('venta');
            return;
        }
    
        try {
            // Asegurar que existe el directorio para QR
            $qr_path = $this->ensure_qr_directory();
            
            // Reducir aún más el tamaño del ticket
            $pdf = new FPDF('P', 'mm', array(80, 110)); // Reducir altura de 120mm a 110mm
            
            foreach ($tickets as $ticket) {
                $pdf->AddPage();
                
                // Configuración de márgenes más pequeños
                $pdf->SetMargins(2, 2, 2);
                
                // Agregar logo con tamaño más reducido
                if(file_exists(FCPATH . 'uploads/agroflori.jpg')) {
                    $pdf->Image(FCPATH . 'uploads/agroflori.jpg', ($pdf->GetPageWidth() - 20) / 2, 2, 20); // Reducir tamaño del logo a 20mm
                }
    
                // Reducir espacio después del logo
                $pdf->Ln(20); // Reducido de 25 a 20
    
                // Nombre de la empresa y ubicación más compactos
                $pdf->SetFont('Arial', 'B', 10); // Reducido de 12 a 10
                $pdf->Cell(0, 3, 'AGROFLORI', 0, 1, 'C');
                
                $pdf->SetFont('Arial', '', 7); // Reducido de 8 a 7
                $pdf->Cell(0, 3, 'Cochabamba - Bolivia', 0, 1, 'C');
                
                // Línea separadora con menos espacio
                $pdf->Ln(1);
                $pdf->Line(2, $pdf->GetY(), 78, $pdf->GetY());
                $pdf->Ln(1);
    
                // Información del ticket más compacta
                $pdf->SetFont('Arial', '', 7);
                
                // Fecha y otros detalles en dos columnas para ahorrar espacio
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(10, 3, 'Fecha:', 0, 0);
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(30, 3, date('d/m/Y H:i', strtotime($venta['FechaCreacion'])), 0, 1);
    
                // Cliente
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(12, 3, 'Cliente:', 0, 0);
                $pdf->SetFont('Arial', '', 7);
                $nombreCompleto = trim($venta['Nombre'] . ' ' . $venta['PrimerApellido'] . ' ' . $venta['SegundoApellido']);
                $pdf->Cell(0, 3, utf8_decode($nombreCompleto), 0, 1);
    
                // CI/NIT y tipo en la misma línea
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(12, 3, 'CI/NIT:', 0, 0);
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(25, 3, $venta['CiNit'], 0, 0);
                
                // Obtener tipo de entrada
                $precio = $this->Venta_model->get_precio_by_id($ticket['idPrecios']);
                $tipo_entrada = '';
                if ($precio) {
                    switch($precio['tipo']) {
                        case 'adulto_mayor':
                            $tipo_entrada = 'Adulto Mayor (61-80)';
                            break;
                        case 'adulto':
                            $tipo_entrada = 'Adulto (18-60)';
                            break;
                        case 'infante':
                            $tipo_entrada = 'Infante (0-17)';
                            break;
                        default:
                            $tipo_entrada = 'No especificado';
                    }
                }
    
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(8, 3, 'Tipo:', 0, 0);
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(0, 3, utf8_decode($tipo_entrada), 0, 1);
    
                $pdf->Ln(1);
    

                // QR Code más pequeño y centrado
               $params['data'] = base_url('venta/validar_ticket/') . $ticket['idTickets'];
              $params['level'] = 'H';
              $params['size'] = 10;
              $params['savename'] = $qr_path . 'qr_' . $ticket['idTickets'] . '.png';
                
                $this->ci_qrcode->generate($params);
                if(file_exists($params['savename'])) {
                    $qr_width = 30; // Reducido de 35 a 30
                    $x_pos = ($pdf->GetPageWidth() - $qr_width) / 2;
                    $pdf->Image($params['savename'], $x_pos, $pdf->GetY(), $qr_width);
                    unlink($params['savename']);
                    
                    $pdf->Ln($qr_width + 1);
                }
    
                // Información importante en un cuadro más compacto
                $pdf->SetFillColor(245, 245, 245);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(0, 3, utf8_decode('¡IMPORTANTE!'), 0, 1, 'C', true);
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(0, 3, utf8_decode('Este ticket es válido solo para el día de hoy'), 0, 1, 'C', true);
    
                // Precio
                if ($precio) {
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(0, 3, 'Precio: Bs. ' . number_format($precio['precio'], 2), 0, 1, 'C');
                }
    
               
            }
    
            // Generar PDF
            $pdf->Output('Tickets_Venta_' . $id_venta . '.pdf', 'D');
    
        } catch (Exception $e) {
            log_message('error', 'Error al generar PDF de tickets: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error al generar el PDF: ' . $e->getMessage());
            redirect('venta');
        }
    }
    
    
    public function validar_ticket($idTicket) {
        $this->load->model('Ticket_model');
        
        // Verificar si el ticket existe y su estado
        $ticket = $this->Ticket_model->get_ticket($idTicket);
        
        if (!$ticket) {
            $data = [
                'status' => 'error',
                'message' => 'Ticket no encontrado',
                'color' => '#dc3545' // Rojo para error
            ];
        } else {
            // Obtener el estado del detalle de venta
            $detalleVenta = $this->db->get_where('detalleventa', ['idTickets' => $idTicket])->row_array();
            
            if (!$detalleVenta) {
                $data = [
                    'status' => 'error',
                    'message' => 'Detalle de venta no encontrado',
                    'color' => '#dc3545'
                ];
            } else if ($detalleVenta['Estado'] === 'yaNoValido') {
                $data = [
                    'status' => 'error',
                    'message' => 'Este ticket ya ha sido utilizado',
                    'color' => '#dc3545'
                ];
            } else {
                // Actualizar el estado en detalleventa
                $this->db->where('idTickets', $idTicket)
                         ->update('detalleventa', ['Estado' => 'yaNoValido']);
                
                $data = [
                    'status' => 'success',
                    'message' => 'Ticket válido',
                    'color' => '#28a745' // Verde para éxito
                ];
            }
        }
        
        // Cargar vista con el resultado
        $this->load->view('venta/validacion_resultado', $data);
    }

    public function generate_qr() {
        $this->load->library('ci_qrcode');
        
        $qr_data = "Your QR code data here";
        try {
            $filename = $this->ci_qrcode->generate($qr_data);
            echo "QR Code generated successfully: " . $filename;
        } catch (Exception $e) {
            log_message('error', 'QR Code generation failed: ' . $e->getMessage());
            echo "Error generating QR code";
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