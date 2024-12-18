<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model'); // Cargar el modelo Usuario_model
        $this->load->library('session'); 
        $this->load->library('Email_lib');
        $this->load->library('pdf');
        // Añadir verificación de permisos
        $this->check_authentication();
    }

    
    // Método para verificar autenticación básica
    private function check_authentication() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Debe iniciar sesión para acceder.');
            redirect('auth/index');
        }
    }

    // Método para verificar permisos de administrador
    private function check_admin_permissions() {
        if ($this->session->userdata('Rol') !== 'admin') {
            $this->session->set_flashdata('error', 'No tiene permisos para acceder a esta sección.');
            redirect('auth/panel');
        }
    }


    public function lista_usuarios() {
        $this->check_admin_permissions();
        $listaUsuarios = $this->Usuario_model->lista_usuarios();
        $data['usuarios'] = $listaUsuarios;

        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('lista_usuarios', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function agregar() {
        $this->check_admin_permissions();
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('formulario_usuario');
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function agregarbd() {
        $this->check_admin_permissions();
        $token = bin2hex(random_bytes(32)); // Generar un token seguro
        $idUsuarios = $this->session->userdata('idUsuarios');
        if (is_null($idUsuarios)) {
            $this->session->set_flashdata('error', 'Usuario no autenticado.');
            redirect('login');
        }
    
        // Recoger los datos del formulario
        $primerApellido = strtoupper($this->input->post('PrimerApellido'));
        $segundoApellido = strtoupper($this->input->post('SegundoApellido'));
        $nombres = strtoupper($this->input->post('Nombres'));
        $email = $this->input->post('Email');
        $nombreUsuario = $this->input->post('NombreUsuario');
        $clave = $this->input->post('Clave');
        $rol = $this->input->post('Rol');
    
        // Preparar los datos para guardar en la base de datos
        $data = array(
            'PrimerApellido' => $primerApellido,
            'SegundoApellido' => $segundoApellido,
            'Nombres' => $nombres,
            'Email' => $email,
            'NombreUsuario' => $nombreUsuario,
            'Clave' => sha1($clave), // Encriptar la contraseña
            'Rol' => $rol,
            'Estado' => '1',
            'FechaCreacion' => date('Y-m-d H:i:s'),
            'IdUsuarioAuditoria' => $idUsuarios
        );
    
        // Guardar el usuario en la base de datos
        $this->Usuario_model->agregar_usuario($data);
    
        // Configuración para enviar el correo
       /* $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'ignaciostephany127@gmail.com',  
            'smtp_pass' => 'wzel svav ocek qqdf',  
            'smtp_port' => 465,  // Cambiado a 465 para SSL
            'smtp_crypto' => 'ssl',  // Cambio a SSL
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
            'newline' => "\r\n",
            'smtp_timeout' => 20
        );
    
        $this->load->library('email', $config);
        $this->email->initialize($config);
    
        // Preparar el contenido del correo
        $this->email->from('ignaciostephany127@gmail.com', 'Parque de las Aves Agroflori');
        $this->email->to($email);
        $this->email->subject('Credenciales de tu cuenta');
        $this->email->message('Hola ' . $nombres . ',<br><br>Tu cuenta ha sido creada exitosamente.<br><br>'
            . 'Tus credenciales de acceso son:<br>'
            . 'Nombre de usuario: ' . $nombreUsuario . '<br>'
            . 'Contraseña: ' . $clave . '<br><br>'
            . 'Por favor, asegúrate de cambiar tu contraseña después de iniciar sesión.');
    
        // Intentar enviar el correo y loguear errores si ocurre alguno
        if (!$this->email->send()) {
            $error = $this->email->print_debugger(); // Obtener detalles del error
            log_message('error', $error);  // Loguear el error exacto
            $this->session->set_flashdata('error', 'No se pudo enviar el correo de verificación. Detalles: ' . $error);
        } else {
            $this->session->set_flashdata('success', 'Usuario registrado y correo enviado correctamente.');
        }*/
    
        redirect('usuario/lista_usuarios', 'refresh');
    }
    
    
     //generacion de reportes
     public function listapdf() {
        $lista = $this->Usuario_model->lista_usuarios();
        $lista = $lista->result();
    
        $this->pdf = new Pdf();
        $this->pdf->AddPage();
        $this->pdf->AliasNbPages();
        
        // Título de la sección
        $this->pdf->SectionTitle('LISTA DE USUARIOS');
        $this->pdf->Ln(5);
        
        // Información general
        $this->pdf->InfoBox(
            'Informacion del Reporte',
            'Este reporte muestra la lista completa de usuarios registrados en el sistema.'
        );
        
        // Encabezados de tabla
        $headers = array(
            array('width' => 7, 'text' => 'No.'),
            array('width' => 30, 'text' => 'A. PATERNO'),
            array('width' => 30, 'text' => 'A. MATERNO'),
            array('width' => 25, 'text' => 'NOMBRE'),
            array('width' => 50, 'text' => 'EMAIL'),
            array('width' => 25, 'text' => 'USUARIO')
        );
        $this->pdf->TableHeader($headers);
        
        // Contenido de la tabla
        $num = 1;
        foreach ($lista as $usuario) {
            $this->pdf->TableCell(7, $num);
            $this->pdf->TableCell(30, $usuario->PrimerApellido);
            $this->pdf->TableCell(30, $usuario->SegundoApellido);
            $this->pdf->TableCell(25, $usuario->Nombres);
            $this->pdf->TableCell(50, $usuario->Email);
            $this->pdf->TableCell(25, $usuario->NombreUsuario);
            $this->pdf->Ln();
            $num++;
        }
    
        $this->pdf->Output("listausuarios.pdf", "I");
    }


    public function modificar($idUsuarios) {
        $this->check_admin_permissions();
        $data['infousuario'] = $this->Usuario_model->recuperar_usuario($idUsuarios);
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('form_modificar_usuario', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function modificarbd() {
        $this->check_admin_permissions();
        $idUsuarios = $this->input->post('idUsuarios');
        $data = array(
            'PrimerApellido' => strtoupper($this->input->post('PrimerApellido')),
            'SegundoApellido' => strtoupper($this->input->post('SegundoApellido')),
            'Nombres' => strtoupper($this->input->post('Nombres')),
            'Email' => $this->input->post('Email'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Clave' => sha1($this->input->post('Clave')),
            'Rol' => $this->input->post('Rol'),
            'Estado' => '1'
        );

        $this->Usuario_model->modificar_usuario($idUsuarios, $data);
        redirect('usuario/lista_usuarios', 'refresh');
    }

    public function eliminarbd($idUsuarios) {
        $this->check_admin_permissions();
        $this->Usuario_model->eliminar_usuario($idUsuarios);
        redirect('usuario/lista_usuarios', 'refresh');
    }
//subida de fotos
    public function subirfoto(){
        $this->check_admin_permissions();
		$data['idUsuarios']=$_POST['idUsuarios'];
		$this->load->view('inc/head');
		$this->load->view('inc/menu');
		$this->load->view('subirform',$data);
		$this->load->view('inc/footer');
        $this->load->view('inc/pie');
		
	}
    public function subir() {
        $this->check_admin_permissions();
		$idUsuarios = $this->input->post('idUsuarios');
		$nombrearchivo = $idUsuarios . ".jpg";
		
		// Ruta donde se guardan los archivos
		$config['upload_path'] = './uploads/usuarios/';
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
    private function enviar_correo_verificacion($email, $token) {
        $this->load->library('email');
    
        $this->email->from('ignaciostephany127@gmail.com', 'Agroflori');
        $this->email->to($email);
        $this->email->subject('Verificación de correo electrónico');
        $this->email->message('Haz clic en el siguiente enlace para verificar tu correo: ' . base_url('usuario/verificar/' . $token));
    
        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', $this->email->print_debugger()); // Registra los errores
            return false;
        }
    }
    
 

    public function cambiar_contrasena() {
        $this->load->view('inc/head');
        $this->load->view('cambiar_contrasena');
        $this->load->view('inc/footer');
    }

    public function cambiar_contrasena_bd() {
        $usuario_id = $this->session->userdata('usuario_id');
        $nueva_contrasena = password_hash($this->input->post('Clave'));

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
    
    ///Edicion de datos desde el usuario
    public function editar_perfil() {
        // Suponiendo que tienes la sesión activa y el ID del usuario almacenado en la sesión
        $idUsuarios = $this->session->userdata('idUsuarios');
    
        if (!$idUsuarios) {
            $this->session->set_flashdata('error', 'No se pudo identificar el usuario.');
            redirect('auth/panel');
        }
        // Obtener la información del usuario
        $data['usuario'] = $this->Usuario_model->recuperar_usuario($idUsuarios);
    
        // Cargar las vistas
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('profile_edit', $data); // Asegúrate de que 'profile_edit' sea el nombre de tu vista
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }
    
    public function actualizar_perfil() {
        $idUsuarios = $this->input->post('idUsuarios');
    
        // Obtener los datos del formulario
        $data = array(
            'PrimerApellido' => $this->input->post('PrimerApellido'),
            'SegundoApellido' => $this->input->post('SegundoApellido'),
            'Nombres' => $this->input->post('Nombres'),
            'NombreUsuario' => $this->input->post('NombreUsuario'),
            'Email' => $this->input->post('Email'),
        );
    
        // Verificar si se ha proporcionado una nueva contraseña
        $nueva_clave = $this->input->post('Clave');
        if (!empty($nueva_clave)) {
            $data['Clave'] = sha1($nueva_clave);
        }
    
        // Manejo de la foto de perfil
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path'] = './uploads/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2048; // Tamaño máximo en kilobytes
            $config['file_name'] = $idUsuarios . '_' . $_FILES['foto']['name']; // Renombrar el archivo
    
            $this->load->library('upload', $config);
    
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $data['foto'] = $upload_data['file_name'];
            } else {
                // Manejar errores de carga
                $data['error'] = $this->upload->display_errors();
            }
        }
    
        // Actualizar los datos del usuario
        $this->Usuario_model->modificar_usuario($idUsuarios, $data);
    
        // Redirigir a la página de perfil con un mensaje de éxito
        $this->session->set_flashdata('success', 'Perfil actualizado exitosamente.');
        redirect('usuario/editar_perfil');
    }
    

    
}
?>