<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf extends FPDF {	
    // Definición de la propiedad HREF
    protected $HREF;
    protected $extgstates = array();

    public function __construct($orientation='P', $unit='mm', $size='A4') {
        parent::__construct($orientation, $unit, $size);
        $this->extgstates = array();
    }

    public function Header(){
        // Guardar el estado actual
        $this->_out('q');

        $rutaimg = FCPATH . "uploads/agroflori.jpg";
        
        // Verificar si la imagen existe
        if (file_exists($rutaimg)) {
            // Aplicar transparencia del 50%
            $this->SetAlpha(0.5);
            
            // Dibujar la imagen
            $this->Image($rutaimg, 15, 10, 15, 15);
            
            // Restaurar la opacidad al 100% para el texto
            $this->SetAlpha(1);
        } else {
            // Si la imagen no existe, imprimir un mensaje de error
            $this->SetFont('Arial', '', 8);
            $this->SetTextColor(255, 0, 0);
            $this->Text(15, 10, 'Image not found: ' . $rutaimg);
        }
        
        // Restaurar el estado
        $this->_out('Q');
        
        $this->SetFont('Arial','B',10);
        $this->Cell(30);
        $this->Cell(120, 10, 'Agroflori parque de las Aves ', 0, 0, 'C');
        $this->Ln(20);
    }

    public function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',7);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

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
        // HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i => $e) {
            if($i % 2 == 0) {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF, $e);
                else
                    $this->Write(5, $e);
            } else {
                // Tag
                if($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    // Extract attributes
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
        // Opening tag
        if($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if($tag == 'A')
            $this->HREF = $attr['HREF'];
        if($tag == 'BR')
            $this->Ln(5);
    }

    protected function CloseTag($tag) {
        // Closing tag
        if($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if($tag == 'A')
            $this->HREF = '';
    }

    protected function SetStyle($tag, $enable) {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
            if($this->$s > 0)
                $style .= $s;
        $this->SetFont('', $style);
    }
}
?>
