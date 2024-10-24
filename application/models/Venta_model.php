<?php
class Venta_model extends CI_Model {
    public function __construct() {
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
        return $this->db->get('precios')->result_array();
    }

    public function insert_venta($data) {
        $this->db->trans_start();
    
        // Calculate total amount
        $precios = $this->get_precios_activos();
        $monto_total = 0;
        $precio_adulto_mayor = 0;
        $precio_adulto = 0;
        $precio_infante = 0;
        
        foreach ($precios as $precio) {
            if ($precio['tipo'] == 'adulto_mayor') {
                $monto_total += $data['CantAdultoMayor'] * $precio['precio'];
                $precio_adulto_mayor = $precio['id'];
            }
            if ($precio['tipo'] == 'adulto') {
                $monto_total += $data['CantAdulto'] * $precio['precio'];
                $precio_adulto = $precio['id'];
            }
            if ($precio['tipo'] == 'infante') {
                $monto_total += $data['CantInfante'] * $precio['precio'];
                $precio_infante = $precio['id'];
            }
        }
    
        // Prepare data for venta table
        $venta_data = array(
            'idVisitante' => $data['idVisitante'],
            'Monto' => $monto_total,
            'Comentario' => $data['Comentario'],
            'idUsuarios' => $data['idUsuarios'],
            'IdUsuarioAuditoria' => $data['idUsuarios']
        );
    
        // Insert venta
        $this->db->insert('venta', $venta_data);
        $id_venta = $this->db->insert_id();
        
        // Array para almacenar los IDs de tickets creados
        $ticket_ids = [];
        
        // Crear tickets para adultos mayores
        for ($i = 0; $i < $data['CantAdultoMayor']; $i++) {
            $ticket = array(
                'idVisitante' => $data['idVisitante'],
                'IdUsuarioAuditoria' => $data['idUsuarios'],
                'descripcion' => 'Es para 61-80',
                'estado' => 'Activo',
                'idPrecios' => $precio_adulto_mayor
            );
            $this->db->insert('tickets', $ticket);
            $ticket_ids[] = $this->db->insert_id();
        }
        
        // Crear tickets para adultos
        for ($i = 0; $i < $data['CantAdulto']; $i++) {
            $ticket = array(
                'idVisitante' => $data['idVisitante'],
                'IdUsuarioAuditoria' => $data['idUsuarios'],
                'descripcion' => 'Es para 18-60',
                'estado' => 'Activo',
                'idPrecios' => $precio_adulto
            );
            $this->db->insert('tickets', $ticket);
            $ticket_ids[] = $this->db->insert_id();
        }
        
        // Crear tickets para infantes
        for ($i = 0; $i < $data['CantInfante']; $i++) {
            $ticket = array(
                'idVisitante' => $data['idVisitante'],
                'IdUsuarioAuditoria' => $data['idUsuarios'],
                'descripcion' => 'Es para 0-17',
                'estado' => 'Activo',
                'idPrecios' => $precio_infante
            );
            $this->db->insert('tickets', $ticket);
            $ticket_ids[] = $this->db->insert_id();
        }
    
        // Insert detalleventa para cada ticket
        foreach ($ticket_ids as $ticket_id) {
            $detalle_venta = array(
                'idVenta' => $id_venta,
                'SubTotal' => $monto_total,
                'CantAdultoMayor' => $data['CantAdultoMayor'],
                'CantAdulto' => $data['CantAdulto'],
                'CantInfante' => $data['CantInfante'],
                'IdUsuarioAuditoria' => $data['idUsuarios'],
                'idTickets' => $ticket_id,
                'idHorarios' => $data['idHorarios']
            );
            $this->db->insert('detalleventa', $detalle_venta);
        }
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
    
        return $id_venta;
    }

    public function get_venta_details($id_venta) {
        if (!$id_venta) {
            return null;
        }
    
        $this->db->select('venta.*, visitante.*, 
                           detalleventa.CantAdultoMayor, detalleventa.CantAdulto, detalleventa.CantInfante,
                           precios.precio,
                           tickets.descripcion as tipo_ticket,
                           precios.tipo as tipo_precio');
        $this->db->from('venta');
        $this->db->join('visitante', 'visitante.idVisitante = venta.idVisitante');
        $this->db->join('detalleventa', 'detalleventa.idVenta = venta.idVenta');
        $this->db->join('tickets', 'tickets.idTickets = detalleventa.idTickets');
        $this->db->join('precios', 'precios.id = tickets.idPrecios');
        $this->db->where('venta.idVenta', $id_venta);
        $result = $this->db->get()->result_array();
    
        // Si no hay resultados, retornar null
        if (empty($result)) {
            return null;
        }
    
        // Procesar los resultados para obtener los precios correctos
        $precios = [];
        foreach ($result as $row) {
            if ($row['tipo_precio'] == 'adulto_mayor') {
                $precios['adulto_mayor'] = $row['precio'];
            } elseif ($row['tipo_precio'] == 'adulto') {
                $precios['adulto'] = $row['precio'];
            } elseif ($row['tipo_precio'] == 'infante') {
                $precios['infante'] = $row['precio'];
            }
        }
    
        // Añadir los precios al primer elemento del resultado
        $result[0]['PrecioAdultoMayor'] = isset($precios['adulto_mayor']) ? $precios['adulto_mayor'] : 0;
        $result[0]['PrecioAdulto'] = isset($precios['adulto']) ? $precios['adulto'] : 0;
        $result[0]['PrecioInfante'] = isset($precios['infante']) ? $precios['infante'] : 0;
        
        return $result[0]; // Retornar solo el primer elemento del array
    }
}
?>