<?php
ob_clean();
ob_start();

require_once(__DIR__ . '/../../TCPDF-main/tcpdf.php'); // Ajusta la ruta según tu estructura

$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];

// Conexión a la base de datos
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

// Cambia la consulta para traer también la tabla_afectada
$stmt = $con->prepare("SELECT id_notificacion, fecha, tipo_evento, id_afectado, descripcion, documento_generador, tabla_afectada FROM notificaciones WHERE fecha BETWEEN :inicio AND :fin");
$stmt->bindValue(':inicio', $fecha_inicio . ' 00:00:00');
$stmt->bindValue(':fin', $fecha_fin . ' 23:59:59');
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
$pdf = new TCPDF();
$pdf->AddPage('L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Reporte de Notificaciones', 0, 1, 'C');

$html = '<table border="1" cellpadding="4">
<tr>
    <th>Fecha y Hora</th>
    <th>Identificador</th>
    <th>Descripción</th>
    <th>Nombre Administrador</th>
</tr>';
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
    $html .= '<tr>
        <td>' . $fila['fecha'] . '</td>
        <td>' . $identificador . '</td>
        <td>' . $fila['descripcion'] . '</td>
        <td>' . $fila['documento_generador'] . '</td>
    </tr>';
}
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte.pdf', 'I');

ob_end_flush();
?>