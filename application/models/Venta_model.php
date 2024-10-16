<?php
class Venta_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all_ventas() {
        $this->db->select('venta.*, visitante.Nombre, visitante.PrimerApellido, visitante.CiNit, detalleventa.CantAdultoMayor, detalleventa.CantAdulto, detalleventa.CantInfante');
        $this->db->from('venta');
        $this->db->join('visitante', 'visitante.idVisitante = venta.idVisitante');
        $this->db->join('detalleventa', 'detalleventa.idVenta = venta.idVenta');
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
        foreach ($precios as $precio) {
            if ($precio['tipo'] == 'adulto_mayor') $monto_total += $data['CantAdultoMayor'] * $precio['precio'];
            if ($precio['tipo'] == 'adulto') $monto_total += $data['CantAdulto'] * $precio['precio'];
            if ($precio['tipo'] == 'infante') $monto_total += $data['CantInfante'] * $precio['precio'];
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
    
        // Insert ticket
        $ticket = array(
            'idVisitante' => $data['idVisitante'],
            'idHorarios' => $data['idHorarios'],
            'IdUsuarioAuditoria' => $data['idUsuarios']
        );
        $this->db->insert('tickets', $ticket);
        $id_ticket = $this->db->insert_id();
    
        // Insert detalleventa
        $detalle_venta = array(
            'idVenta' => $id_venta,
            'SubTotal' => $monto_total,
            'CantAdultoMayor' => $data['CantAdultoMayor'],
            'CantAdulto' => $data['CantAdulto'],
            'CantInfante' => $data['CantInfante'],
            'IdUsuarioAuditoria' => $data['idUsuarios'],
            'idTickets' => $id_ticket
        );
        $this->db->insert('detalleventa', $detalle_venta);
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
    
        return $id_venta;
    }

    public function get_venta_details($id_venta) {
        $this->db->select('venta.*, visitante.*, detalleventa.*, precios.precio as PrecioAdultoMayor');
        $this->db->from('venta');
        $this->db->join('visitante', 'visitante.idVisitante = venta.idVisitante');
        $this->db->join('detalleventa', 'detalleventa.idVenta = venta.idVenta');
        $this->db->join('precios', 'precios.tipo = "adulto_mayor" AND precios.Estado = 1', 'left');
        $this->db->where('venta.idVenta', $id_venta);
        $result = $this->db->get()->row_array();
    
        // Get prices for adult and child
        $this->db->where('tipo', 'adulto');
        $this->db->where('Estado', 1);
        $result['PrecioAdulto'] = $this->db->get('precios')->row()->precio;
    
        $this->db->where('tipo', 'infante');
        $this->db->where('Estado', 1);
        $result['PrecioInfante'] = $this->db->get('precios')->row()->precio;
    
        return $result;
    }
}
?>