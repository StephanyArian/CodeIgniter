<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
  

	public function validar($NombreUsuario, $Clave)
	{
		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->where('NombreUsuario', $NombreUsuario);
		$this->db->where('Clave', sha1($Clave)); // Cambiado de md5 a sha1
		$consulta = $this->db->get();
		$this->db->last_query();
	
		if ($consulta === FALSE) {
			// Muestra el error de la base de datos
			$db_error = $this->db->error();
			echo 'Error en la consulta: ' . $db_error['message'];
			return false;
		}
	
		return $consulta; 
	}
	
}
?>