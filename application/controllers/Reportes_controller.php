<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Horario_model');
        $this->load->model('Ticket_model');
        $this->load->model('Visitante_model');
        $this->load->library('Pdf');
         // Añadir verificación de permisos
         $this->check_admin_permissions();
    }
      // Método privado para verificar permisos de administrador
      private function check_admin_permissions() {
        // Verificar si el usuario está logueado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Debe iniciar sesión para acceder.');
            redirect('auth/index');
        }

        // Verificar si el usuario es administrador
        if ($this->session->userdata('Rol') !== 'admin') {
            $this->session->set_flashdata('error', 'No tiene permisos para acceder a los reportes.');
            redirect('auth/panel');
        }
    }

    public function index() {
        $this->load->view('inc/head');
        $this->load->view('inc/menu');
        $this->load->view('reportes/menu_reportes');
        $this->load->view('inc/footer');
        $this->load->view('inc/pie');
    }

    public function ocupacion_horarios() {
         // Obtener todos los horarios
         $horarios = $this->Horario_model->get_ocupacion_horarios();
        
         // Verificar que tenemos datos para todos los días
         $dias_presentes = array_column($horarios, 'DiaSemana');
         $dias_faltantes = array_diff(range(1, 7), $dias_presentes);
         
         // Agregar horarios vacíos para los días faltantes
         foreach ($dias_faltantes as $dia) {
             $horarios[] = array(
                 'DiaSemana' => $dia,
                 'HoraEntrada' => 'No disponible',
                 'HoraCierre' => 'No disponible',
                 'MaxVisitantes' => 0,
                 'visitantes_actuales' => 0
             );
         }
         
         // Ordenar los horarios por día de la semana
         usort($horarios, function($a, $b) {
             return $a['DiaSemana'] - $b['DiaSemana'];
         });
        $pdf = new Pdf();
        $pdf->AddPage();
        
        // Título principal
        $pdf->SectionTitle('Reporte de Ocupación de Horarios');
        $pdf->Ln(5);
    
        // Agregar cuadro de información con la fecha actual
        $pdf->InfoBox(
            'Informacion del Reporte',
            'Fecha de generacion: ' . date('d/m/Y') . "\n" .
            'Hora de generacion: ' . date('H:i:s') . "\n" .
            'Total de horarios analizados: ' . count($horarios)
        );
        $pdf->Ln(5);
    
        // Definir encabezados de tabla con sus anchos
        $headers = array(
            array('width' => 30, 'text' => 'Dia'),
            array('width' => 45, 'text' => 'Horario'),
            array('width' => 30, 'text' => 'Capacidad'),
            array('width' => 30, 'text' => 'Ocupados'),
            array('width' => 30, 'text' => 'Disponibles'),
            array('width' => 25, 'text' => '% Ocup.')
        );
        
        // Crear encabezados de tabla con estilo
        $pdf->TableHeader($headers);
        
        // Color y fuente para los datos
        $pdf->SetFont('Arial', '', 9);
        $total_capacidad = 0;
        $total_ocupados = 0;

         // Array para mapear números a nombres de días
         $dias_semana = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabado',
            7 => 'Domingo'
        ];
    
        // Datos
        foreach($horarios as $horario) {
            $disponibles = $horario['MaxVisitantes'] - $horario['visitantes_actuales'];
            $porcentaje_ocupacion = $horario['MaxVisitantes'] > 0 ? 
                ($horario['visitantes_actuales'] / $horario['MaxVisitantes']) * 100 : 0;
            
            // Alternar colores de fondo para las filas
            $pdf->SetFillColor(245, 247, 250);
            
            $pdf->TableCell(30, $dias_semana[$horario['DiaSemana']], 'L');
            $pdf->TableCell(45, $horario['HoraEntrada'] . ' - ' . $horario['HoraCierre'], 'L');
            $pdf->TableCell(30, $horario['MaxVisitantes'], 'C');
            $pdf->TableCell(30, $horario['visitantes_actuales'], 'C');
            $pdf->TableCell(30, $disponibles, 'C');
            $pdf->TableCell(25, number_format($porcentaje_ocupacion, 1) . '%', 'C');
            $pdf->Ln();
            
            $total_capacidad += $horario['MaxVisitantes'];
            $total_ocupados += $horario['visitantes_actuales'];
        }
        
        // Calcular totales
        $total_disponibles = $total_capacidad - $total_ocupados;
        $porcentaje_total = ($total_ocupados / $total_capacidad) * 100;
        
        // Línea separadora
        $pdf->SetDrawColor(189, 195, 199);
        $pdf->SetLineWidth(0.2);
        $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 180, $pdf->GetY());
        $pdf->Ln(1);
        
        // Totales con estilo
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(236, 240, 241);
        
        $pdf->TableCell(75, 'TOTALES', 'L', 1);
        $pdf->TableCell(30, $total_capacidad, 'C', 1);
        $pdf->TableCell(30, $total_ocupados, 'C', 1);
        $pdf->TableCell(30, $total_disponibles, 'C', 1);
        $pdf->TableCell(25, number_format($porcentaje_total, 1) . '%', 'C', 1);
        
        // Agregar resumen al final
        $pdf->Ln(10);
        $pdf->InfoBox(
            'Resumen de Ocupacion',
            "Capacidad total del dia: $total_capacidad visitantes\n" .
            "Total de espacios ocupados: $total_ocupados visitantes\n" .
            "Total de espacios disponibles: $total_disponibles visitantes\n" .
            "Porcentaje de ocupacion general: " . number_format($porcentaje_total, 1) . "%"
        );
    
        $pdf->Output('reporte_ocupacion_horarios.pdf', 'I');
    }

    /*public function estructura_precios() {
        $precios = $this->Precios_model->get_precios_activos();
        
        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Estructura de Precios',0,1,'C');
        $pdf->Ln(10);

        $header = array('Tipo', 'Precio', 'Ultima Actualizacion', 'Estado');
        
        // Colors, line width and bold font
        $pdf->SetFillColor(173,216,230); // Light blue
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(100,149,237); // Cornflower blue
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(40, 30, 60, 30);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(240,248,255); // Alice blue
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        foreach($precios as $precio) {
            $pdf->Cell($w[0],6,$precio['tipo'],'LR',0,'L',$fill);
            $pdf->Cell($w[1],6,'Bs. ' . $precio['precio'],'LR',0,'R',$fill);
            $pdf->Cell($w[2],6,$precio['fecha_actualizacion'],'LR',0,'C',$fill);
            $pdf->Cell($w[3],6,$precio['estado'] ? 'Activo' : 'Inactivo','LR',0,'C',$fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Cell(array_sum($w),0,'','T');

        $pdf->Output('reporte_estructura_precios.pdf', 'I');
    }
*/
public function ventas_tickets($fecha_inicio = null, $fecha_fin = null) {
    // Si no se proporcionan fechas, establecer la semana actual
    if (!$fecha_inicio || !$fecha_fin) {
        // Obtener el inicio de la semana (lunes)
        $fecha_inicio = date('Y-m-d', strtotime('monday this week'));
        // Obtener el fin de la semana (domingo)
        $fecha_fin = date('Y-m-d', strtotime('sunday this week'));
    }
    
    // Calcular el número de semana
    $numero_semana = date('W', strtotime($fecha_inicio));
    $ano = date('Y', strtotime($fecha_inicio));
    
    // Cargar el modelo si no está ya cargado
    if (!isset($this->Ticket_model)) {
        $this->load->model('Ticket_model');
    }
    
    $ventas = $this->Ticket_model->get_ventas_resumen($fecha_inicio, $fecha_fin);
    $pdf = new Pdf();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Título principal
    $pdf->SectionTitle('Reporte Semanal de Ventas de Tickets');
    
    // Información del período semanal
    $info_periodo = sprintf(
        "Semana %d del %d\nPeríodo: %s al %s\nDías incluidos: %s",
        $numero_semana,
        $ano,
        date('d/m/Y', strtotime($fecha_inicio)),
        date('d/m/Y', strtotime($fecha_fin)),
        $this->getDiasIncluidos($fecha_inicio, $fecha_fin)
    );
    
    $pdf->InfoBox('Informacion del Periodo', utf8_decode($info_periodo));
    
    $pdf->Ln(5);

    // Definir encabezados de tabla con sus anchos
    $headers = array(
        array('width' => 30, 'text' => 'Dia'),
        array('width' => 25, 'text' => 'Total Tickets'),
        array('width' => 25, 'text' => 'Adulto Mayor'),
        array('width' => 25, 'text' => 'Adulto'),
        array('width' => 25, 'text' => 'Infante'),
        array('width' => 40, 'text' => 'Ingresos Totales')
    );

    // Crear encabezado de tabla
    $pdf->TableHeader($headers);

    // Variables para totales
    $total_ventas = 0;
    $total_tickets = 0;
    $total_adulto_mayor = 0;
    $total_adulto = 0;
    $total_infante = 0;
    
    // Arreglo para almacenar datos diarios
    $ventas_por_dia = array();
    
    // Inicializar array con todos los días de la semana
    $fecha_actual = new DateTime($fecha_inicio);
    $fecha_final = new DateTime($fecha_fin);
    
    while ($fecha_actual <= $fecha_final) {
        $fecha_key = $fecha_actual->format('Y-m-d');
        $ventas_por_dia[$fecha_key] = array(
            'total_tickets' => 0,
            'total_adulto_mayor' => 0,
            'total_adulto' => 0,
            'total_infante' => 0,
            'ingresos_totales' => 0
        );
        $fecha_actual->modify('+1 day');
    }
    
    // Llenar con datos reales
    foreach ($ventas as $venta) {
        $fecha = $venta['fecha'];
        // Asegurarse de que los valores numéricos sean enteros para los conteos
        $venta['total_tickets'] = (int)$venta['total_tickets'];
        $venta['total_adulto_mayor'] = (int)$venta['total_adulto_mayor'];
        $venta['total_adulto'] = (int)$venta['total_adulto'];
        $venta['total_infante'] = (int)$venta['total_infante'];
        // Asegurarse de que los ingresos sean float
        $venta['ingresos_totales'] = (float)$venta['ingresos_totales'];
        $ventas_por_dia[$fecha] = $venta;
    }

    // Mostrar datos por día
    $fill = false;
    foreach ($ventas_por_dia as $fecha => $venta) {
        $pdf->SetFillColor(245, 247, 250);
        
        // Formatear el nombre del día
        $nombre_dia = $this->getDiaSemana($fecha);
        
        // Usar el método TableCell para cada celda
        $pdf->TableCell(30, $nombre_dia . ' ' . date('d/m', strtotime($fecha)), 'L', 1);
        $pdf->TableCell(25, $venta['total_tickets'], 'R', 1);
        $pdf->TableCell(25, $venta['total_adulto_mayor'], 'R', 1);
        $pdf->TableCell(25, $venta['total_adulto'], 'R', 1);
        $pdf->TableCell(25, $venta['total_infante'], 'R', 1);
        $pdf->TableCell(40, 'Bs. ' . number_format($venta['ingresos_totales'], 2), 'R', 1);
        
        $pdf->Ln();
        $fill = !$fill;

        // Actualizar totales
        $total_ventas += $venta['ingresos_totales'];
        $total_tickets += $venta['total_tickets'];
        $total_adulto_mayor += $venta['total_adulto_mayor'];
        $total_adulto += $venta['total_adulto'];
        $total_infante += $venta['total_infante'];
    }

    // Línea de separación
    $pdf->SetDrawColor(189, 195, 199);
    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 170, $pdf->GetY());
    $pdf->Ln(2);

    // Sección de totales con estilo mejorado
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(236, 240, 241);
    
    // Fila de totales
    $pdf->Cell(30, 8, 'TOTALES:', 1, 0, 'L', true);
    $pdf->Cell(25, 8, $total_tickets, 1, 0, 'R', true);
    $pdf->Cell(25, 8, $total_adulto_mayor, 1, 0, 'R', true);
    $pdf->Cell(25, 8, $total_adulto, 1, 0, 'R', true);
    $pdf->Cell(25, 8, $total_infante, 1, 0, 'R', true);
    $pdf->Cell(40, 8, 'Bs. ' . number_format($total_ventas, 2), 1, 1, 'R', true);

    // Agregar resumen estadístico
    $pdf->Ln(10);
    $pdf->SectionTitle('Análisis Semanal');

    // Calcular promedios diarios (solo para días con ventas)
    $dias_activos = count(array_filter($ventas_por_dia, function($v) { 
        return $v['total_tickets'] > 0; 
    }));
    $promedio_diario = $dias_activos > 0 ? $total_tickets / $dias_activos : 0;
    $promedio_ingresos = $dias_activos > 0 ? $total_ventas / $dias_activos : 0;

    // Crear cuadro de estadísticas
    $estadisticas = "Resumen de la Semana:\n";
    $estadisticas .= "• Total Tickets Vendidos: " . $total_tickets . "\n";
    $estadisticas .= "• Promedio Diario de Tickets: " . number_format($promedio_diario, 1) . "\n";
    $estadisticas .= "• Promedio Diario de Ingresos: Bs. " . number_format($promedio_ingresos, 2) . "\n";
    $estadisticas .= "\nDistribución por Tipo:\n";
    $estadisticas .= "• Adulto Mayor (" . $total_adulto_mayor . "): " . 
        number_format(($total_adulto_mayor / max(1, $total_tickets)) * 100, 1) . "%\n";
    $estadisticas .= "• Adulto (" . $total_adulto . "): " . 
        number_format(($total_adulto / max(1, $total_tickets)) * 100, 1) . "%\n";
    $estadisticas .= "• Infante (" . $total_infante . "): " . 
        number_format(($total_infante / max(1, $total_tickets)) * 100, 1) . "%";

    $pdf->InfoBox('Estadisticas de la Semana', utf8_decode($estadisticas));

    // Generar el PDF
    $filename = 'reporte_semanal_tickets_semana' . $numero_semana . '_' . $ano . '.pdf';
    
    $pdf->Output($filename, 'I');
}

