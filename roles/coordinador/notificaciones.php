<?php
session_start();
require_once('../../database/database.php');
include('components/alert.php');
include('components/loading.php');
include('../../includes/sesion_validar.php');

$conexion = new database;
$con = $conexion->conectar();

$documento = $_SESSION['documento'] ?? '';

if (!$documento) {
    echo "Documento no definido en la sesión.";
    exit;
}

$stmt = $con->prepare("SET @documento_generador = :doc");
$stmt->bindValue(':doc', $documento);
$stmt->execute();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_leidas'])) {
    $stmt = $con->prepare("UPDATE notificaciones SET leida = 1 WHERE id_afectado = :doc AND leida = 0");
    $stmt->bindValue(':doc', $documento);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="../../css/home.css">
    <link rel="stylesheet" href="css/notificaciones.css">
</head>
<body data-documento="<?= htmlspecialchars($documento) ?>">

<div class="container_grid">
    <aside class="aside_barra">
        <?php include('asideBar/asideBar.php'); ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="titulo">
            <div>
                <h1>Notificaciones</h1>
                <p style="color:#858585">Visualiza las notificaciones según el usuario</p>
            </div>
        </div>
        <hr class="separador">

        <div class="contenedor-notificaciones">
            <button id="toggleFiltros" hidden>Mostrar filtros</button>

            <div id="filtrosOpcionales" style="display:none; margin-top: 10px;">
                <select id="tablaAfectada">
                    <option value=""> Módulo </option>
                   
                </select>
                <input type="date" id="fechaFiltro">
                <button id="limpiarFiltrosBtn">Limpiar filtros</button>
            </div>

            <div id="resultadoNotificaciones">
                <p>Usa los filtros para buscar notificaciones</p>
            </div>
        </div>
    </main>
</div>

<script>
//DIV DE FONDO 
    window.addEventListener('load', function() {
        fondo = document.getElementById('fondo');
        setTimeout(function() {
            fondo.classList.add('fade-out');
        }, 1000); 

        setTimeout(function() {
           fondo.style.display = 'none';
                
        }, 1200); // Cambia el valor a 1000 si deseas que se muestre durante 1 segundo
            

    });
    
document.getElementById('toggleFiltros').addEventListener('click', () => {
    const filtros = document.getElementById('filtrosOpcionales');
    const boton = document.getElementById('toggleFiltros');
    filtros.style.display = filtros.style.display === 'none' ? 'block' : 'none';
    boton.textContent = filtros.style.display === 'block' ? 'Ocultar filtros' : 'Mostrar filtros';
});

document.getElementById('tablaAfectada').addEventListener('change', () => cargarNotificaciones());
document.getElementById('fechaFiltro').addEventListener('change', () => cargarNotificaciones());
document.getElementById('limpiarFiltrosBtn').addEventListener('click', () => {
    document.getElementById('tablaAfectada').value = '';
    document.getElementById('fechaFiltro').value = '';
    cargarNotificaciones();
});

function cargarNotificaciones(pagina = 1) {
    const tabla = document.getElementById('tablaAfectada').value;
    const fecha = document.getElementById('fechaFiltro').value;
    const documento = document.body.getAttribute('data-documento');

    const params = new URLSearchParams({
        documento: documento,
        tabla: tabla,
        fecha: fecha,
        pagina: pagina
    });

    document.getElementById('resultadoNotificaciones').innerHTML = '<p>Cargando notificaciones...</p>';

    fetch(`fetch/buscar_notificaciones.php?${params.toString()}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById('resultadoNotificaciones').innerHTML = data;
            document.querySelectorAll('.pagina-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    cargarNotificaciones(this.getAttribute('data-pagina'));
                });
            });
        })
        .catch(error => {
            document.getElementById('resultadoNotificaciones').innerHTML = '<p>Error al cargar notificaciones: ' + error + '</p>';
        });
}

function actualizarContadorNotificaciones() {
    fetch('fetch/contar_notificaciones.php')
        .then(response => response.json())
        .then(data => {
            const contador = document.getElementById('contadorNotificaciones');
            if (data.total > 0) {
                contador.textContent = data.total;
                contador.style.display = 'inline-block';
            } else {
                contador.style.display = 'none';
            }
        });
}

function marcarNotificacionesComoLeidas() {
    fetch('notificaciones.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'marcar_leidas=1'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) actualizarContadorNotificaciones();
    });
}

window.addEventListener('DOMContentLoaded', () => {
    cargarNotificaciones();
    marcarNotificacionesComoLeidas();
    actualizarContadorNotificaciones();
});

setInterval(actualizarContadorNotificaciones, 10000); // cada 10 segundos
</script>

</body>
</html>
