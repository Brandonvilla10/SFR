<?php
session_start();
require_once('../../database/database.php');
include('components/alert.php');
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
                <p style="color:#858585">Visualiza las notificacions segun el usuario</p>
            </div>
        </div>
        <hr class="separador">
        <div class="contenedor-notificaciones">
           
            <input type="text" class="buscar_documento_con_icono" id="buscarDocumento" placeholder="Buscar por documento..." value="<?php htmlspecialchars($documentoBuscado); ?>">
        
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
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("buscarDocumento");
    const contenedor = document.getElementById("resultadoNotificaciones");

    function buscar(documento) {
        fetch("fetch/buscar_notificaciones.php?documento=" + encodeURIComponent(documento))
            .then(res => res.text())
            .then(data => contenedor.innerHTML = data)
            .catch(err => contenedor.innerHTML = "<p>Error al cargar notificaciones.</p>");
    }

    input.addEventListener("input", () => buscar(input.value));
    
    // Carga inicial con documento de sesión
    buscar(input.value);
});
</script>

</body>
</html>