<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf extends FPDF {	
    // Definición de la propiedad HREF
    protected $HREF;

    public function Header(){
        $rutaimg = base_url() . "uploads/parque.jpeg";
        $this->Image($rutaimg, 15, 10, 15, 15);
        $this->SetFont('Arial','B',10);
        $this->Cell(30);
        $this->Cell(120, 10, '', 0, 0, 'C');
        $this->Ln(5);
    }

    public function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',7);
        $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
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
