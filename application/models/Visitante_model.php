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
     
    public function get_estadisticas_visitantes($periodo = 'semanal', $fecha_inicio = null, $fecha_fin = null) {
        // Si no se proporcionan fechas, usar el mes actual
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('first day of this month'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d', strtotime('last day of this month'));
        }
    
        // Consulta base para estadísticas semanales
        $this->db->select("
            DATE_FORMAT(ven.FechaCreacion, '%Y-%u') as semana,
            MIN(DATE_FORMAT(ven.FechaCreacion, '%d/%m/%Y')) as fecha_inicio_semana,
            SUM(dv.CantAdultoMayor) as total_adulto_mayor,
            SUM(dv.CantAdulto) as total_adulto,
            SUM(dv.CantInfante) as total_infante
        ");
        $this->db->from('venta ven');
        $this->db->join('detalleventa dv', 'dv.idVenta = ven.idVenta');
        $this->db->where('ven.FechaCreacion >=', $fecha_inicio);
        $this->db->where('ven.FechaCreacion <=', $fecha_fin);
        $this->db->group_by("DATE_FORMAT(ven.FechaCreacion, '%Y-%u')");
        $this->db->order_by('semana', 'ASC');
        
        $query = $this->db->get();
        $resultados_semanales = $query->result_array();
        
        // Consulta para totales generales del período
        $this->db->select("
            SUM(dv.CantAdultoMayor) as total_adulto_mayor,
            SUM(dv.CantAdulto) as total_adulto,
            SUM(dv.CantInfante) as total_infante
        ");
        $this->db->from('venta ven');
        $this->db->join('detalleventa dv', 'dv.idVenta = ven.idVenta');
        $this->db->where('ven.FechaCreacion >=', $fecha_inicio);
        $this->db->where('ven.FechaCreacion <=', $fecha_fin);
        
        $query_totales = $this->db->get();
        $totales = $query_totales->row_array();
        
        // Procesar los resultados semanales
        $estadisticas = [];
        foreach ($resultados_semanales as $row) {
            // Obtener el número de semana directamente de la fecha
            $fecha_obj = DateTime::createFromFormat('d/m/Y', $row['fecha_inicio_semana']);
            $numero_semana = $fecha_obj->format('W');
            
            $estadisticas[$row['semana']] = [
                'periodo' => "Semana {$numero_semana} ({$row['fecha_inicio_semana']})",
                'total_adulto_mayor' => (int)$row['total_adulto_mayor'],
                'total_adulto' => (int)$row['total_adulto'],
                'total_infante' => (int)$row['total_infante']
            ];
        }
        
        // Determinar el tipo más común de visitante
        $max_visitante = max(
            $totales['total_adulto_mayor'],
            $totales['total_adulto'],
            $totales['total_infante']
        );
        
        $tipo_mas_comun = 'Adulto';
        if ($max_visitante == $totales['total_adulto_mayor']) {
            $tipo_mas_comun = 'Adulto Mayor';
        } elseif ($max_visitante == $totales['total_infante']) {
            $tipo_mas_comun = 'Infante';
        }
        
        // Agregar estadísticas generales
        $estadisticas['estadisticas_generales'] = [
            'tipo_visitante_mas_comun' => $tipo_mas_comun,
            'total_adulto_mayor' => (int)$totales['total_adulto_mayor'],
            'total_adulto' => (int)$totales['total_adulto'],
            'total_infante' => (int)$totales['total_infante'],
            'periodo_inicio' => $fecha_inicio,
            'periodo_fin' => $fecha_fin
        ];
        
        return $estadisticas;
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