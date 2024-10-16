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

    public function buscar_visitante($termino) {
        $termino = $this->db->escape_like_str($termino);
        log_message('debug', 'Término de búsqueda original: ' . $termino);
        
        $palabras = explode(' ', $termino);
        
        $this->db->select('idVisitante, Nombre, PrimerApellido, SegundoApellido, CiNit, NroCelular');
        $this->db->from('visitante');
        // Comentar o eliminar esta línea si quieres buscar en todos los registros, independientemente del estado
        // $this->db->where('Estado', 1);
        
        $this->db->group_start();
        foreach ($palabras as $palabra) {
            $palabra = '%' . strtolower($palabra) . '%';
            $this->db->or_group_start()
                ->where('LOWER(CiNit) LIKE', $palabra)
                ->or_where('LOWER(Nombre) LIKE', $palabra)
                ->or_where('LOWER(PrimerApellido) LIKE', $palabra)
                ->or_where('LOWER(SegundoApellido) LIKE', $palabra)
                ->or_where('LOWER(NroCelular) LIKE', $palabra)
            ->group_end();
        }
        $this->db->group_end();
        
        $this->db->limit(10);
        
        $query = $this->db->get();
        log_message('debug', 'Query SQL: ' . $this->db->last_query());
    
        $result = $query->result_array();
        log_message('debug', 'Número de resultados: ' . count($result));
        log_message('debug', 'Resultados de la query: ' . print_r($result, true));
        
        return $result;
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