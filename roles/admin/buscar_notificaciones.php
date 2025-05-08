<?php
include('../../database/database.php');
session_start();

$conexion = new database;
$con = $conexion->conectar();

if (!isset($_SESSION['documento'])) {
    header("Location: ../../index.php");   
    exit();
}

// Establecer la variable @documento_generador en MySQL desde la sesión


    try {
        $sql = $con->prepare("SELECT nombre_completo FROM usuario WHERE id_documento = $_SESSION[documento]");
        $sql->execute();
        $nombre = $sql->fetch(PDO::FETCH_ASSOC);
        $con->exec("SET @documento_generador = '$nombre[nombre_completo]'");
    } catch (Exception $e) {
        echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
        exit;
    }


// Obtener el parámetro de búsqueda por documento (opcional)
$documento = $_GET['documento'] ?? null;

try {
    if (!empty($documento)) {
        $sql = $con->prepare("SELECT * FROM notificaciones WHERE id_afectado LIKE :documento ORDER BY fecha DESC");
        $documento = "%$documento%";
        $sql->bindParam(':documento', $documento, PDO::PARAM_STR);
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
            
            echo '<time>' . date("d/m/Y H:i", strtotime($notif['fecha'])) . '</time>';
            echo '</div>';
        }
        echo '</div>'; // Cierra el contenedor de notificaciones
    } else {
        echo '<p>No hay notificaciones encontradas.</p>';
    }
} catch (Exception $e) {
    echo '<p>Error al cargar las notificaciones: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
