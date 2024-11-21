<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf extends FPDF {	
    protected $HREF;
    protected $extgstates = array();
    protected $primaryColor = array(39, 174, 96); // Verde
    protected $secondaryColor = array(41, 128, 185); // Azul
    protected $accentColor = array(142, 68, 173); // Morado
    protected $tableHeaderColor = array(236, 240, 241); // Gris claro
    protected $borderColor = array(189, 195, 199); // Gris

    public function __construct($orientation='P', $unit='mm', $size='A4') {
        parent::__construct($orientation, $unit, $size);
        $this->extgstates = array();
        // Inicializar variables de estilo
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        
        // Establecer márgenes
        $this->SetMargins(15, 25, 15);
        $this->SetAutoPageBreak(true, 25);
    }

    // Método para limpiar y codificar texto
    protected function cleanText($txt) {
        if (!is_string($txt)) {
            return '';
        }

        // Asegurar que el texto está en UTF-8
        if (!mb_check_encoding($txt, 'UTF-8')) {
            $txt = mb_convert_encoding($txt, 'UTF-8', mb_detect_encoding($txt));
        }

        // Mapa extendido de caracteres especiales
        $special_chars = array(
            // Vocales acentuadas minúsculas
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',

            // Vocales acentuadas mayúsculas
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',

            // Otros caracteres especiales
            'ñ' => 'n', 'Ñ' => 'N',
            'ç' => 'c', 'Ç' => 'C',
            'ý' => 'y', 'ÿ' => 'y', 'Ý' => 'Y',
            'ø' => 'o', 'Ø' => 'O',
            'œ' => 'oe', 'Œ' => 'OE',
            'æ' => 'ae', 'Æ' => 'AE',
            'ß' => 'ss',

            // Símbolos y puntuación
            '°' => 'o', 'º' => 'o', 'ª' => 'a',
            '€' => 'EUR', '£' => 'GBP', '¥' => 'JPY',
            '¿' => '?', '¡' => '!',
            '–' => '-', '—' => '-',
            '"' => '"', '"' => '"',
            '≤' => '<=', '≥' => '>=',
            '×' => 'x', '÷' => '/',
            '©' => '(c)', '®' => '(r)', '™' => '(tm)',
            '¼' => '1/4', '½' => '1/2', '¾' => '3/4',
            '∞' => 'inf', '≠' => '!=',
            '±' => '+/-', '∑' => 'sum',
            '…' => '...',

            // Espacios y caracteres de control
            "\xC2\xA0" => ' ',    // Non-breaking space
            "\r" => '',           // Retorno de carro
            "\n" => ' ',          // Nueva línea
            "\t" => ' ',          // Tabulación
            "\0" => '',           // Null byte
            "\x0B" => '',         // Tabulación vertical
        );

        try {
            // Primero convertir entidades HTML
            $txt = html_entity_decode($txt, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            
            // Luego reemplazar caracteres especiales
            $txt = strtr($txt, $special_chars);
            
            // Eliminar caracteres no imprimibles y control
            $txt = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $txt);
            
            // Convertir múltiples espacios en uno solo
            $txt = preg_replace('/\s+/', ' ', $txt);
            
            // Trim espacios al inicio y final
            $txt = trim($txt);
            
            // Asegurar que el resultado es válido para FPDF (ISO-8859-1)
            return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $txt);
            
        } catch (Exception $e) {
            // En caso de error, intentar limpiar el texto de forma básica
            return preg_replace('/[^a-zA-Z0-9\s\-\_\.\,\;\:\!\?\(\)]/u', '', $txt);
        }
    }

    // Método auxiliar para debugging
    protected function debugText($txt) {
        // Crear un log con la información de codificación
        $debug_info = array(
            'original' => $txt,
            'encoding' => mb_detect_encoding($txt),
            'cleaned' => $this->cleanText($txt),
            'length_before' => strlen($txt),
            'length_after' => strlen($this->cleanText($txt)),
            'contains_utf8' => mb_check_encoding($txt, 'UTF-8'),
        );
        
        // Guardar en un archivo de log (ajusta la ruta según tu necesidad)
        error_log(print_r($debug_info, true), 3, APPPATH . 'logs/pdf_debug.log');
        
        return $debug_info;
    }

    // Sobrescribir Cell para manejar caracteres especiales
    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w, $h, $this->cleanText($txt), $border, $ln, $align, $fill, $link);
    }

    // Sobrescribir MultiCell para manejar caracteres especiales
    public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false) {
        parent::MultiCell($w, $h, $this->cleanText($txt), $border, $align, $fill);
    }

    public function Header(){
        $this->_out('q');
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, 0, $this->GetPageWidth(), 1, 'F');

        $rutaimg = FCPATH . "uploads/agroflori.jpg";
        
        if (file_exists($rutaimg)) {
            $this->SetAlpha(0.1);
            $this->Image($rutaimg, 17, 12, 15, 15);
            
            $this->SetAlpha(1);
            $this->Image($rutaimg, 15, 10, 15, 15);
        }

        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(30);
        $this->Cell(120, 10, $this->cleanText('Agroflori Parque de las Aves'), 0, 0, 'C');
        
        $this->Ln(12);
        $this->SetDrawColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->SetLineWidth(0.3);
        $this->Line($this->GetX() + 30, $this->GetY(), $this->GetPageWidth() - 30, $this->GetY());
        
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->SetXY($this->GetPageWidth() - 50, 10);
        $this->Cell(35, 5, date('d/m/Y H:i'), 0, 0, 'R');
        
        $this->Ln(20);
        $this->_out('Q');
    }

    public function Footer(){
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, $this->GetPageHeight() - 1, $this->GetPageWidth(), 1, 'F');
        
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        
        $this->Cell(0, 5, $this->cleanText('Agroflori - Parque de las Aves'), 0, 0, 'L');
        
        $this->SetX($this->GetX() - 30);
        $this->Cell(30, 5, 'Pg ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

    public function TableHeader($headers) {
        $this->SetFillColor($this->tableHeaderColor[0], $this->tableHeaderColor[1], $this->tableHeaderColor[2]);
        $this->SetTextColor(44, 62, 80);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.2);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);

        foreach($headers as $header) {
            $this->Cell($header['width'], 8, $this->cleanText($header['text']), 1, 0, 'C', true);
        }
        $this->Ln();
        
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', 9);
    }

    public function TableCell($width, $text, $align = 'L', $border = 1) {
        $this->Cell($width, 7, $this->cleanText($text), $border, 0, $align);
    }

    public function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->Cell(0, 10, $this->cleanText($title), 0, 1, 'L');
        $this->SetDrawColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->SetLineWidth(0.2);
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 180, $this->GetY());
        $this->Ln(5);
        $this->SetTextColor(0);
    }

    public function InfoBox($title, $content) {
        $this->SetFillColor(245, 247, 250);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(0.2);
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 8, $this->cleanText($title), 1, 1, 'L', true);
        
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 6, $this->cleanText($content), 'LR', 'L');
        
        $this->Cell(0, 0, '', 'T');
        $this->Ln(5);
    }

    // Los métodos relacionados con transparencia y estados gráficos permanecen igual
    public function SetAlpha($alpha, $bm='Normal') {
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    protected function AddExtGState($parms) {
        $n = count($this->extgstates) + 1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    protected function SetExtGState($gs) {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    protected function _enddoc() {
        if (!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    protected function _putextgstates() {
        for ($i = 1; $i <= count($this->extgstates); $i++) {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_put(sprintf('/ca %.3F', $parms['ca']));
            $this->_put(sprintf('/CA %.3F', $parms['CA']));
            $this->_put('/BM '.$parms['BM']);
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    protected function _putresourcedict() {
        parent::_putresourcedict();
        $this->_put('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_put('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_put('>>');
    }

    protected function _putresources() {
        $this->_putextgstates();
        parent::_putresources();
    }

    public function WriteHTML($html) {
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i => $e) {
            if($i % 2 == 0) {
                if($this->HREF)
                    $this->PutLink($this->HREF, $this->cleanText($e));
                else
                    $this->Write(5, $this->cleanText($e));
            } else {
                if($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v) {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    protected function OpenTag($tag, $attr) {
        if($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if($tag == 'A')
            $this->HREF = $attr['HREF'];
        if($tag == 'BR')
            $this->Ln(5);
    }

    protected function CloseTag($tag) {
        if($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if($tag == 'A')
            $this->HREF = '';
    }

    protected function SetStyle($tag, $enable) {
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
            if($this->$s > 0)
                $style .= $s;
        $this->SetFont('', $style);
    }
}
?>
