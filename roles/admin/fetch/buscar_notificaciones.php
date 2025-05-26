<?php
include('../../../database/database.php'); // Incluye la conexión a la base de datos
session_start(); // Inicia la sesión

$conexion = new database;
$con = $conexion->conectar(); // Obtiene la conexión PDO

$documento = $_SESSION['documento']; 
$stmt = $con->prepare("SET @documento_generador = :doc");
$stmt->bindValue(':doc', $documento);
$stmt->execute();

// Obtiene los parámetros enviados por GET (filtros)
$documento = $_GET['documento'] ?? null;
$tabla = $_GET['tabla'] ?? null;
$fecha = $_GET['fecha'] ?? null;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$por_pagina = 20;
$offset = ($pagina - 1) * $por_pagina;

// Arreglos para construir la consulta dinámica
$where = []; //where condicional
$params = []; // Parámetros para la consulta

// Si se ingresó un documento, agrega condición y parámetro 
if (!empty($documento)) {
    $where[] = "id_afectado LIKE :documento";
    $params[':documento'] = "%$documento%";
}

// Si se seleccionó una tabla/módulo, agrega condición y parámetro
if (!empty($tabla)) {
    $where[] = "tabla_afectada = :tabla";
    $params[':tabla'] = $tabla;
}

// Si se seleccionó una fecha, agrega condición y parámetro
if (!empty($fecha)) {
    $where[] = "DATE(fecha) = :fecha";
    $params[':fecha'] = $fecha;
}

// Construye la consulta SQL base
$query = "SELECT * FROM notificaciones";
// Si hay condiciones, las agrega a la consulta
if (count($where) > 0) {
    $query .= " WHERE " . implode(" AND ", $where);
}
$query .= " ORDER BY fecha DESC LIMIT :limit OFFSET :offset";

// Para contar el total de resultados (para la paginación)
$count_query = "SELECT COUNT(*) FROM notificaciones";
if (count($where) > 0) {
    $count_query .= " WHERE " . implode(" AND ", $where);
}

try {
    // Consulta para el total
    $count_stmt = $con->prepare($count_query);
    foreach ($params as $key => $val) {
        $count_stmt->bindValue($key, $val);
    }
    $count_stmt->execute();
    $total = $count_stmt->fetchColumn();

    // Consulta para los resultados paginados
    $sql = $con->prepare($query);
    foreach ($params as $key => $val) {
        $sql->bindValue($key, $val);
    }
    $sql->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
    $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
    $sql->execute();
    $notificaciones = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Si hay notificaciones, las muestra en tarjetas
    if (count($notificaciones) > 0) {
        echo '<div class="grid-notificaciones">';
        foreach ($notificaciones as $notif) {
            echo '<div class="notificacion">';
            $tipoEvento = $notif['tipo_evento'];
            switch ($tipoEvento) {
                case 'INSERT':
                    $tipoEventoMostrar = 'Agregado';
                    break;
                case 'UPDATE':
                    $tipoEventoMostrar = 'Actualizado';
                    break;
                case 'DELETE':
                    $tipoEventoMostrar = 'Eliminado';
                    break;
                default:
                    $tipoEventoMostrar = $tipoEvento;
            }
            echo "<b>{$tipoEventoMostrar} en {$notif['tabla_afectada']}</b>";
            echo '<p>' . htmlspecialchars($notif['descripcion']) . '</p>';
            echo '<small>Realizado por: ' . htmlspecialchars($notif['documento_generador']) . '</small>';
            echo '<time>' . date("d/m/Y H:i", strtotime($notif['fecha'])) . '</time>';
            echo '</div>';
        }
        echo '</div>';

        // PAGINACIÓN INTELIGENTE
        $total_paginas = ceil($total / $por_pagina);
        if ($total_paginas > 1) {
            echo '<div class="paginacion-tabla">';
            $max_btns = 5; // Número máximo de botones visibles

            // Siempre muestra el primer botón
            if ($pagina > 1) {
                echo '<button class="pagina-btn" data-pagina="1">1</button>';
                if ($pagina > 3) echo '<span class="puntos"> - </span>';
            }

            // Botones alrededor de la página actual
            $start = max(2, $pagina - 2);
            $end = min($total_paginas - 1, $pagina + 2);
            for ($i = $start; $i <= $end; $i++) {
                $active = ($i == $pagina) ? 'active' : '';
                echo '<button class="pagina-btn ' . $active . '" data-pagina="' . $i . '">' . $i . '</button>';
            }

            // Siempre muestra el último botón
            if ($pagina < $total_paginas - 2) {
                if ($pagina < $total_paginas - 3) echo '<span class="puntos"> - </span>';
                echo '<button class="pagina-btn" data-pagina="' . $total_paginas . '">' . $total_paginas . '</button>';
            }

            // Botón siguiente
            if ($pagina < $total_paginas) {
                echo '<button class="pagina-btn siguiente-btn" data-pagina="' . ($pagina + 1) . '">Siguiente &raquo;</button>';
            }
            echo '</div>';
        }
    } else {
        // Si no hay resultados, muestra un mensaje
        echo '<p>No hay notificaciones encontradas.</p>';
    }
} catch (Exception $e) {
    // Si ocurre un error, muestra el mensaje de error
    echo '<p>Error al cargar las notificaciones: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>