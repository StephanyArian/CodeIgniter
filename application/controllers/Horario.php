<?php 
class Horario extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Horario_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['horarios'] = $this->Horario_model->get_all_horarios();
        // Agregamos array para mapear números a nombres de días
        $data['dias_semana'] = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('horario/lista_horarios', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function agregar() {
        $this->load->helper('form');

        $this->form_validation->set_rules('DiaSemana', 'Día de la semana', 'required|integer|greater_than[0]|less_than[8]');
        $this->form_validation->set_rules('HoraEntrada', 'Hora de Entrada', 'required');
        $this->form_validation->set_rules('HoraCierre', 'Hora de Cierre', 'required');
        $this->form_validation->set_rules('MaxVisitantes', 'Capacidad Máxima', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('Estado', 'Estado', 'required|in_list[0,1]');

        if ($this->form_validation->run() === FALSE) {
            $data['dias_semana'] = [
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado',
                7 => 'Domingo'
            ];
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('horario/formulario_horario', $data);
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $data = array(
                'DiaSemana' => $this->input->post('DiaSemana'),
                'HoraEntrada' => $this->input->post('HoraEntrada'),
                'HoraCierre' => $this->input->post('HoraCierre'),
                'MaxVisitantes' => $this->input->post('MaxVisitantes'),
                'Estado' => $this->input->post('Estado'),
                'idUsuarios' => $this->session->userdata('idUsuarios') // Cambiar por $this->session->userdata('id_usuario') cuando esté implementado
            );
            $this->Horario_model->insert_horario($data);
            redirect('horario');
        }
    }

    public function editar($id) {
        $this->load->helper('form');

        $data['horario'] = $this->Horario_model->get_horario($id);
        $data['dias_semana'] = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];

        if (empty($data['horario'])) {
            show_404();
        }

        $this->form_validation->set_rules('DiaSemana', 'Día de la semana', 'required|integer|greater_than[0]|less_than[8]');
        $this->form_validation->set_rules('HoraEntrada', 'Hora de Entrada', 'required');
        $this->form_validation->set_rules('HoraCierre', 'Hora de Cierre', 'required');
        $this->form_validation->set_rules('MaxVisitantes', 'Capacidad Máxima', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('Estado', 'Estado', 'required|in_list[0,1]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('horario/formulario_horario', $data);
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $data = array(
                'DiaSemana' => $this->input->post('DiaSemana'),
                'HoraEntrada' => $this->input->post('HoraEntrada'),
                'HoraCierre' => $this->input->post('HoraCierre'),
                'MaxVisitantes' => $this->input->post('MaxVisitantes'),
                'Estado' => $this->input->post('Estado'),
                'IdUsuarioAuditoria' => $this->session->userdata('idUsuarios')
            );
            $this->Horario_model->update_horario($id, $data);
            redirect('horario');
        }
    }

    public function actualizar_estado() {
        if (!$this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['success' => false, 'message' => 'Invalid request']));
        }
    
        $input = json_decode(file_get_contents('php://input'), true);
        $horarioId = $input['id'] ?? null;
        $estado = $input['estado'] ?? null;
    
        if ($horarioId === null || $estado === null) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['success' => false, 'message' => 'Missing parameters']));
        }
    
        // Update the estado in your database
        $success = $this->Horario_model->actualizar_estado($horarioId, $estado);
    
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => $success]));
    }


}
?>