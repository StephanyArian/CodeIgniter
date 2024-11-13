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
    
    // En el método get_horarios_disponibles
    public function get_horarios_disponibles() {
        // Obtener el día actual (1-7, donde 1 es Lunes)
        $dia_actual = date('N');
        
        // Construir la consulta
        $this->db->select('h.*, COALESCE(COUNT(DISTINCT dv.idDetalleVenta), 0) as tickets_vendidos', false);
        $this->db->from('horarios h');
        $this->db->join('venta v', 'v.idHorarios = h.idHorarios AND v.Estado = 1', 'left');
        $this->db->join('detalleventa dv', 'dv.idVenta = v.idVenta AND dv.Estado = "ACTIVO"', 'left');
        
        // Filtros básicos
        $this->db->where('h.Estado', 1);
        $this->db->where('h.DiaSemana >=', $dia_actual);
        
        // Agrupamiento
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
        
        // Filtrar solo horarios con capacidad disponible
        $this->db->having('h.MaxVisitantes > tickets_vendidos');
        
        // Ordenamiento
        $this->db->order_by('h.DiaSemana', 'ASC');
        $this->db->order_by('h.HoraEntrada', 'ASC');
        
        return $this->db->get()->result_array();
    }
    public function get_horario($id) {
        return $this->db->get_where('horarios', array('idHorarios' => $id))->row_array();
    }

    public function get_ocupacion_horarios() {
        $dia_actual = date('N'); // Obtiene el día de la semana (1-7)
        
        $this->db->select('h.*, COUNT(dv.idTickets) as visitantes_actuales');
        $this->db->from('horarios h');
        $this->db->join('detalleventa dv', 'dv.idHorarios = h.idHorarios', 'left');
        $this->db->where('h.DiaSemana >=', $dia_actual);
        $this->db->group_by('h.idHorarios');
        $this->db->order_by('h.DiaSemana', 'ASC');
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