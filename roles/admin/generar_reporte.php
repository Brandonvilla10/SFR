<?php
ob_start();
require_once(__DIR__ . '/../../js/TCPDF/tcpdf.php');
require_once('../../database/database.php');

function generarPDF($titulo, $columnas, $datos, $nombreArchivo = 'reporte.pdf') {
    $pdf = new TCPDF();
    $pdf->AddPage('L');
    $pdf->SetFont('helvetica', '', 12);

    // Marca de agua
    $pdf->setAlpha(0.1);
    $pdf->Image(__DIR__ . '/../../assets/img/LogoconFONDO.png', 60, 40, 180, 100, '', '', '', false, 300, '', false, false, 0);
    $pdf->setAlpha(1);

    $pdf->Cell(0, 10, $titulo, 0, 1, 'C');

    // Construir tabla HTML
    $html = '<table border="1" cellpadding="4"><tr>';
    foreach ($columnas as $col) {
        $html .= '<th>' . $col . '</th>';
    }
    $html .= '</tr>';

    foreach ($datos as $fila) {
        $html .= '<tr>';
        foreach ($fila as $valor) {
            $html .= '<td>' . $valor . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($nombreArchivo, 'I');
}

// --- Lógica para elegir el módulo y consulta ---
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$modulo = $_POST['tabla_afectada'] ?? 'usuario';

$conexion = new database;
$con = $conexion->conectar();

switch ($modulo) {
    case 'objetos':
        $query = "
            SELECT r.fechaEntrada, u.nombre_completo, o.nombre AS objeto, r.fechaSalida, n.documento_generador
            FROM notificaciones n
            INNER JOIN registroentradaobjetos r ON n.id_afectado = r.id_registroObjeto
            INNER JOIN objetos o ON r.id_serial = o.id_serial
            INNER JOIN usuario u ON o.id_documento = u.id_documento
            WHERE n.tabla_afectada = 'registro_entrada_objetos'
              AND r.fechaEntrada BETWEEN :inicio AND :fin
            ORDER BY r.fechaEntrada DESC
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Objeto', 'Fecha Salida', 'Administrador'];
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
            ORDER BY r.fechaEntrada DESC
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Placa', 'Fecha Salida', 'Administrador'];
        break;
    default: // personas
        $query = "
            SELECT r.fechaEntrada, u.nombre_completo, n.descripcion, r.fechaSalida, n.documento_generador
            FROM notificaciones n
            INNER JOIN registroentradapersona r ON n.id_afectado = r.id_registro
            INNER JOIN usuario u ON r.id_documento = u.id_documento
            WHERE n.tabla_afectada = 'registroentreadapersona'
              AND r.fechaEntrada BETWEEN :inicio AND :fin
            ORDER BY r.fechaEntrada DESC
        ";
        $columnas = ['Fecha Ingreso', 'Persona', 'Descripción', 'Fecha Salida', 'Administrador'];
        break;
}

$params = [
    ':inicio' => $fecha_inicio . ' 00:00:00',
    ':fin' => $fecha_fin . ' 23:59:59'
];
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

// Llama la función para generar el PDF
generarPDF('Reporte de ' . ucfirst($modulo), $columnas, $datos);
