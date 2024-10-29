<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->helper('url');
    
     }
    public function index() {
        $data = array();
        
        // Obtener rango de fechas si se proporciona
        $start_date = $this->input->get('start_date') ?: date('Y-m-d', strtotime('-30 days'));
        $end_date = $this->input->get('end_date') ?: date('Y-m-d');
        
        // Obtener estadísticas
        $data['stats'] = $this->Dashboard_model->get_stats($start_date, $end_date);
        
        // Obtener mejor vendedor y última venta
        $data['top_seller'] = $this->Dashboard_model->get_top_seller($start_date, $end_date);
        $data['last_sale'] = $this->Dashboard_model->get_last_sale();
        
        // Obtener tendencia de ventas y horas más ocupadas
        $data['sales_trend'] = $this->Dashboard_model->get_sales_trend($start_date, $end_date);
        $data['busiest_hours'] = $this->Dashboard_model->get_busiest_hours($start_date, $end_date);

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('dashboard', $data);
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function refresh_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        
        $data = array(
            'stats' => $this->Dashboard_model->get_stats($start_date, $end_date),
            'top_seller' => $this->Dashboard_model->get_top_seller($start_date, $end_date),
            'last_sale' => $this->Dashboard_model->get_last_sale(),
            'sales_trend' => $this->Dashboard_model->get_sales_trend($start_date, $end_date),
            'busiest_hours' => $this->Dashboard_model->get_busiest_hours($start_date, $end_date)
        );
        
        echo json_encode($data);
    }
 }
?>