<?php
class Ticket_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all_tickets_with_visitantes() {
        $this->db->select('tickets.*,
                          usuarios.Nombres as NombreUsuario,
                          usuarios.PrimerApellido as ApellidoUsuario');
        $this->db->from('tickets');
        $this->db->join('usuarios', 'usuarios.idUsuarios = tickets.IdUsuarioAuditoria');
        $this->db->where('tickets.estado', 'activo');
        $this->db->order_by('tickets.fecha_actualizacion', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_ticket_details($id) {
        $this->db->select('tickets.*, usuarios.Nombres as NombreUsuario, 
                          usuarios.PrimerApellido as ApellidoUsuario');
        $this->db->from('tickets');
        $this->db->join('usuarios', 'usuarios.idUsuarios = tickets.IdUsuarioAuditoria');
        $this->db->where('tickets.idTickets', $id);
        return $this->db->get()->row_array();
    }

    public function insert_ticket($data) {
        return $this->db->insert('tickets', $data);
    }

    public function update_ticket($id, $data) {
        $this->db->where('idTickets', $id);
        return $this->db->update('tickets', $data);
    }

    public function delete_ticket($id) {
        $this->db->where('idTickets', $id);
        return $this->db->delete('tickets');
    }

    public function get_inactive_tickets() {
        $this->db->select('tickets.*,
                          usuarios.Nombres as NombreUsuario,
                          usuarios.PrimerApellido as ApellidoUsuario');
        $this->db->from('tickets');
        $this->db->join('usuarios', 'usuarios.idUsuarios = tickets.IdUsuarioAuditoria');
        $this->db->where('tickets.estado', 'inactivo');
        $this->db->order_by('tickets.fecha_actualizacion', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_active_tickets() {
        $this->db->select('tickets.*,
                          usuarios.Nombres as NombreUsuario,
                          usuarios.PrimerApellido as ApellidoUsuario');
        $this->db->from('tickets');
        $this->db->join('usuarios', 'usuarios.idUsuarios = tickets.IdUsuarioAuditoria');
        $this->db->where('tickets.estado', 'activo');
        $this->db->order_by('tickets.fecha_actualizacion', 'DESC');
        return $this->db->get()->result_array();
    }

    // Existing method remains unchanged
    public function activate_ticket($id) {
        $data = array(
            'estado' => 'activo',
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );
        $this->db->where('idTickets', $id);
        return $this->db->update('tickets', $data);
    }

    
public function get_ticket_validation_status($ticket_id_str) {
    // Separar el ID de venta y el número de ticket
    $parts = explode('_', $ticket_id_str);
    if (count($parts) != 2) {
        return null;
    }
    
    $id_venta = $parts[0];
    $ticket_number = $parts[1];
    
    $this->db->select('
        dv.idDetalleVenta,
        dv.Estado as EstadoTicket,
        dv.NroTicket,
        t.descripcion as TipoTicket,
        t.precio,
        v.FechaCreacion,
        v.idVenta,
        vis.Nombre,
        vis.PrimerApellido,
        vis.CiNit,
        h.fecha_actualizacion as FechaHorario,
        h.HoraEntrada,
        h.HoraCierre
    ');
    $this->db->from('detalleventa dv');
    $this->db->join('venta v', 'v.idVenta = dv.idVenta');
    $this->db->join('tickets t', 't.idTickets = dv.idTickets');
    $this->db->join('visitante vis', 'vis.idVisitante = v.idVisitante');
    $this->db->join('horarios h', 'h.idHorarios = v.idHorarios');
    $this->db->where('v.idVenta', $id_venta);
    $this->db->where('dv.NroTicket', $ticket_number);
    
    $result = $this->db->get()->row_array();
    
    if ($result) {
        $fecha_horario = date('Y-m-d', strtotime($result['FechaHorario']));
        $hora_actual = date('H:i:s');
        $fecha_actual = date('Y-m-d');
        
        $result['es_valido'] = (
            $fecha_actual == $fecha_horario &&
            $hora_actual >= $result['HoraEntrada'] &&
            $hora_actual <= $result['HoraCierre'] &&
            $result['EstadoTicket'] != 'Usado'
        );
    }
    
    return $result;
}
    public function marcar_ticket_usado($detalle_id) {
        $this->db->where('idDetalleVenta', $detalle_id);
        return $this->db->update('detalleventa', ['Estado' => 'Usado']);
    }

    
}
?>
