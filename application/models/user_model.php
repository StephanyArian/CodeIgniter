<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
  

    public function validar($NombreUsuario,$Clave)
	{
		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->where('NombreUsuario',$NombreUsuario);
		$this->db->where('Clave',$Clave);
		return $this->db->get();
	}
    
}
