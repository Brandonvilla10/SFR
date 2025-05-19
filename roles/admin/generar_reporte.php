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

// Consulta de ejemplo (ajusta según tus necesidades)
$stmt = $con->prepare("SELECT id_notificacion, fecha, tipo_evento, tabla_afectada, id_afectado, descripcion FROM notificaciones WHERE fecha BETWEEN :inicio AND :fin");
$stmt->bindValue(':inicio', $fecha_inicio . ' 00:00:00');
$stmt->bindValue(':fin', $fecha_fin . ' 23:59:59');
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Reporte de Notificaciones', 0, 1, 'C');

$html = '<table border="1" cellpadding="4">
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Tipo Evento</th>
    <th>Tabla Afectada</th>
    <th>ID Afectado</th>
    <th>Descripción</th>
</tr>';
foreach ($datos as $fila) {
    $html .= '<tr>
        <td>' . $fila['id_notificacion'] . '</td>
        <td>' . $fila['fecha'] . '</td>
        <td>' . $fila['tipo_evento'] . '</td>
        <td>' . $fila['tabla_afectada'] . '</td>
        <td>' . $fila['id_afectado'] . '</td>
        <td>' . $fila['descripcion'] . '</td>
    </tr>';
}
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte.pdf', 'I');

ob_end_flush();
?>