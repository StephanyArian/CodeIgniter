<?php
class Horario_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    
    public function verificar_disponibilidad($idHorarios) {
        $this->db->select('h.MaxVisitantes, COUNT(dv.idTickets) as tickets_vendidos');
        $this->db->from('horarios h');
        $this->db->join('detalleventa dv', 'dv.idHorarios = h.idHorarios AND dv.estado = "Valido"', 'left');
        $this->db->where('h.idHorarios', $idHorarios);
        $this->db->group_by('h.idHorarios');
        $query = $this->db->get();
    
        if ($query->num_rows() == 0) {
            return false;
        }
    
        $result = $query->row();
        $disponibles = $result->MaxVisitantes - ($result->tickets_vendidos ?? 0);
        return $disponibles; // Agregado el return
    }
    
    
    
    public function insert_horario($data) {
        $data['fecha_actualizacion'] = date('Y-m-d H:i:s'); // Añadir fecha de actualización
        return $this->db->insert('horarios', $data);
    }

    public function get_all_horarios() {
        $this->db->select('*');  // Asegurarse de que selecciona todos los campos
        $this->db->order_by('DiaSemana', 'ASC');
        $this->db->order_by('HoraEntrada', 'ASC');
        return $this->db->get('horarios')->result_array();
    }
    
public function get_horarios_disponibles() {
    $dia_actual = date('N');
    
    $this->db->select('h.*,
        h.MaxVisitantes - COALESCE(COUNT(DISTINCT CASE WHEN dv.Estado = "Comprado" THEN dv.idDetalleVenta END), 0) as tickets_disponibles', FALSE);
    $this->db->from('horarios h');
    $this->db->join('venta v', 'v.idHorarios = h.idHorarios AND v.Estado = 1', 'left');
    $this->db->join('detalleventa dv', 'dv.idVenta = v.idVenta AND dv.Estado = "Comprado"', 'left');
    
    $this->db->where('h.Estado', 1);
    $this->db->where('h.DiaSemana', $dia_actual); // Solo el día actual
    
    $this->db->group_by([
        'h.idHorarios',
        'h.DiaSemana',
        'h.HoraEntrada', 
        'h.HoraCierre',
        'h.MaxVisitantes',
        'h.Estado',
        'h.fecha_actualizacion',
        'h.IdUsuarioAuditoria'
    ]);
    
    $this->db->having('tickets_disponibles > 0');
    $this->db->order_by('h.HoraEntrada', 'ASC');
    
    return $this->db->get()->result_array();
}
    public function get_horario($id) {
        return $this->db->get_where('horarios', array('idHorarios' => $id))->row_array();
    }

    
    public function get_ocupacion_horarios($fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio) $fecha_inicio = date('Y-m-d');
        if (!$fecha_fin) $fecha_fin = date('Y-m-d');
        
        $this->db->select('h.idHorarios,
                          TIME_FORMAT(h.HoraEntrada, "%H:%i") as HoraInicio,
                          TIME_FORMAT(h.HoraCierre, "%H:%i") as HoraFin,
                          h.MaxVisitantes as CapacidadTotal,
                          COALESCE(COUNT(dt.idDetalleVenta), 0) as Ocupacion');
        $this->db->from('horarios h');
        
        // Join con venta y detalleventa para obtener la cantidad real de tickets
        $join_condition_venta = 'h.idHorarios = v.idHorarios 
                                AND v.Estado = 1 
                                AND DATE(v.FechaCreacion) >= ' . $this->db->escape($fecha_inicio) . 
                                ' AND DATE(v.FechaCreacion) <= ' . $this->db->escape($fecha_fin);
        
        $this->db->join('venta v', $join_condition_venta, 'left');
        $this->db->join('detalleventa dt', 'v.idVenta = dt.idVenta AND dt.Estado = "Comprado"', 'left');
        
        $this->db->where('h.Estado', 1);
        $this->db->group_by(['h.idHorarios', 'h.HoraEntrada', 'h.HoraCierre', 'h.MaxVisitantes']);
        $this->db->order_by('h.HoraEntrada', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function update_horario($id, $data) {
        $data['fecha_actualizacion'] = date('Y-m-d H:i:s'); // Añadir fecha de actualización
        $this->db->where('idHorarios', $id);
        return $this->db->update('horarios', $data);
    }
    
    public function delete_horario($id) {
        $this->db->where('idHorarios', $id);
        return $this->db->delete('horarios');
    }

    public function actualizar_estado($id, $estado) {
        $data = [
            'Estado' => $estado,
            'fecha_actualizacion' => date('Y-m-d H:i:s')  // Añadir fecha de actualización
        ];
        return $this->db->where('idHorarios', $id)
                        ->update('horarios', $data);
    }

    
}

?>