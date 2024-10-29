class QrCodeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ciqrcode');
    }

    public function index($data = 'https://example.com')
    {
        // Configuración del QR
        $params['data'] = $data;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . 'uploads/qr/example.png';

        // Generar QR
        $this->ciqrcode->generate($params);

        // Cargar la vista
        $this->load->view('qr_code_view', ['qr_code_path' => base_url('uploads/qr/example.png')]);
    }
}