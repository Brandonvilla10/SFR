<?php
ob_start();
require_once(__DIR__ . '/../../js/TCPDF/tcpdf.php');
require_once('../../database/database.php');

// Recibe los datos del formulario PDF
$filtro = isset($_POST['filtro_pdf']) ? trim($_POST['filtro_pdf']) : '';
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$modulo = $_POST['tipo_reporte_pdf'] ?? 'todos';

$conexion = new database;
$con = $conexion->conectar();

$columnas = [];
$params = [];

switch ($modulo) {
    case 'objetos':
        $query = "
            SELECT r.fechaEntrada, u.nombre_completo, o.nombre AS objeto, r.fechaSalida, 'N/A' as documento_generador
            FROM registroentradaobjetos r
            INNER JOIN objetos o ON r.id_serial = o.id_serial
            INNER JOIN usuario u ON o.id_documento = u.id_documento
            WHERE r.fechaEntrada BETWEEN :inicio AND :fin
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Objeto', 'Fecha Salida', 'Administrador'];
        $params = [
            ':inicio' => $fecha_inicio . ' 00:00:00',
            ':fin' => $fecha_fin . ' 23:59:59'
        ];
        if ($filtro !== '') {
            $query .= " AND (
                o.id_serial = :filtro1
                OR o.nombre LIKE :filtroLike1
                OR u.nombre_completo LIKE :filtroLike2
                OR o.id_documento = :filtro2
            )";
            $params[':filtro1'] = $filtro;
            $params[':filtro2'] = $filtro;
            $params[':filtroLike1'] = "%$filtro%";
            $params[':filtroLike2'] = "%$filtro%";
        }
        $query .= " ORDER BY r.fechaEntrada DESC";
        break;

    case 'vehiculos':
        $query = "
            SELECT r.fechaEntrada, u.nombre_completo, v.id_placa, r.fechaSalida, n.documento_generador
            FROM notificaciones n
            INNER JOIN registroentradavehiculos r ON n.id_afectado = r.id_registroVehiculo
            INNER JOIN vehiculos v ON r.id_placa = v.id_placa
            INNER JOIN usuario u ON v.id_documento = u.id_documento
            WHERE n.tabla_afectada = 'registro_entrada_vehiculos'
              AND r.fechaEntrada BETWEEN :inicio AND :fin
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Placa', 'Fecha Salida', 'Administrador'];
        $params = [
            ':inicio' => $fecha_inicio . ' 00:00:00',
            ':fin' => $fecha_fin . ' 23:59:59'
        ];
        if ($filtro !== '') {
            $query .= " AND (
                v.id_placa = :filtro1
                OR u.nombre_completo LIKE :filtroLike
                OR v.id_documento = :filtro2
            )";
            $params[':filtro1'] = $filtro;
            $params[':filtro2'] = $filtro;
            $params[':filtroLike'] = "%$filtro%";
        }
        $query .= " ORDER BY r.fechaEntrada DESC";
        break;

    case 'personas':
        $query = "
            SELECT r.fechaEntrada, u.nombre_completo, n.descripcion, r.fechaSalida, n.documento_generador
            FROM notificaciones n
            INNER JOIN registroentradapersona r ON n.id_afectado = r.id_registro
            INNER JOIN usuario u ON r.id_documento = u.id_documento
            WHERE n.tabla_afectada = 'registroentreadapersona'
              AND r.fechaEntrada BETWEEN :inicio AND :fin
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Descripción', 'Fecha Salida', 'Administrador'];
        $params = [
            ':inicio' => $fecha_inicio . ' 00:00:00',
            ':fin' => $fecha_fin . ' 23:59:59'
        ];
        if ($filtro !== '') {
            $query .= " AND (
                r.id_documento = :filtro
                OR u.nombre_completo LIKE :filtroLike
            )";
            $params[':filtro'] = $filtro;
            $params[':filtroLike'] = "%$filtro%";
        }
        $query .= " ORDER BY r.fechaEntrada DESC";
        break;

    default: // todos
        $whereFiltro1 = $whereFiltro2 = $whereFiltro3 = '';
        $params = [
            ':inicio1' => $fecha_inicio . ' 00:00:00',
            ':fin1' => $fecha_fin . ' 23:59:59',
            ':inicio2' => $fecha_inicio . ' 00:00:00',
            ':fin2' => $fecha_fin . ' 23:59:59',
            ':inicio3' => $fecha_inicio . ' 00:00:00',
            ':fin3' => $fecha_fin . ' 23:59:59'
        ];
        if ($filtro !== '') {
            $whereFiltro1 = " AND (o.id_serial = :filtro1 OR o.nombre LIKE :filtroLike1 OR u.nombre_completo LIKE :filtroLike1 OR o.id_documento = :filtro1)";
            $whereFiltro2 = " AND (v.id_placa = :filtro2 OR u.nombre_completo LIKE :filtroLike2 OR v.id_documento = :filtro2)";
            $whereFiltro3 = " AND (r.id_documento = :filtro3 OR u.nombre_completo LIKE :filtroLike3)";
            $params[':filtro1'] = $filtro;
            $params[':filtroLike1'] = "%$filtro%";
            $params[':filtro2'] = $filtro;
            $params[':filtroLike2'] = "%$filtro%";
            $params[':filtro3'] = $filtro;
            $params[':filtroLike3'] = "%$filtro%";
        }
        $query = "
        SELECT r.fechaEntrada, u.nombre_completo, o.nombre AS descripcion, r.fechaSalida, n.documento_generador 
        FROM notificaciones n
        INNER JOIN registroentradaobjetos r ON n.id_afectado = r.id_registroObjeto
        INNER JOIN objetos o ON r.id_serial = o.id_serial
        INNER JOIN usuario u ON o.id_documento = u.id_documento
        WHERE n.tabla_afectada = 'registro_entrada_objetos'
          AND r.fechaEntrada BETWEEN :inicio1 AND :fin1 $whereFiltro1

        UNION ALL

        SELECT r.fechaEntrada, u.nombre_completo, v.id_placa AS descripcion, r.fechaSalida, n.documento_generador 
        FROM notificaciones n
        INNER JOIN registroentradavehiculos r ON n.id_afectado = r.id_registroVehiculo
        INNER JOIN vehiculos v ON r.id_placa = v.id_placa
        INNER JOIN usuario u ON v.id_documento = u.id_documento
        WHERE n.tabla_afectada = 'registro_entrada_vehiculos'
          AND r.fechaEntrada BETWEEN :inicio2 AND :fin2 $whereFiltro2

        UNION ALL

        SELECT r.fechaEntrada, u.nombre_completo, n.descripcion, r.fechaSalida, n.documento_generador
        FROM notificaciones n
        INNER JOIN registroentradapersona r ON n.id_afectado = r.id_registro
        INNER JOIN usuario u ON r.id_documento = u.id_documento
        WHERE n.tabla_afectada = 'registroentreadapersona'
          AND r.fechaEntrada BETWEEN :inicio3 AND :fin3 $whereFiltro3

        ORDER BY fechaEntrada DESC
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Descripción', 'Fecha Salida', 'Administrador'];
        break;
}

