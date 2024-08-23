<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function index() {
        // Cargar la vista del dashboard
        $this->load->view('dashboard');
    }
}
