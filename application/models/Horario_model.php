<?php
class Horario_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function verificar_disponibilidad($idHorarios) {
        $this->db->select('h.MaxVisitantes, COUNT(t.idTickets) as tickets_vendidos');
        $this->db->from('horarios h');
        $this->db->join('tickets t', 't.idHorarios = h.idHorarios', 'left');
        $this->db->where('h.idHorarios', $idHorarios);
        $this->db->group_by('h.idHorarios');
        $query = $this->db->get();
    
        if ($query->num_rows() == 0) {
            return false; // El horario no existe
        }
    
        $result = $query->row();
        $disponibles = $result->MaxVisitantes - $result->tickets_vendidos;
    
        return $disponibles;
    }
    
    public function get_horarios_disponibles() {
        $this->db->select('h.*, COUNT(t.idTickets) as tickets_vendidos');
        $this->db->from('horarios h');
        $this->db->join('tickets t', 't.idHorarios = h.idHorarios', 'left');
        $this->db->where('h.Estado', 1);
        $this->db->where('h.Dia >=', date('Y-m-d')); // Solo horarios futuros
        $this->db->group_by('h.idHorarios');
        $this->db->having('h.MaxVisitantes > tickets_vendidos OR tickets_vendidos IS NULL');
        $this->db->order_by('h.Dia', 'ASC');
        $this->db->order_by('h.HoraEntrada', 'ASC');
        $query = $this->db->get();
    
        return $query->result_array();
    }

    public function insert_horario($data) {
        return $this->db->insert('horarios', $data);
    }

    public function get_all_horarios() {
        $this->db->order_by('Dia', 'ASC');
        $this->db->order_by('HoraEntrada', 'ASC');
        return $this->db->get('horarios')->result_array();
    }

    public function get_horario($id) {
        return $this->db->get_where('horarios', array('idHorarios' => $id))->row_array();
    }

    public function get_ocupacion_horarios() {
        $this->db->select('h.*, COUNT(t.idTickets) as visitantes_actuales');
        $this->db->from('horarios h');
        $this->db->join('tickets t', 't.idHorarios = h.idHorarios', 'left');
        $this->db->where('h.Dia >=', date('Y-m-d')); // Solo horarios futuros
        $this->db->group_by('h.idHorarios');
        $this->db->order_by('h.Dia', 'ASC');
        $this->db->order_by('h.HoraEntrada', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_horario($id, $data) {
        $this->db->where('idHorarios', $id);
        return $this->db->update('horarios', $data);
    }

    public function delete_horario($id) {
        $this->db->where('idHorarios', $id);
        return $this->db->delete('horarios');
    }
}
?>