// Debug: Imprimir la consulta y parámetros (quitar en producción)
// echo "<pre>";
// echo "Módulo: " . $modulo . "\n";
// echo "Filtro: '" . $filtro . "'\n";
// echo "Query: " . $query . "\n";
// echo "Params: " . print_r($params, true) . "\n";
// echo "</pre>";
// die(); // Detener ejecución para ver el debug

$stmt = $con->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_NUM);

// Reemplazar valores nulos o vacíos en la columna de salida por "Dentro del SENA"
foreach ($datos as &$fila) {
    if (
        !isset($fila[3]) ||
        is_null($fila[3]) ||
        $fila[3] === "" ||
        $fila[3] === "0000-00-00 00:00:00"
    ) {
        $fila[3] = 'Dentro del SENA';
    }
}
unset($fila);

// Crear PDF con encabezado personalizado y fondo
class CustomPDF extends TCPDF {
    public function Header() {
        $originalMargins = [
            'l' => $this->lMargin,
            'r' => $this->rMargin,
            't' => $this->tMargin,
            'b' => $this->bMargin
        ];
        $this->SetMargins(0, 0, 0);
        $this->SetAutoPageBreak(false, 0);
        $pageWidth = $this->getPageWidth();
        $pageHeight = $this->getPageHeight();
        $this->Image('imgAdmin/Reporte_plantilla.png', 
                  0,  // X: desde el borde izquierdo
                  0,  // Y: desde el borde superior
                  $pageWidth, 
                  $pageHeight, 
                  'PNG', '', '', false, 300, '', false, false, 0, false, false, true);
        $this->SetMargins($originalMargins['l'], $originalMargins['t'], $originalMargins['r']);
        $this->SetAutoPageBreak(true, $originalMargins['b']);
        if ($this->getPage() == 1) {
            $this->SetY(17);
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(0, 10, 'Reporte de ingresos', 0, 1, 'C');
        }
        // Encabezado de tabla
        $this->SetY(30);
        $this->SetX(20);
        $this->SetFont('helvetica', 'B', 10);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        foreach ($GLOBALS['columnas'] as $col) {
            $this->Cell(50, 8, $col, 1, 0, 'C');
        }
        $this->Ln();
    }
}

$pdf = new CustomPDF('L', 'mm', 'A4');
$pdf->SetMargins(15, 40, 10);
$pdf->SetAutoPageBreak(true, 30);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->SetY(40);
$startY = $pdf->GetY(); // Guardar posición Y inicial

foreach ($datos as $fila) {
    // Verificar si necesitamos nueva página (dejar al menos 30mm para el margen inferior)
    if ($pdf->GetY() + 30 > $pdf->getPageHeight() - $pdf->getBreakMargin()) {
        $pdf->AddPage();
        $startY = $pdf->GetY();
    }
    
    $pdf->SetX(20);
    $maxHeight = 0;
    
    // Primera pasada: calcular altura máxima necesaria para la fila
    foreach ($fila as $valor) {
        $cellHeight = $pdf->getStringHeight(50, $valor, false, true, 0, 'L');
        if ($cellHeight > $maxHeight) {
            $maxHeight = $cellHeight;
        }
    }
    $rowHeight = $maxHeight + 2; // Pequeño margen adicional
    
    // Segunda pasada: dibujar celdas manteniendo la alineación
    $currentX = 20;
    foreach ($fila as $valor) {
        $pdf->SetXY($currentX, $startY);
        $pdf->MultiCell(50, $rowHeight, $valor, 1, 'L');
        $currentX += 50; // Mover a la siguiente columna
    }
    
    $startY += $rowHeight; // Mover a la siguiente fila
    $pdf->SetY($startY); // Actualizar posición Y
}
$pdf->Output('reporte.pdf', 'I');
?>