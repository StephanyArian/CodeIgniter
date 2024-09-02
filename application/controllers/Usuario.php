<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model'); // Cargar el modelo Usuario_model
        $this->load->library('session'); 
        $this->load->library('Email_lib');
        $this->load->library('pdf');
    }

    public function lista_usuarios() {
        $listaUsuarios = $this->Usuario_model->lista_usuarios();
        $data['usuarios'] = $listaUsuarios;

        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('lista_usuarios', $data);
        $this->load->view('inc/footer');
    }

    public function agregar() {
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('formulario_usuario');
        $this->load->view('inc/footer');
    }

    public function agregarbd() {
        $token = bin2hex(random_bytes(32)); // Generar un token seguro
        $data = array(
            'PrimerApellido' => strtoupper($this->input->post('PrimerApellido')),
            'SegundoApellido' => strtoupper($this->input->post('SegundoApellido')),
            'Nombres' => strtoupper($this->input->post('Nombres')),
            'Email' => $this->input->post('Email'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Clave' =>  md5($this->input->post('Clave'), PASSWORD_BCRYPT),
            'Rol' => $this->input->post('Rol'),
            'Estado' => '1',
            'FechaCreacion' => date('Y-m-d H:i:s'),
            'IdUsuarioAuditoria' => 1, // ID del usuario que crea el registro
            //'TokenVerificacion' => $token
        );
      
        $this->Usuario_model->agregar_usuario($data);

        //$this->enviar_correo_verificacion($this->input->post('Email'), $token);

        redirect('usuario/lista_usuarios', 'refresh');
    }
     //generacion de reportes
    public function listapdf()
	{
		
			$lista=$this->Usuario_model->lista_usuarios();
			$lista=$lista->result();

			$this->pdf=new Pdf();
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Lista de usuarios");
			$this->pdf->SetLeftMargin(15);
			$this->pdf->SetRightMargin(15);
			$this->pdf->SetFillColor(210,210,210);
			$this->pdf->SetFont('Arial','B',11);
			$this->pdf->Cell(30);
			$this->pdf->Cell(120,10,'LISTA DE USUARIOS',0,0,'C',1);

			$this->pdf->Ln(10);
			$this->pdf->SetFont('Arial','',9);
			$num=1;
			foreach ($lista as $usuario) {
				$PrimerApellido=$usuario->PrimerApellido;
				$SegundoApellido=$usuario->SegundoApellido;
				$Nombres=$usuario->Nombres;
                $Email=$usuario->Email;
                $NombreUsuario=$usuario->NombreUsuario;
				$this->pdf->Cell(7,5,$num,'TBLR',0,'L',0);
				$this->pdf->Cell(30,5,$PrimerApellido,'TBLR',0,'L',0);
				$this->pdf->Cell(30,5,$SegundoApellido,'TBLR',0,'L',0);
				$this->pdf->Cell(25,5,$Nombres,'TBLR',0,'L',0);
				$this->pdf->Cell(50,5,$Email,'TBLR',0,'L',0);
                $this->pdf->Cell(25,5,$NombreUsuario,'TBLR',0,'L',0);
				$this->pdf->Ln(5);
				$num++;
			}

			$this->pdf->Output("listausuarios.pdf","I");

	}

    //email
    private function enviar_correo_verificacion($email, $token) {
        $this->load->library('email');
    
        $this->email->from('stephanyignacio2000@gmail.com', 'Agroflori');
        $this->email->to($email);
        $this->email->subject('Verificación de correo electrónico');
        $this->email->message('Haz clic en el siguiente enlace para verificar tu correo: ' . base_url('usuario/verificar/' . $token));
    
        $this->email->send();
    }
    

    public function modificar($idUsuarios) {
        $data['infousuario'] = $this->Usuario_model->recuperar_usuario($idUsuarios);
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('form_modificar_usuario', $data);
        $this->load->view('inc/footer');
    }

    public function modificarbd() {
        $idUsuarios = $this->input->post('idUsuarios');
        $data = array(
            'PrimerApellido' => strtoupper($this->input->post('PrimerApellido')),
            'SegundoApellido' => strtoupper($this->input->post('SegundoApellido')),
            'Nombres' => strtoupper($this->input->post('Nombres')),
            'Email' => $this->input->post('Email'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Clave' => md5($this->input->post('Clave'), PASSWORD_BCRYPT),
            'Rol' => $this->input->post('Rol'),
            'Estado' => '1'
        );

        $this->Usuario_model->modificar_usuario($idUsuarios, $data);
        redirect('usuario/lista_usuarios', 'refresh');
    }

    public function eliminarbd($idUsuarios) {
        $this->Usuario_model->eliminar_usuario($idUsuarios);
        redirect('usuario/lista_usuarios', 'refresh');
    }
//subida de fotos
    public function subirfoto(){
		$data['idUsuarios']=$_POST['idUsuarios'];
		$this->load->view('inc/head');
		$this->load->view('inc/menu');
		$this->load->view('subirform',$data);
		$this->load->view('inc/footer');
		
	}
    public function subir() {
		$idUsuarios = $this->input->post('idUsuarios');
		$nombrearchivo = $idUsuarios . ".jpg";
		
		// Ruta donde se guardan los archivos
		$config['upload_path'] = './uploads/estudiantes/';
		// Nombre del archivo
		$config['file_name'] = $nombrearchivo;
		$config['allowed_types'] = 'jpg';
		$config['overwrite'] = true; // Para sobreescribir el archivo si ya existe
		
		// Dirección completa del archivo
		$direccion = $config['upload_path'] . $nombrearchivo;
		
		// Si existe un archivo con el mismo nombre, eliminarlo
		if (file_exists($direccion)) {
			unlink($direccion);
		}
		
		$this->load->library('upload', $config); // Carga de la librería upload
		
		if (!$this->upload->do_upload()) {
			// Si hay un error en la subida del archivo
			$data['error'] = $this->upload->display_errors();
		} else {
			// Si la subida del archivo es exitosa
			$data['foto'] = $nombrearchivo;
			$this->Usuario_model->modificar_usuario($idUsuarios, $data);
		}
		
		redirect('Usuario/lista_usuarios', 'refresh');
	}
    //email

    public function enviar_email() {
        $destinatario = 'stephanyignacio2000@gmail.com';
        $asunto = 'Asunto del Correo';
        $mensaje = 'Este es el contenido del correo.';

        if ($this->email_lib->enviar_correo($destinatario, $asunto, $mensaje)) {
            echo "Correo enviado exitosamente.";
        } else {
            echo "Error al enviar el correo.";
        }
    }

    public function cambiar_contrasena() {
        $this->load->view('inc/head');
        $this->load->view('cambiar_contrasena');
        $this->load->view('inc/footer');
    }

    public function cambiar_contrasena_bd() {
        $usuario_id = $this->session->userdata('usuario_id');
        $nueva_contrasena = password_hash($this->input->post('Clave'), PASSWORD_BCRYPT);

        $data = array(
            'Clave' => $nueva_contrasena
        );

        $this->Usuario_model->modificar_usuario($usuario_id, $data);
        $this->session->set_flashdata('success', 'Contraseña actualizada exitosamente.');
        redirect('inicio');
    }

    public function verificar($token) {
        $usuario = $this->Usuario_model->verificar_usuario($token);
    
        if ($usuario) {
            $data = array(
                'TokenVerificacion' => NULL // Limpiar el token
            );
            $this->Usuario_model->modificar_usuario($usuario->idUsuarios, $data);
            $this->session->set_flashdata('success', 'Cuenta verificada exitosamente. Ahora puede iniciar sesión.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Token de verificación inválido o expirado.');
            redirect('auth/login');
        }
    }
    
    
}
?>
