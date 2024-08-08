<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function insert_user($data) {
        return $this->db->insert('Usuarios', $data);
    }

    public function register_user($data) {
        return $this->db->insert('Usuarios', $data);
    }

    public function get_user($NombreUsuario, $Clave) {
        $this->db->where('NombreUsuario', $NombreUsuario);
        $this->db->where('Clave', md5($Clave));
        $query = $this->db->get('Usuarios');
        
        
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
}
