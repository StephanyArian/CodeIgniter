<?php
class Dashboard_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function get_stats($start_date, $end_date) {
        $this->db->select('SUM(v.Monto) as total_ventas, COUNT(v.idVenta) as num_ventas');
        $this->db->select('SUM(dv.CantAdultoMayor) as CantAdultoMayor, SUM(dv.CantAdulto) as CantAdulto, SUM(dv.CantInfante) as CantInfante');
        $this->db->select('SUM(dv.CantAdultoMayor + dv.CantAdulto + dv.CantInfante) as total_clientes');
        $this->db->from('venta v');
        $this->db->join('detalleventa dv', 'v.idVenta = dv.idVenta');

        $this->db->where('v.FechaCreacion >=', $start_date);
        $this->db->where('v.FechaCreacion <=', $end_date);

        $result = $this->db->get()->row_array();
        
        // Si no hay resultados, devolver un array con valores predeterminados
        if (empty($result)) {
            return [
                'total_ventas' => 0,
                'num_ventas' => 0,
                'CantAdultoMayor' => 0,
                'CantAdulto' => 0,
                'CantInfante' => 0,
                'total_clientes' => 0
            ];
        }

        return $result;
    }

    public function get_top_seller($start_date = null, $end_date = null) {
        $this->db->select('u.NombreUsuario, u.Email, COUNT(v.idVenta) as num_ventas, SUM(v.Monto) as total_ventas');
        $this->db->from('venta v');
        $this->db->join('usuarios u', 'v.idUsuarios = u.idUsuarios');
        
        if ($start_date && $end_date) {
            $this->db->where('v.FechaCreacion >=', $start_date);
            $this->db->where('v.FechaCreacion <=', $end_date);
        }
        
        $this->db->group_by('v.idUsuarios');
        $this->db->order_by('num_ventas', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row_array();
        
        // Si no hay resultados, devolver un array con valores predeterminados
        if (empty($result)) {
            return [
                'NombreUsuario' => 'N/A',
                'Correo' => 'N/A',
                'num_ventas' => 0,
                'total_ventas' => 0
            ];
        }
        
        return $result;
    }

    public function get_last_sale() {
        $this->db->select('v.idVenta, v.Monto, v.FechaCreacion, vi.Nombre, vi.PrimerApellido, vi.SegundoApellido, vi.CiNit');
        $this->db->from('venta v');
        $this->db->join('visitante vi', 'v.idVisitante = vi.idVisitante');
        $this->db->order_by('v.FechaCreacion', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row_array();
        
        // Si no hay resultados, devolver un array con valores predeterminados
        if (empty($result)) {
            return [
                'idVenta' => 'N/A',
                'Monto' => 0,
                'FechaCreacion' => date('Y-m-d H:i:s'),
                'Nombre' => 'N/A',
                'PrimerApellido' => 'N/A',
                'SegundoApellido' => 'N/A',
                'CiNit' => 'N/A'
            ];
        }
        
        return $result;
    }

    public function get_sales_trend($start_date, $end_date) {
        $this->db->select('DATE(v.FechaCreacion) as fecha, SUM(v.Monto) as total_ventas');
        $this->db->from('venta v');
        $this->db->where('v.FechaCreacion >=', $start_date);
        $this->db->where('v.FechaCreacion <=', $end_date);
        $this->db->group_by('DATE(v.FechaCreacion)');
        $this->db->order_by('DATE(v.FechaCreacion)', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_busiest_hours($start_date = null, $end_date = null) {
        $this->db->select('HOUR(v.FechaCreacion) as hora, COUNT(*) as num_ventas');
        $this->db->from('venta v');
        
        if ($start_date && $end_date) {
            $this->db->where('v.FechaCreacion >=', $start_date);
            $this->db->where('v.FechaCreacion <=', $end_date);
        } else {
            $this->db->where('DATE(v.FechaCreacion)', date('Y-m-d'));
        }
        
        $this->db->group_by('HOUR(v.FechaCreacion)');
        $this->db->order_by('num_ventas', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        // Si no hay resultados, devolver un array con valores predeterminados
        if (empty($result)) {
            return array_map(function($hour) {
                return ['hora' => $hour, 'num_ventas' => 0];
            }, range(0, 23));
        }
        
        return $result;
    }
}
?>