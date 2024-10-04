<?php
class Visitante_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all_visitantes() {
        $query = $this->db->get('visitante');
        return $query->result_array();
    }

    public function get_visitante_by_id($id) {
        $query = $this->db->get_where('visitante', array('idVisitante' => $id));
        return $query->row_array();
    }

    public function get_visitante_by_ci_nit($ci_nit) {
        $query = $this->db->get_where('visitante', array('CiNit' => $ci_nit));
        return $query->row_array();
    }
     
    public function get_estadisticas_visitantes($fecha_inicio = null, $fecha_fin = null) {
        $this->db->select('COUNT(DISTINCT v.idVisitante) as total_visitantes');
        $this->db->select('SUM(dv.CantAdultoMayor) as total_adulto_mayor, SUM(dv.CantAdulto) as total_adulto, SUM(dv.CantInfante) as total_infante');
        $this->db->select('h.Dia, COUNT(*) as visitas_por_dia');
        $this->db->from('visitante v');
        $this->db->join('venta ven', 'ven.idVisitante = v.idVisitante');
        $this->db->join('detalleventa dv', 'dv.idVenta = ven.idVenta');
        $this->db->join('tickets t', 't.idVenta = ven.idVenta');
        $this->db->join('horarios h', 'h.idHorarios = t.idHorarios');
        if ($fecha_inicio && $fecha_fin) {
            $this->db->where('ven.FechaCreacion >=', $fecha_inicio);
            $this->db->where('ven.FechaCreacion <=', $fecha_fin);
        }
        $this->db->group_by('h.Dia');
        $query = $this->db->get();
        return $query->result_array();
    } 
    
    public function insert_visitante($data) {
        $data['FechaCreacion'] = date('Y-m-d H:i:s');
        return $this->db->insert('visitante', $data);
    }

    public function update_visitante($id, $data) {
        $data['FechaActualizacion'] = date('Y-m-d H:i:s');
        $this->db->where('idVisitante', $id);
        return $this->db->update('visitante', $data);
    }

    public function delete_visitante($id) {
        $this->db->where('idVisitante', $id);
        return $this->db->delete('visitante');
    }
}
?>