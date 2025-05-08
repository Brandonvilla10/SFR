<?php
session_start();
require_once('../../database/database.php');

$conexion = new database;
$con = $conexion->conectar();
$documentoBuscado = $_SESSION['documento'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="../../css/home.css">
    <link rel="stylesheet" href="css/notificaciones.css">
    <style>
        .contenedor-notificaciones form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .contenedor-notificaciones select,
        .contenedor-notificaciones button {
            padding: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container_grid">
    <aside class="aside_barra">
        <?php include('asideBar/asideBar.php'); ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="titulo">
            <div>
                <h1>Notificaciones</h1>
                <p style="color:#858585">Visualiza las notificaciones según el tipo</p>
            </div>
        </div>
        <hr class="separador">
        
        <div class="contenedor-notificaciones">

            <!-- Filtro por tabla afectada -->
            <form id="filtrosNotificaciones">
                <select name="tabla" id="tabla">
                    <option value="">-- Filtrar por tipo --</option>
                    <option value="vehiculos">Vehículos</option>
                    <option value="objetos">Objetos</option>
                    <option value="usuario">Usuarios</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>

            <!-- Resultados dinámicos -->
            <div id="resultadoNotificaciones">
                <!-- Aquí se cargan las notificaciones por JavaScript -->
            </div>
        </div>
    </main>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
document.getElementById("filtrosNotificaciones").addEventListener("submit", function (e) {
    e.preventDefault();

    const tabla = document.getElementById("tabla").value;
    const params = new URLSearchParams({ tabla });

    fetch("buscar_notificaciones.php?" + params.toString())
        .then(res => res.text())
        .then(data => document.getElementById("resultadoNotificaciones").innerHTML = data)
        .catch(() => {
            document.getElementById("resultadoNotificaciones").innerHTML = "<p>Error al cargar notificaciones.</p>";
        });
});

// Carga inicial sin filtro
window.addEventListener("DOMContentLoaded", () => {
    document.getElementById("filtrosNotificaciones").dispatchEvent(new Event('submit'));
});
</script>

</body>
</html>
