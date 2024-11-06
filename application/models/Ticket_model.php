<?php
class Ticket_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all_tickets_with_visitantes() {
        $this->db->select('tickets.*,
                           visitante.Nombre,
                           visitante.PrimerApellido,
                           visitante.SegundoApellido,
                           visitante.CiNit,
                           precios.precio as Total,
                           precios.tipo,
                           venta.FechaCreacion');
        $this->db->from('tickets');
        $this->db->join('visitante', 'visitante.idVisitante = tickets.idVisitante');
        $this->db->join('precios', 'precios.id = tickets.idPrecios');
        $this->db->join('detalleventa', 'detalleventa.idTickets = tickets.idTickets');
        $this->db->join('venta', 'venta.idVenta = detalleventa.idVenta');
        $this->db->where('precios.estado', 1); // Para obtener solo los precios activos
        $this->db->order_by('venta.FechaCreacion', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function get_all_tickets() {
        $this->db->select('tickets.*, visitante.Nombre, visitante.PrimerApellido, visitante.SegundoApellido, visitante.CiNit, venta.Monto as Total, venta.FechaCreacion, precios.tipo, precios.precio');
        $this->db->from('tickets');
        $this->db->join('visitante', 'visitante.idVisitante = tickets.idVisitante');
        $this->db->join('detalleventa', 'detalleventa.idTickets = tickets.idTickets');
        $this->db->join('venta', 'venta.idVenta = detalleventa.idVenta');
        $this->db->join('precios', 'precios.id = tickets.idPrecios');
        $this->db->order_by('venta.FechaCreacion', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_ticket_details($id) {
        $this->db->select('tickets.*, visitante.*, venta.Monto as Total, venta.FechaCreacion, detalleventa.*, precios.tipo, precios.precio');
        $this->db->from('tickets');
        $this->db->join('visitante', 'visitante.idVisitante = tickets.idVisitante');
        $this->db->join('detalleventa', 'detalleventa.idTickets = tickets.idTickets');
        $this->db->join('venta', 'venta.idVenta = detalleventa.idVenta');
        $this->db->join('precios', 'precios.id = tickets.idPrecios');
        $this->db->where('tickets.idTickets', $id);
        return $this->db->get()->row_array();
    }

  

    //Impresion de tickets
    public function get_tickets_by_venta($id_venta) {
        $this->db->select('tickets.*,
                          visitante.Nombre,
                          visitante.PrimerApellido,
                          visitante.SegundoApellido,
                          visitante.CiNit,
                          precios.precio,
                          precios.tipo');
        $this->db->from('tickets');
        $this->db->join('visitante', 'visitante.idVisitante = tickets.idVisitante');
        $this->db->join('detalleventa', 'detalleventa.idTickets = tickets.idTickets');
        $this->db->join('precios', 'precios.id = tickets.idPrecios');
        $this->db->where('detalleventa.idVenta', $id_venta);
        return $this->db->get()->result_array();
    }

    public function get_ticket($idTicket) {
        $this->db->select('tickets.*, detalleventa.Estado')
                 ->from('tickets')
                 ->join('detalleventa', 'detalleventa.idTickets = tickets.idTickets')
                 ->where('tickets.idTickets', $idTicket);
        return $this->db->get()->row_array();
    }

    public function get_ventas_resumen($fecha_inicio, $fecha_fin) {
        $this->db->select("
            DATE(venta.FechaCreacion) as fecha,
            COUNT(DISTINCT detalleventa.idTickets) as total_tickets,
            SUM(CASE WHEN tickets.descripcion = 'Es para 61-80' THEN 1 ELSE 0 END) as total_adulto_mayor,
            SUM(CASE WHEN tickets.descripcion = 'Es para 18-60' THEN 1 ELSE 0 END) as total_adulto,
            SUM(CASE WHEN tickets.descripcion = 'Es para 0-17' THEN 1 ELSE 0 END) as total_infante,
            SUM(precios.precio) as ingresos_totales
        ");
        
        $this->db->from('detalleventa');
        $this->db->join('venta', 'detalleventa.idVenta = venta.idVenta');
        $this->db->join('tickets', 'detalleventa.idTickets = tickets.idTickets');
        $this->db->join('precios', 'tickets.idPrecios = precios.id');
        
        $this->db->where('DATE(venta.FechaCreacion) >=', $fecha_inicio);
        $this->db->where('DATE(venta.FechaCreacion) <=', $fecha_fin);
        
        $this->db->group_by('fecha');
        $this->db->order_by('fecha', 'ASC');
        
        return $this->db->get()->result_array();
    }
}
?>
