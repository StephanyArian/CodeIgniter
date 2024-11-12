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

    public function activate_ticket($id) {
        $data = array(
            'estado' => 'activo',
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );
        $this->db->where('idTickets', $id);
        return $this->db->update('tickets', $data);
    }
}
?>