// Función auxiliar para obtener el nombre del día en español
private function getDiaSemana($fecha) {
    $dias = array(
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miercoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sabado',
        'Sunday' => 'Domingo'
    );
    return $dias[date('l', strtotime($fecha))];
}

// Función auxiliar para obtener la lista de días incluidos
private function getDiasIncluidos($fecha_inicio, $fecha_fin) {
    $dias = [];
    $fecha_actual = new DateTime($fecha_inicio);
    $fecha_final = new DateTime($fecha_fin);

    while ($fecha_actual <= $fecha_final) {
        $dias[] = $this->getDiaSemana($fecha_actual->format('Y-m-d'));
        $fecha_actual->modify('+1 day');
    }

    return implode(', ', $dias);
}

public function estadisticas_visitantes($periodo = 'semanal', $fecha_inicio = null, $fecha_fin = null) {
    // Si no se proporcionan fechas, usar el mes actual
    if (!$fecha_inicio) {
        $fecha_inicio = date('Y-m-d', strtotime('first day of this month'));
    }
    if (!$fecha_fin) {
        $fecha_fin = date('Y-m-d', strtotime('last day of this month'));
    }
    
    // Obtener datos
    $estadisticas = $this->Visitante_model->get_estadisticas_visitantes($periodo, $fecha_inicio, $fecha_fin);
    
    // Inicializar PDF
    $pdf = new Pdf();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    // Título principal
    $pdf->SectionTitle('Estadísticas de Visitantes - Reporte Semanal');
    
    // Información del reporte
    $info_reporte = sprintf(
        "Fecha de generación: %s\n" .
        "Hora de generación: %s\n" .
        "Período analizado: %s al %s\n" .
        "Total de semanas: %d",
        date('d/m/Y'),
        date('H:i:s'),
        date('d/m/Y', strtotime($fecha_inicio)),
        date('d/m/Y', strtotime($fecha_fin)),
        count($estadisticas) - 1 // -1 por estadísticas_generales
    );
    $pdf->InfoBox('Informacion del Reporte', utf8_decode($info_reporte));
    $pdf->Ln(5);
    
    // Resumen general
    if(isset($estadisticas['estadisticas_generales'])) {
        $gen = $estadisticas['estadisticas_generales'];
        $total_general = $gen['total_adulto_mayor'] + $gen['total_adulto'] + $gen['total_infante'];
        
        $info_general = sprintf(
            "Total de visitantes en el período: %s\n" .
            "Tipo de visitante más común: %s\n\n" .
            "Distribución por tipo:\n" .
            "• Adulto Mayor: %s (%s%%)\n" .
            "• Adulto: %s (%s%%)\n" .
            "• Infante: %s (%s%%)",
            number_format($total_general),
            $gen['tipo_visitante_mas_comun'],
            number_format($gen['total_adulto_mayor']),
            number_format(($gen['total_adulto_mayor'] / max(1, $total_general)) * 100, 1),
            number_format($gen['total_adulto']),
            number_format(($gen['total_adulto'] / max(1, $total_general)) * 100, 1),
            number_format($gen['total_infante']),
            number_format(($gen['total_infante'] / max(1, $total_general)) * 100, 1)
        );
        $pdf->InfoBox('Resumen General', utf8_decode($info_general));
        $pdf->Ln(5);
    }
    
    // Detalle semanal
    $pdf->SectionTitle('Detalle por Semana');
    $pdf->Ln(2);
    
    // Definir encabezados de tabla
    $headers = array(
        array('width' => 50, 'text' => 'Semana'),
        array('width' => 35, 'text' => 'Total'),
        array('width' => 35, 'text' => 'Adulto Mayor'),
        array('width' => 35, 'text' => 'Adulto'),
        array('width' => 35, 'text' => 'Infante')
    );
    
    // Crear encabezados de tabla
    $pdf->TableHeader($headers);
    
    // Variables para totales
    $total_visitantes = 0;
    $total_adulto_mayor = 0;
    $total_adulto = 0;
    $total_infante = 0;
    
    // Imprimir datos
    foreach($estadisticas as $key => $estadistica) {
        if($key === 'estadisticas_generales') continue;
        
        // Calcular total por fila
        $total_fila = $estadistica['total_adulto_mayor'] + 
                      $estadistica['total_adulto'] + 
                      $estadistica['total_infante'];
        
        // Alternar colores de fondo
        $pdf->SetFillColor(245, 247, 250);
        
        // Imprimir fila
        $pdf->TableCell(50, $estadistica['periodo'], 'L');
        $pdf->TableCell(35, number_format($total_fila), 'R');
        $pdf->TableCell(35, number_format($estadistica['total_adulto_mayor']), 'R');
        $pdf->TableCell(35, number_format($estadistica['total_adulto']), 'R');
        $pdf->TableCell(35, number_format($estadistica['total_infante']), 'R');
        $pdf->Ln();
        
        // Actualizar totales
        $total_visitantes += $total_fila;
        $total_adulto_mayor += $estadistica['total_adulto_mayor'];
        $total_adulto += $estadistica['total_adulto'];
        $total_infante += $estadistica['total_infante'];
    }
    
    // Línea separadora antes de totales
    $pdf->SetDrawColor(189, 195, 199);
    $pdf->SetLineWidth(0.2);
    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY());
    $pdf->Ln(1);
    
    // Fila de totales
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(236, 240, 241);
    
    $pdf->TableCell(50, 'TOTALES', 'L', 1);
    $pdf->TableCell(35, number_format($total_visitantes), 'R', 1);
    $pdf->TableCell(35, number_format($total_adulto_mayor), 'R', 1);
    $pdf->TableCell(35, number_format($total_adulto), 'R', 1);
    $pdf->TableCell(35, number_format($total_infante), 'R', 1);
    
    // Agregar análisis estadístico
    $pdf->Ln(10);
    
    // Cálculo de promedios semanales
    $num_semanas = count($estadisticas) - 1; // -1 por estadísticas_generales
    $promedio_semanal = $total_visitantes / max(1, $num_semanas);
    
    $analisis = sprintf(
        "Análisis del Período:\n" .
        "• Total de visitantes: %s\n" .
        "• Promedio de visitantes por semana: %s\n" .
        "• Distribución por tipo de visitante:\n" .
        "  - Adulto Mayor: %s%% (%s visitantes)\n" .
        "  - Adulto: %s%% (%s visitantes)\n" .
        "  - Infante: %s%% (%s visitantes)\n\n" .
        "Período analizado: %s al %s",
        number_format($total_visitantes),
        number_format($promedio_semanal, 1),
        number_format(($total_adulto_mayor / max(1, $total_visitantes)) * 100, 1),
        number_format($total_adulto_mayor),
        number_format(($total_adulto / max(1, $total_visitantes)) * 100, 1),
        number_format($total_adulto),
        number_format(($total_infante / max(1, $total_visitantes)) * 100, 1),
        number_format($total_infante),
        date('d/m/Y', strtotime($fecha_inicio)),
        date('d/m/Y', strtotime($fecha_fin))
    );
    
    $pdf->InfoBox('Analisis Estadistico', utf8_decode($analisis));
    
    // Generar el PDF
    $pdf->Output('estadisticas_visitantes_semanal.pdf', 'I');
}
}