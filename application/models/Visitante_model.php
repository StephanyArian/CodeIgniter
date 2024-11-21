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
     
    public function get_estadisticas_visitantes($tipo = 'personalizado', $fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio) $fecha_inicio = date('Y-m-d');
        if (!$fecha_fin) $fecha_fin = date('Y-m-d');
        
        $sql = "SELECT 
                    DATE(v.FechaCreacion) as periodo,
                    SUM(CASE WHEN t.tipo = 'Adulto Mayor' THEN dt.Cantidad ELSE 0 END) as total_adulto_mayor,
                    SUM(CASE WHEN t.tipo = 'Adulto' THEN dt.Cantidad ELSE 0 END) as total_adulto,
                    SUM(CASE WHEN t.tipo = 'Infante' THEN dt.Cantidad ELSE 0 END) as total_infante
                FROM venta v
                JOIN detalleventa dt ON v.idVenta = dt.idVenta
                JOIN tickets t ON dt.idTickets = t.idTickets
                WHERE v.Estado = 1 
                AND DATE(v.FechaCreacion) BETWEEN ? AND ?
                GROUP BY DATE(v.FechaCreacion)
                ORDER BY periodo ASC";
                
        $query = $this->db->query($sql, array($fecha_inicio, $fecha_fin));
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