<?php
class Venta_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_ventas() {
        $this->db->select('venta.*, 
                           visitante.Nombre, 
                           visitante.PrimerApellido, 
                           visitante.CiNit,
                           dv.CantAdultoMayor,
                           dv.CantAdulto,
                           dv.CantInfante');
        $this->db->from('venta');
        $this->db->join('visitante', 'visitante.idVisitante = venta.idVisitante');
        $this->db->join('(SELECT idVenta, 
                                 MAX(CantAdultoMayor) as CantAdultoMayor,
                                 MAX(CantAdulto) as CantAdulto,
                                 MAX(CantInfante) as CantInfante 
                          FROM detalleventa 
                          GROUP BY idVenta) as dv', 'dv.idVenta = venta.idVenta', 'left');
        $this->db->order_by('venta.FechaCreacion', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_precios_activos() {
        $this->db->where('Estado', 1);
        $precios = $this->db->get('precios')->result_array();
        log_message('debug', 'Precios activos recuperados: ' . json_encode($precios));
        return $precios;
    }

    public function get_next_ticket_number($idHorarios) {
        if (!$idHorarios) {
            log_message('error', 'get_next_ticket_number: idHorarios es nulo o inválido');
            return false;
        }
        
        $this->db->select('MAX(NroTicket) as ultimo_numero');
        $this->db->from('detalleventa');
        $this->db->where('idHorarios', $idHorarios);
        $query = $this->db->get();
        $result = $query->row();
        
        $next_number = empty($result->ultimo_numero) ? 1 : ($result->ultimo_numero + 1);
        log_message('debug', 'Próximo número de ticket para horario ' . $idHorarios . ': ' . $next_number);
        return $next_number;
    }

    public function insert_venta($data) {
        // Validación inicial de datos
        if (!isset($data['idVisitante']) || !isset($data['idHorarios']) || !isset($data['idUsuarios'])) {
            log_message('error', 'Datos de venta incompletos: ' . json_encode($data));
            return FALSE;
        }

        // Asegurar que las cantidades sean enteros
        $data['CantAdultoMayor'] = isset($data['CantAdultoMayor']) ? (int)$data['CantAdultoMayor'] : 0;
        $data['CantAdulto'] = isset($data['CantAdulto']) ? (int)$data['CantAdulto'] : 0;
        $data['CantInfante'] = isset($data['CantInfante']) ? (int)$data['CantInfante'] : 0;

        // Log de datos iniciales
        log_message('debug', 'Iniciando insert_venta con datos: ' . json_encode($data));
        

        //TRANSACCION
        $this->db->trans_start();
    
        // Verificar disponibilidad en el horario
        $this->load->model('Horario_model');
        $disponibles = $this->Horario_model->verificar_disponibilidad($data['idHorarios']);
        $total_tickets = $data['CantAdultoMayor'] + $data['CantAdulto'] + $data['CantInfante'];
        
        log_message('debug', 'Disponibilidad: ' . $disponibles . ', Total tickets requeridos: ' . $total_tickets);
        
        if ($disponibles === false || $disponibles < $total_tickets) {
            log_message('error', 'No hay disponibilidad suficiente. Disponible: ' . $disponibles . ', Requerido: ' . $total_tickets);
            return FALSE;
        }
    
        // Obtener y verificar precios activos
        $precios = $this->get_precios_activos();
        if (empty($precios)) {
            log_message('error', 'No se encontraron precios activos');
            return FALSE;
        }

        // Initialize variables
        $monto_total = 0;
        $precio_adulto_mayor = $precio_adulto = $precio_infante = null;
        $subtotales = [
            'adulto_mayor' => 0,
            'adulto' => 0,
            'infante' => 0
        ];

        // Calcular montos
        foreach ($precios as $precio) {
            switch($precio['tipo']) {
                case 'adulto_mayor':
                    if ($data['CantAdultoMayor'] > 0) {
                        $subtotales['adulto_mayor'] = (float)$data['CantAdultoMayor'] * (float)$precio['precio'];
                        $monto_total += $subtotales['adulto_mayor'];
                        $precio_adulto_mayor = $precio['id'];
                        log_message('debug', 'Calculado adulto mayor: ' . $subtotales['adulto_mayor']);
                    }
                    break;
                case 'adulto':
                    if ($data['CantAdulto'] > 0) {
                        $subtotales['adulto'] = (float)$data['CantAdulto'] * (float)$precio['precio'];
                        $monto_total += $subtotales['adulto'];
                        $precio_adulto = $precio['id'];
                        log_message('debug', 'Calculado adulto: ' . $subtotales['adulto']);
                    }
                    break;
                case 'infante':
                    if ($data['CantInfante'] > 0) {
                        $subtotales['infante'] = (float)$data['CantInfante'] * (float)$precio['precio'];
                        $monto_total += $subtotales['infante'];
                        $precio_infante = $precio['id'];
                        log_message('debug', 'Calculado infante: ' . $subtotales['infante']);
                    }
                    break;
            }
        }

        log_message('debug', 'Monto total calculado: ' . $monto_total);
        log_message('debug', 'Subtotales: ' . json_encode($subtotales));

        // Verificar que se encontraron todos los precios necesarios
        if (($data['CantAdultoMayor'] > 0 && $precio_adulto_mayor === null) ||
            ($data['CantAdulto'] > 0 && $precio_adulto === null) ||
            ($data['CantInfante'] > 0 && $precio_infante === null)) {
            log_message('error', 'No se encontraron todos los precios necesarios');
            return FALSE;
        }
    
        // Prepare data for venta table
        $venta_data = array(
            'idVisitante' => $data['idVisitante'],
            'Monto' => $monto_total,
            'Comentario' => isset($data['Comentario']) ? $data['Comentario'] : '',
            'idUsuarios' => $data['idUsuarios'],
           
            
        );
    
        // Insert venta
        $this->db->insert('venta', $venta_data);
        $id_venta = $this->db->insert_id();
        
        if (!$id_venta) {
            log_message('error', 'Error al insertar venta');
            return FALSE;
        }

        log_message('debug', 'Venta insertada con ID: ' . $id_venta);
        
        // Array para almacenar los IDs de tickets creados
        $ticket_ids = [];
        
        // Crear tickets para cada tipo de visitante
        $ticket_types = [
            'adulto_mayor' => [
                'cantidad' => $data['CantAdultoMayor'],
                'precio_id' => $precio_adulto_mayor,
                'descripcion' => 'Es para 61-80'
            ],
            'adulto' => [
                'cantidad' => $data['CantAdulto'],
                'precio_id' => $precio_adulto,
                'descripcion' => 'Es para 18-60'
            ],
            'infante' => [
                'cantidad' => $data['CantInfante'],
                'precio_id' => $precio_infante,
                'descripcion' => 'Es para 0-17'
            ]
        ];

        foreach ($ticket_types as $type => $info) {
            for ($i = 0; $i < $info['cantidad']; $i++) {
                $ticket = array(
                    'idVisitante' => $data['idVisitante'],
                    'IdUsuarioAuditoria' => $data['idUsuarios'],
                    'descripcion' => $info['descripcion'],
                    'idPrecios' => $info['precio_id'],
                    
                );
                
                $this->db->insert('tickets', $ticket);
                $ticket_id = $this->db->insert_id();
                
                if (!$ticket_id) {
                    log_message('error', 'Error al insertar ticket tipo ' . $type);
                    return FALSE;
                }
                
                $ticket_ids[] = $ticket_id;
                log_message('debug', 'Ticket creado: ' . $ticket_id . ' tipo: ' . $type);
            }
        }
    
        // Insert detalleventa para cada ticket
        foreach ($ticket_ids as $ticket_id) {
            $nro_ticket = $this->get_next_ticket_number($data['idHorarios']);
            
            if (!$nro_ticket) {
                log_message('error', 'Error al obtener número de ticket');
                return FALSE;
            }

            $detalle_venta = array(
                'idVenta' => $id_venta,
                'SubTotal' => $monto_total,
                'CantAdultoMayor' => $data['CantAdultoMayor'],
                'CantAdulto' => $data['CantAdulto'],
                'CantInfante' => $data['CantInfante'],
                'IdUsuarioAuditoria' => $data['idUsuarios'],
                'idTickets' => $ticket_id,
                'idHorarios' => $data['idHorarios'],
                'NroTicket' => $nro_ticket,
                'estado' => 'Comprado'
            );
            
            $this->db->insert('detalleventa', $detalle_venta);
            
            if ($this->db->affected_rows() <= 0) {
                log_message('error', 'Error al insertar detalle_venta para ticket ' . $ticket_id);
                return FALSE;
            }

            log_message('debug', 'Detalle venta creado para ticket: ' . $ticket_id . ' con número: ' . $nro_ticket);
        }
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Transacción fallida en insert_venta');
            return FALSE;
        }
    
        log_message('debug', 'Venta completada exitosamente. ID: ' . $id_venta);
        redirect('/venta/detalle/' . $id_venta);
        return $id_venta;
    }

    public function insert_new_ticket() {
        $data = $this->input->post(); // Assuming you're getting the data from a form
        $this->load->model('Venta_model');
        $id_venta = $this->Venta_model->insert_venta($data);
        
        if ($id_venta) {
            // Redirect to the "Imprimir Tickets" page
            redirect('/venta/imprimir_tickets/' . $id_venta);
        } else {
            // Handle the error case
            // For example, you can set a flash message and redirect back to the form
            $this->session->set_flashdata('error', 'Error al crear la venta.');
             redirect('/venta/detalle/' . $id_venta);
            redirect('/venta/new');
        }
    }
    
    //obtenr el precio por id imprimir ticket
    public function get_precio_by_id($id_precio)
    {
    $this->db->where('id', $id_precio);
    $precio = $this->db->get('precios')->row_array();

    if (!$precio) {
        log_message('error', 'No se encontró el precio con ID: ' . $id_precio);
        return null;
    }

    return $precio;
   }

    public function get_venta_details($id_venta) {
        if (!$id_venta) {
            log_message('error', 'get_venta_details: id_venta es nulo o inválido');
            return null;
        }
    
        // Modificada la consulta para evitar ambigüedad en las columnas
        $this->db->select('venta.*, 
            visitante.Nombre, 
            visitante.PrimerApellido, 
            visitante.SegundoApellido,
            visitante.CiNit, 
            visitante.Email, 
            visitante.NroCelular,
            dv.CantAdultoMayor,
            dv.CantAdulto,
            dv.CantInfante,
            p_am.precio as PrecioAdultoMayor,
            p_a.precio as PrecioAdulto,
            p_i.precio as PrecioInfante');
        $this->db->from('venta');
        $this->db->join('visitante', 'visitante.idVisitante = venta.idVisitante');
        
        // Subconsulta para obtener las cantidades
        $this->db->join('(
            SELECT idVenta, 
                MAX(CantAdultoMayor) as CantAdultoMayor,
                MAX(CantAdulto) as CantAdulto,
                MAX(CantInfante) as CantInfante
            FROM detalleventa 
            GROUP BY idVenta
        ) as dv', 'dv.idVenta = venta.idVenta');
        
        // Joins para obtener los precios de cada tipo
        $this->db->join('(
            SELECT precio
            FROM precios 
            WHERE tipo = "adulto_mayor" AND Estado = 1
            LIMIT 1
        ) as p_am', '1=1', 'left');
        
        $this->db->join('(
            SELECT precio
            FROM precios 
            WHERE tipo = "adulto" AND Estado = 1
            LIMIT 1
        ) as p_a', '1=1', 'left');
        
        $this->db->join('(
            SELECT precio
            FROM precios 
            WHERE tipo = "infante" AND Estado = 1
            LIMIT 1
        ) as p_i', '1=1', 'left');
        
        $this->db->where('venta.idVenta', $id_venta);
        
        $result = $this->db->get()->row_array();
    
        if (empty($result)) {
            log_message('error', 'No se encontraron detalles para la venta ID: ' . $id_venta);
            return null;
        }
    
        log_message('debug', 'Detalles de venta recuperados para ID: ' . $id_venta);
        return $result;
    }
 }
?>