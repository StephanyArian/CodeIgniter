<?php
class Precios_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_precios_activos() {
        $this->db->where('estado', 1);
        $query = $this->db->get('precios');
        return $query->result_array();
    }

    public function get_precio($id) {
        $query = $this->db->get_where('precios', array('id' => $id, 'estado' => 1));
        return $query->row_array();
    }

   /* public function insert_precio() {
        $data = array(
            'tipo' => $this->input->post('tipo'),
            'precio' => $this->input->post('precio'),
            'estado' => 1,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('precios', $data);
    }*/

    public function update_precio($id) {
        $data = array(
            /*'tipo' => $this->input->post('tipo'),*/
            'precio' => $this->input->post('precio'),
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        return $this->db->update('precios', $data);
    }

    public function delete_precio_logico($id) {
        $data = array(
            'estado' => 0,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        return $this->db->update('precios', $data);
    }
}