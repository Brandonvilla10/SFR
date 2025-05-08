<?php
include('../../../database/database.php');
session_start();

$conexion = new database;
$con = $conexion->conectar();

$documento = $_GET['documento'] ?? null;
$tabla = $_GET['tabla'] ?? null;
$fecha = $_GET['fecha'] ?? null;

$where = [];
$params = [];

if (!empty($documento)) {
    $where[] = "id_afectado LIKE :documento";
    $params[':documento'] = "%$documento%";
}

if (!empty($tabla)) {
    $where[] = "tabla_afectada = :tabla";
    $params[':tabla'] = $tabla;
}

if (!empty($fecha)) {
    $where[] = "DATE(fecha) = :fecha";
    $params[':fecha'] = $fecha;
}

$query = "SELECT * FROM notificaciones";
if (count($where) > 0) {
    $query .= " WHERE " . implode(" AND ", $where);
}
$query .= " ORDER BY fecha DESC";

try {
    $sql = $con->prepare($query);
    foreach ($params as $key => $val) {
        $sql->bindValue($key, $val);
    }
    $sql->execute();
    $notificaciones = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (count($notificaciones) > 0) {
        echo '<div class="grid-notificaciones">';
        foreach ($notificaciones as $notif) {
            echo '<div class="notificacion">';
            echo '<h4>' . htmlspecialchars($notif['tipo_evento'] . ' en ' . $notif['tabla_afectada']) . '</h4>';
            echo '<p>' . htmlspecialchars($notif['descripcion']) . '</p>';
            echo '<small>Realizado por: ' . htmlspecialchars($notif['documento_generador']) . '</small>';
            echo '<time>' . date("d/m/Y H:i", strtotime($notif['fecha'])) . '</time>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No hay notificaciones encontradas.</p>';
    }
} catch (Exception $e) {
    echo '<p>Error al cargar las notificaciones: ' . htmlspecialchars($e->getMessage()) . '</p>';
}