<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ci_qrcode {
    public function generate($params) {
        // Cargar la librería QR
        require_once APPPATH . 'third_party/phpqrcode/qrlib.php';
        
        try {
            // Asegurarse de que existe el directorio padre
            $dir = dirname($params['savename']);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            
            // Generar el código QR
            QRcode::png(
                $params['data'],
                $params['savename'],
                $params['level'],
                $params['size']
            );
            
            return file_exists($params['savename']);
        } catch (Exception $e) {
            log_message('error', 'Error generando QR: ' . $e->getMessage());
            return false;
        }
    }
}