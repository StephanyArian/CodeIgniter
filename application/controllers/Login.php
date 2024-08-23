<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_model');
    }

    public function index() {
        $data['msg'] = $this->uri->segment(3);

        if ($this->session->userdata('NombreUsuario')) {
            // El usuario ya está logueado
            redirect('inicio', 'refresh');
        } else {
            // Usuario no está logueado
            $this->load->view('inc/header');
            $this->load->view('login', $data);
            $this->load->view('inc/footer');
        }
    }

   
	public function validar()
	{
		$NombreUsuario=$_POST['NombreUsuario'];
		$Clave=password_hash($_POST['Clave']);

		$consulta=$this->user_model->validar($NombreUsuario,$Clave);

		if($consulta->num_rows()>0)
		{
			//tenemos una validacion efectiva
			foreach ($consulta->result() as $row)
			{
				$this->session->set_userdata('idUsuarios',$row->idUsuarios);
				$this->session->set_userdata('Clave',$row->Clave);
				$this->session->set_userdata('Rol',$row->Rol);
				redirect('login/panel','refresh');
			}
		}
		else
		{
			//no hay validacion efectiva y redirigimos a login
			redirect('login/index/2','refresh');
		}
	}

	public function panel()
	{
		if($this->session->userdata('login'))
		{
			if($this->session->userdata('Rol')=='admin')
			{
				//el usr ya esta logueado
				redirect('usuario/index','refresh');
			}
			else
			{
				redirect('usuario/guest','refresh');
			}
		}
		else
		{
			//usuario no esta logueado
			redirect('login/index/3','refresh');
		}
	}
    public function logout() {
        $this->session->sess_destroy();
        redirect('login/index/1', 'refresh');
    }
}
?>
