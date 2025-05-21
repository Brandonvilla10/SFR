<?php
session_start();
require_once('../../database/database.php');

include('components/alert.php');
$conexion = new database;
$con = $conexion->conectar();

$documentoBuscado = $_SESSION['documento'] ?? '';

$documento = $_SESSION['documento']; // o el dato del usuario logueado
$stmt = $con->prepare("SET @documento_generador = :doc");
$stmt->bindValue(':doc', $documento);
$stmt->execute();


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
    <!-- Menú lateral -->
    <aside class="aside_barra">
        <!-- Incluye el archivo PHP que contiene el diseño del menú lateral -->
        <?php include('asideBar/asideBar.php'); ?>
    </aside>

    <!-- Contenido principal -->
    <main class="grid_contenido_plantilla">
        <!-- Título y descripción de la página -->
        <div class="titulo">
            <div>
                <h1>Notificaciones</h1>
                <p style="color:#858585">Visualiza las notificaciones según el usuario</p>
            </div>
        </div>
        <hr class="separador">

        <div class="contenedor-notificaciones">
            <!-- Campo de búsqueda por documento -->
            <input type="text" class="buscar_documento_con_icono" id="buscarDocumento"
                   placeholder="Buscar por documento..." 
                   value="<?php htmlspecialchars($documentoBuscado); ?>">
                                   <span id="contadorNotificaciones" style="display:none" class="badge"></span>

            <!-- Botón para mostrar/ocultar los filtros adicionales -->
            <button id="toggleFiltros">Mostrar filtros</button>

            <!-- Filtros adicionales (inicialmente ocultos) -->
            <div id="filtrosOpcionales" style="display:none; margin-top: 10px;">
                <!-- Selector para filtrar por módulo -->
                <select id="tablaAfectada">
                    <option value="">-- Seleccionar módulo --</option>
                    <option value="vehiculos">Vehículos</option>
                    <option value="objetos">Objetos</option>
                    <option value="usuarios">Usuarios</option>
                </select>

                <!-- Selector de fecha para filtrar notificaciones -->
                <input type="date" id="fechaFiltro">

                <!-- Botones para aplicar o limpiar filtros -->
                <button id="limpiarFiltrosBtn">Limpiar filtros</button>
            </div>

            <!-- Contenedor donde se muestran los resultados de las notificaciones -->
            <div id="resultadoNotificaciones">
                <p>Usa los filtros para buscar notificaciones</p>
            </div>
        </div>
    </main>
</div>

<script type="module" src="https://unpkg.com/ionicons @7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons @7.1.0/dist/ionicons/ionicons.js"></script>

<script>
// Función para alternar la visibilidad de los filtros adicionales
document.getElementById('toggleFiltros').addEventListener('click', () => {
    const filtros = document.getElementById('filtrosOpcionales'); // Obtiene el contenedor de filtros
    const boton = document.getElementById('toggleFiltros'); // Obtiene el botón "Mostrar/Ocultar filtros"

    if (filtros.style.display === 'none') { // Si los filtros están ocultos
        filtros.style.display = 'block'; // Muestra los filtros
        boton.textContent = 'Ocultar filtros'; // Cambia el texto del botón
    } else { // Si los filtros están visibles...
        filtros.style.display = 'none'; // Oculta los filtros
        boton.textContent = 'Mostrar filtros'; // Cambia el texto del botón
    }
});

// Llama a cargarNotificaciones cuando se escribe en el input de documento
document.getElementById('buscarDocumento').addEventListener('input', () => cargarNotificaciones());

// Llama a cargarNotificaciones cuando se selecciona un módulo
document.getElementById('tablaAfectada').addEventListener('change', () => cargarNotificaciones());

// Llama a cargarNotificaciones cuando se selecciona una fecha
document.getElementById('fechaFiltro').addEventListener('change', () => cargarNotificaciones());

// Si tienes botón de limpiar filtros, puedes dejarlo así:
document.getElementById('limpiarFiltrosBtn').addEventListener('click', () => {
    document.getElementById('buscarDocumento').value = '';
    document.getElementById('tablaAfectada').value = '';
    document.getElementById('fechaFiltro').value = '';
    cargarNotificaciones();
});

// Llama a cargarNotificaciones al cargar la página para mostrar resultados iniciales
window.addEventListener('DOMContentLoaded', () => cargarNotificaciones());

// Función para cargar notificaciones con filtros y paginación
function cargarNotificaciones(pagina = 1) {
    // Obtiene los valores de los campos de filtro
    const documento = document.getElementById('buscarDocumento').value;
    const tabla = document.getElementById('tablaAfectada').value;
    const fecha = document.getElementById('fechaFiltro').value;

    // Construye los parámetros de la solicitud
    const params = new URLSearchParams({
        documento: documento,
        tabla: tabla,
        fecha: fecha,
        pagina: pagina // Número de página para la paginación
    });

    // Muestra un mensaje de carga mientras se obtienen los datos
    document.getElementById('resultadoNotificaciones').innerHTML = '<p>Cargando notificaciones...</p>';

    // Aquí va el bloque que mencionas:
    fetch(`fetch/buscar_notificaciones.php?${params.toString()}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById('resultadoNotificaciones').innerHTML = data;
            // Eventos para los botones de paginación
            document.querySelectorAll('.pagina-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    cargarNotificaciones(this.getAttribute('data-pagina'));
                });
            });
        })
        .catch(error => {
            document.getElementById('resultadoNotificaciones').innerHTML = '<p>Error al cargar notificaciones: ' + error + '</p>';
        });
}


// Evento para el botón "Limpiar filtros"
document.getElementById('limpiarFiltrosBtn').addEventListener('click', () => {
    // Restablece los valores de los campos de filtro
    document.getElementById('buscarDocumento').value = '';
    document.getElementById('tablaAfectada').value = '';
    document.getElementById('fechaFiltro').value = '';

    // Muestra un mensaje indicando que se deben usar los filtros
    document.getElementById('resultadoNotificaciones').innerHTML = '<p>Usa los filtros para buscar notificaciones</p>';
});

// Evento para buscar al presionar Enter en el campo de documento
document.getElementById('buscarDocumento').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { // Si se presiona la tecla "Enter"...
        cargarNotificaciones(1); // Carga las notificaciones filtradas
    }
});

// Si hay un documento inicial en la sesión, carga las notificaciones automáticamente
window.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('buscarDocumento').value) { // Si el campo de documento no está vacío...
        cargarNotificaciones(1); // Carga las notificaciones
    }
});

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

window.addEventListener('DOMContentLoaded', actualizarContadorNotificaciones);
setInterval(actualizarContadorNotificaciones, 30000); // cada 30 segundos
</script>

</body>
</html>