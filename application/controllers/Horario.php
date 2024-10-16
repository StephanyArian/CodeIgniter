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
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('horario/lista_horarios', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function agregar() {
        $this->load->helper('form');

        $this->form_validation->set_rules('Dia', 'Dia', 'required');
        $this->form_validation->set_rules('HoraEntrada', 'Hora de Entrada', 'required');
        $this->form_validation->set_rules('HoraCierre', 'Hora de Cierre', 'required');
        $this->form_validation->set_rules('MaxVisitantes', 'Capacidad Máxima', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('Estado', 'Estado', 'required|in_list[0,1]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('inc/head');
            $this->load->view('inc/menu');
            $this->load->view('horario/formulario_horario');
            $this->load->view('inc/footer');
            $this->load->view('inc/pie');
        } else {
            $data = array(
                'Dia' => $this->input->post('Dia'),
                'HoraEntrada' => $this->input->post('HoraEntrada'),
                'HoraCierre' => $this->input->post('HoraCierre'),
                'MaxVisitantes' => $this->input->post('MaxVisitantes'),
                'Estado' => $this->input->post('Estado'),
                'IdUsuarioAuditoria' => 1, // Cambiar por $this->session->userdata('id_usuario') cuando esté implementado
            );
            $this->Horario_model->insert_horario($data);
            redirect('horario');
        }
    }

    public function editar($id) {
        $this->load->helper('form');

        $data['horario'] = $this->Horario_model->get_horario($id);

        if (empty($data['horario'])) {
            show_404();
        }

        $this->form_validation->set_rules('Dia', 'Dia', 'required');
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
                'Dia' => $this->input->post('Dia'),
                'HoraEntrada' => $this->input->post('HoraEntrada'),
                'HoraCierre' => $this->input->post('HoraCierre'),
                'MaxVisitantes' => $this->input->post('MaxVisitantes'),
                'Estado' => $this->input->post('Estado'),
                'IdUsuarioAuditoria' => 1, // Cambiar por $this->session->userdata('id_usuario') cuando esté implementado
            );
            $this->Horario_model->update_horario($id, $data);
            redirect('horario');
        }
    }

    public function eliminar($id) {
        $result = $this->Horario_model->delete_horario($id);
        if ($result) {
            $this->session->set_flashdata('message', 'Horario eliminado correctamente');
        } else {
            $this->session->set_flashdata('error', 'No se pudo eliminar el horario');
        }
        redirect('horario');
    }
}
?>