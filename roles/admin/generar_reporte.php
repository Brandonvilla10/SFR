<?php
ob_clean();
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
$pdf = new TCPDF();
$pdf->AddPage('L');
$pdf->SetFont('helvetica', '', 12);

$pdf->setAlpha(0.1); //Opacidad 
$pdf->Image(__DIR__ . '/../../assets/img/LogoconFONDO.png', 60, 40, 180, 100, '', '', '', false, 300, '', false, false, 0);
$pdf->setAlpha(1); //Restablecer opacidad
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