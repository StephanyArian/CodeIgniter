<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'third_party/phpqrcode/qrlib.php';

class Ci_qrcode {
    public function generate($params) {
        if (!isset($params['data'])) return false;
        
        $params['savename'] = FCPATH . 'assets/img/qr_' . time() . '.png';
        $params['level'] = isset($params['level']) ? $params['level'] : 'H';
        $params['size'] = isset($params['size']) ? $params['size'] : 10;
        
        QRcode::png($params['data'], $params['savename'], $params['level'], $params['size'], 2);

        
        return $params['savename'];
    }
}