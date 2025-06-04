<?php
ob_start();

require_once(__DIR__ . '/../../js/TCPDF/tcpdf.php'); // Ajusta la ruta según tu estructura

$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$tabla_afectada = isset($_POST['tabla_afectada']) ? $_POST['tabla_afectada'] : ''; // filtros 

// Conexión a la base de datos
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

// Construir consulta dinámica con filtros
$query = "SELECT id_notificacion, fecha, tipo_evento, id_afectado, descripcion, documento_generador, tabla_afectada FROM notificaciones WHERE fecha BETWEEN :inicio AND :fin";
$params = [
    ':inicio' => $fecha_inicio . ' 00:00:00',
    ':fin' => $fecha_fin . ' 23:59:59'
];

if (!empty($tabla_afectada)) {
    $query .= " AND tabla_afectada = :tabla_afectada";
    $params[':tabla_afectada'] = $tabla_afectada;
}

$stmt = $con->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
class CustomPDF extends TCPDF {
    public function Header() {
        $originalMargins = [
            'l' => $this->lMargin,
            'r' => $this->rMargin,
            't' => $this->tMargin,
            'b' => $this->bMargin
        ];
        
        // Establecer márgenes a cero solo para la imagen
        $this->SetMargins(0, 0, 0);
        $this->SetAutoPageBreak(false, 0);
        
        // Obtener dimensiones exactas de la página
        $pageWidth = $this->getPageWidth();
        $pageHeight = $this->getPageHeight();
        
        // Insertar imagen de fondo (sin considerar márgenes)
        $this->Image('imgAdmin/Reporte_plantilla.png', 
                    0,  // X: desde el borde izquierdo
                    0,  // Y: desde el borde superior
                    $pageWidth, 
                    $pageHeight, 
                    'PNG', '', '', false, 300, '', false, false, 0, false, false, true);
        
        // Restaurar márgenes originales
        $this->SetMargins($originalMargins['l'], $originalMargins['t'], $originalMargins['r']);
        $this->SetAutoPageBreak(true, $originalMargins['b']);
        
        if ($this->getPage() == 1) {
            $this->SetY(17);
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(0, 10, 'Reporte de ingresos', 0, 1, 'C');
        }

        // Encabezado de tabla (se repite en todas las páginas)
        $this->SetY(30); // Posición fija para el encabezado de tabla
        $this->SetFont('helvetica', 'B', 10); // Misma fuente que los datos pero en negrita
        $this->SetTextColor(0);
        $this->SetDrawColor(0);

        $this->Cell(50, 8, 'Fecha y Hora', 1, 0, 'C');
        $this->Cell(60, 8, 'Identificador', 1, 0, 'C');
        $this->Cell(100, 8, 'Descripción', 1, 0, 'C');
        $this->Cell(60, 8, 'Nombre Administrador', 1, 1, 'C');
    }
}

$pdf = new CustomPDF('L', 'mm', 'A4');
$pdf->SetMargins(15, 40, 10); // Margen superior más grande para el encabezado
$pdf->SetAutoPageBreak(true, 30); // Margen inferior para el pie de página

// Primera página
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10); // Fuente para los datos

// Contenido de la tabla
$pdf->SetY(40); // Posición inicial del contenido
foreach ($datos as $fila) {
    // Determinar el tipo
    switch ($fila['tabla_afectada']) {
        case 'usuario':
            $tipo = 'Usuario';
            break;
        case 'vehiculos':
            $tipo = 'Vehículo';
            break;
        case 'objetos':
            $tipo = 'Objeto';
            break;
        default:
            $tipo = 'Otro';
    }
    
    // Concatenar tipo con identificador
    $identificador = strtolower($tipo) . ':' . $fila['id_afectado'];
    
    // Verificar si necesita nueva página
    if ($pdf->GetY() > $pdf->getPageHeight() - 30) {
        $pdf->AddPage();
        $pdf->SetY(40);
    }
    
    $pdf->Cell(50, 8, $fila['fecha'], 1, 0, 'L');
    $pdf->Cell(60, 8, $identificador, 1, 0, 'L');
    $pdf->Cell(100, 8, $fila['descripcion'], 1, 0, 'L');
    $pdf->Cell(60, 8, $fila['documento_generador'], 1, 1, 'L');
}

$pdf->Output('reporte.pdf', 'I');