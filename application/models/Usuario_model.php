<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function lista_usuarios() {
        $this->db->select('*');
        $this->db->from('usuarios');
        $this->db->where('Estado', '1');
        return $this->db->get();
    }
    public function get_usuario_by_nombre($username) {
        $this->db->where('NombreUsuario', $username);
        $query = $this->db->get('usuarios');
        return $query->row();
    }
    public function agregar_usuario($data) {
        $this->db->insert('usuarios', $data);
    }

    public function recuperar_usuario($idUsuarios) {
        $this->db->select('*');
        $this->db->from('usuarios');
        $this->db->where('idUsuarios', $idUsuarios);
        return $this->db->get()->row();
    }

    public function modificar_usuario($idUsuarios, $data) {
        $this->db->where('idUsuarios', $idUsuarios);
        $this->db->update('usuarios', $data);
        
    }

    public function eliminar_usuario($idUsuarios) {

         $data = array(
            'Estado' => '0', 
            'FechaActualizacion' => date('Y-m-d H:i:s')
        );

        $this->db->where('idUsuarios', $idUsuarios);
        return $this->db->update('Usuarios', $data);
    }

    
    ///Cambio para email
    public function verificar_usuario($token) {
        $this->db->where('TokenVerificacion', $token);
        $query = $this->db->get('usuarios');
        return $query->row();
    }
    
    
}
?>
