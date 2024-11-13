<?php
class Venta_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all_ventas() {
        $this->db->select('venta.*, visitante.Nombre, visitante.PrimerApellido, 
                          visitante.CiNit, usuarios.NombreUsuario, 
                          h.HoraEntrada, h.HoraCierre,
                          COUNT(dv.Cantidad) as TotalTickets,
                          venta.Monto as MontoTotal');
        $this->db->from('venta');
        $this->db->join('visitante', 'visitante.idVisitante = venta.idVisitante');
        $this->db->join('usuarios', 'usuarios.idUsuarios = venta.idUsuarios');
        $this->db->join('horarios h', 'h.idHorarios = venta.idHorarios');
        $this->db->join('detalleventa dv', 'dv.idVenta = venta.idVenta', 'left');
        $this->db->group_by('venta.idVenta');
        $this->db->order_by('venta.FechaCreacion', 'DESC');
        return $this->db->get()->result_array();
    }

    public function crear_venta($data_venta, $detalles) {
        $this->db->trans_start();
        
        // Calcular monto total de la venta
        $monto_total = 0;
        $next_ticket_number = $this->get_next_ticket_number($data_venta['idHorarios']);
        $detalles_expandidos = [];
        
        // Primero verificamos que hay suficiente espacio para todos los tickets
        $total_tickets = 0;
        foreach ($detalles as $detalle) {
            $total_tickets += $detalle['Cantidad'];
        }
        
        // Verificar disponibilidad total
        if (!$this->verificar_stock_tickets(null, $total_tickets, $data_venta['idHorarios'])) {
            return FALSE;
        }
        
        foreach ($detalles as $detalle) {
            // Obtener precio del ticket
            $this->db->select('precio');
            $this->db->from('tickets');
            $this->db->where('idTickets', $detalle['idTickets']);
            $ticket = $this->db->get()->row();
            
            // Calcular subtotal para este tipo de ticket
            $subtotal = $ticket->precio * $detalle['Cantidad'];
            $monto_total += $subtotal;
            
            // Crear registros individuales manteniendo la cantidad original
            for ($i = 0; $i < $detalle['Cantidad']; $i++) {
                $detalles_expandidos[] = [
                    'idTickets' => $detalle['idTickets'],
                    'Cantidad' => $detalle['Cantidad'],  // Mantenemos la cantidad original
                    'SubTotal' => $subtotal,             // SubTotal para toda la cantidad
                    'NroTicket' => $next_ticket_number++,
                    'Estado' => 'Valido',
                    'IdUsuarioAuditoria' => $data_venta['idUsuarios']
                ];
            }
        }
        
        // Preparar datos de venta
        $data_venta['Monto'] = $monto_total;
        $data_venta['FechaCreacion'] = date('Y-m-d H:i:s');
        $data_venta['Estado'] = 1; // Activo por defecto
        
        // Insertar la venta
        $this->db->insert('venta', $data_venta);
        $id_venta = $this->db->insert_id();
        
        // Insertar los detalles individuales de la venta
        foreach ($detalles_expandidos as $detalle) {
            $detalle['idVenta'] = $id_venta;
            $this->db->insert('detalleventa', $detalle);
        }
        
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE) ? $id_venta : FALSE;
    }
    public function get_precios_activos() {
        $this->db->where('estado', 'activo');
        return $this->db->get('tickets')->result_array();
    }

    
public function get_venta_details($id_venta) {
    $this->db->select('v.*, vi.Nombre, vi.PrimerApellido, vi.CiNit, 
                      vi.NroCelular, vi.Email,
                      t.tipo as TipoTicket, t.precio,
                      t.descripcion as DescripcionTicket,
                      u.NombreUsuario as NombreVendedor,
                      COUNT(dv.Cantidad) as CantidadTotal');  // Agregamos la suma de cantidades
    $this->db->from('venta v');
    $this->db->join('visitante vi', 'vi.idVisitante = v.idVisitante');
    $this->db->join('detalleventa dv', 'dv.idVenta = v.idVenta');
    $this->db->join('tickets t', 't.idTickets = dv.idTickets');
    $this->db->join('usuarios u', 'u.idUsuarios = v.idUsuarios');
    $this->db->where('v.idVenta', $id_venta);
    $this->db->group_by('t.idTickets'); // Agrupamos por tipo de ticket
    return $this->db->get()->result_array();
}
    public function verificar_stock_tickets($id_ticket, $cantidad, $id_horario) {
        // Obtener el total de tickets vendidos para este horario
        $this->db->select('SUM(dv.Cantidad) as tickets_vendidos');
        $this->db->from('detalleventa dv');
        $this->db->join('venta v', 'v.idVenta = dv.idVenta');
        $this->db->where('v.idHorarios', $id_horario);
        $this->db->where('dv.idTickets', $id_ticket);
        $this->db->where('dv.Estado', 'Valido');
        $vendidos = $this->db->get()->row()->tickets_vendidos ?? 0;

        // Obtener el máximo de visitantes permitidos
        $this->db->select('MaxVisitantes');
        $this->db->from('horarios');
        $this->db->where('idHorarios', $id_horario);
        $this->db->where('Estado', 1);
        $max_visitantes = $this->db->get()->row()->MaxVisitantes;

        return ($vendidos + $cantidad) <= $max_visitantes;
    }

    public function get_next_ticket_number($id_horario) {
        $this->db->select_max('NroTicket');
        $this->db->from('detalleventa dv');
        $this->db->join('venta v', 'v.idVenta = dv.idVenta');
        $this->db->where('v.idHorarios', $id_horario);
        $result = $this->db->get()->row();
        return ($result->NroTicket ?? 0) + 1;
    }

    public function actualizar_estado_venta($id_venta, $estado, $comentario = NULL) {
        $data = array(
            'Estado' => $estado,
            'FechaActualizacion' => date('Y-m-d H:i:s')
        );
        
        if ($comentario !== NULL) {
            $data['Comentario'] = $comentario;
        }
        
        $this->db->where('idVenta', $id_venta);
        return $this->db->update('venta', $data);
    }

    public function get_tickets_vendidos($id_horario) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('detalleventa dv');
        $this->db->join('venta v', 'v.idVenta = dv.idVenta');
        $this->db->where('v.idHorarios', $id_horario);
        $this->db->where('dv.Estado', 'Valido');
        return $this->db->get()->row()->total ?? 0;
    }
}
?>