<?php
include('../../../database/database.php');
session_start();

$conexion = new database;
$con = $conexion->conectar();

$tabla = $_GET['tabla'] ?? null;

try {
    if (!empty($tabla)) {
        $sql = $con->prepare("SELECT * FROM notificaciones WHERE tabla_afectada = :tabla ORDER BY fecha DESC");
        $sql->bindParam(':tabla', $tabla, PDO::PARAM_STR);
    } else {
        $sql = $con->prepare("SELECT * FROM notificaciones ORDER BY fecha DESC");
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

