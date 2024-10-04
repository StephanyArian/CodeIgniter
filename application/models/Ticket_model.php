<?php
class Ticket_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all_tickets() {
        $this->db->select('tickets.*, visitante.Nombre, visitante.PrimerApellido, visitante.CiNit, venta.Monto as Total, detalleventa.SubTotal, venta.FechaCreacion');
        $this->db->from('tickets');
        $this->db->join('visitante', 'visitante.idVisitante = tickets.idVisitante');
        $this->db->join('venta', 'venta.idVenta = tickets.idVenta');
        $this->db->join('detalleventa', 'detalleventa.idVenta = venta.idVenta');
        $this->db->order_by('venta.FechaCreacion', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_ticket_details($id) {
        $this->db->select('tickets.*, visitante.*, venta.Monto as Total, venta.FechaCreacion, detalleventa.*');
        $this->db->from('tickets');
        $this->db->join('visitante', 'visitante.idVisitante = tickets.idVisitante');
        $this->db->join('venta', 'venta.idVenta = tickets.idVenta');
        $this->db->join('detalleventa', 'detalleventa.idVenta = venta.idVenta');
        $this->db->where('tickets.idTickets', $id);
        return $this->db->get()->row_array();
    }

    public function get_ventas_resumen($fecha_inicio = null, $fecha_fin = null) {
        $this->db->select('DATE(v.FechaCreacion) as fecha, COUNT(*) as total_tickets, SUM(v.Monto) as ingresos_totales');
        $this->db->select('SUM(dv.CantAdultoMayor) as total_adulto_mayor, SUM(dv.CantAdulto) as total_adulto, SUM(dv.CantInfante) as total_infante');
        $this->db->from('tickets t');
        $this->db->join('venta v', 'v.idVenta = t.idVenta');
        $this->db->join('detalleventa dv', 'dv.idVenta = v.idVenta');
        if ($fecha_inicio && $fecha_fin) {
            $this->db->where('v.FechaCreacion >=', $fecha_inicio);
            $this->db->where('v.FechaCreacion <=', $fecha_fin);
        }
        $this->db->group_by('DATE(v.FechaCreacion)');
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>