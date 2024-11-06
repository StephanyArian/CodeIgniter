<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf extends FPDF {	
    // Definición de la propiedad HREF
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

    public function Header(){
        // Guardar el estado actual
        $this->_out('q');
            // Dibujar un rectángulo decorativo en la parte superior
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Rect(0, 0, $this->GetPageWidth(), 1, 'F');

        $rutaimg = FCPATH . "uploads/agroflori.jpg";
        
        // Verificar si la imagen existe
        if (file_exists($rutaimg)) {
            // Efecto de sombra
            $this->SetAlpha(0.1);
            $this->Image($rutaimg, 17, 12, 15, 15);
            
            // Imagen principal
            $this->SetAlpha(1);
            $this->Image($rutaimg, 15, 10, 15, 15);
        }
         // Información del encabezado
         $this->SetFont('Arial', 'B', 16);
         $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
         $this->Cell(30); // Espacio para el logo
         $this->Cell(120, 10, 'Agroflori Parque de las Aves', 0, 0, 'C');
         
         // Línea decorativa debajo del título
         $this->Ln(12);
         $this->SetDrawColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
         $this->SetLineWidth(0.3);
         $this->Line($this->GetX() + 30, $this->GetY(), $this->GetPageWidth() - 30, $this->GetY());
         
         // Fecha y hora
         $this->SetFont('Arial', 'I', 8);
         $this->SetTextColor(128, 128, 128);
         $this->SetXY($this->GetPageWidth() - 50, 10);
         $this->Cell(35, 5, date('d/m/Y H:i'), 0, 0, 'R');
         
         $this->Ln(20);
        
        // Restaurar el estado
        $this->_out('Q');
        
       
    }

    public function Footer(){
        // Rectángulo decorativo en la parte inferior
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, $this->GetPageHeight() - 1, $this->GetPageWidth(), 1, 'F');
        
        // Información del pie de página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        
        // Información de la empresa a la izquierda
        $this->Cell(0, 5, 'Agroflori - Parque de las Aves', 0, 0, 'L');
        
        // Número de página a la derecha
        $this->SetX($this->GetX() - 30);
        $this->Cell(30, 5, 'Pg ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
    
    
 public function TableHeader($headers) {
        // Configurar estilo para encabezados
        $this->SetFillColor($this->tableHeaderColor[0], $this->tableHeaderColor[1], $this->tableHeaderColor[2]);
        $this->SetTextColor(44, 62, 80);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.2);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);

        foreach($headers as $header) {
            $this->Cell($header['width'], 8, $header['text'], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Restaurar colores para el contenido
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', 9);
    }

    // Método para crear una celda de tabla con estilo
    public function TableCell($width, $text, $align = 'L', $border = 1) {
        $this->Cell($width, 7, $text, $border, 0, $align);
    }

    // Método para crear títulos de sección
    public function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->Cell(0, 10, utf8_decode($title), 0, 1, 'L');
        $this->SetDrawColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->SetLineWidth(0.2);
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 180, $this->GetY());
        $this->Ln(5);
        $this->SetTextColor(0);
    }

    // Método para agregar un cuadro de información
    public function InfoBox($title, $content) {
        $this->SetFillColor(245, 247, 250);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(0.2);
        $startY = $this->GetY();
        
        // Título del cuadro
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 8, $title, 1, 1, 'L', true);
        
        // Contenido
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 6, $content, 'LR', 'L');
        
        // Línea inferior
        $this->Cell(0, 0, '', 'T');
        $this->Ln(5);
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
