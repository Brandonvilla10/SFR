<?php
// Inicia la sesión PHP
session_start();

// Se requiere el archivo de base de datos y se incluye el archivo de validación de sesión
require_once('../../database/database.php');
include('../../includes/sesion_validar.php');

// Establece una nueva conexión a la base de datos
$conexion = new database;
$con = $conexion->conectar();

// Obtiene información del usuario desde la sesión
$codigo = $_SESSION['documento']; // Documento del usuario
$nombre = explode(" ", $_SESSION['nombre'])[0]; // Primer nombre del usuario
$rol = $_SESSION['rol_name']; // Rol del usuario
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png"> <!-- Icono de la página -->
    <title>Home</title>
    <link rel="stylesheet" href="css/home.css"> <!-- Estilo principal de la página -->
    <link rel="stylesheet" href="asideBar/asidebar.css"> <!-- Estilo de la barra lateral -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Inclusión de Chart.js para gráficos -->
</head>
<body>
<div class="container_grid">

    <!-- Barra lateral -->
    <aside class="aside_barra">
        <?php  include ('asideBar/asideBar.php')?> <!-- Incluir la barra lateral desde un archivo PHP -->
    </aside>

    <!-- Encabezado de la página -->
    <header class="home_header">
      <div class="home_titulo"> 
        Bienvenido, <?php echo $nombre?> 🎉 <!-- Muestra el nombre del usuario -->
        <p>Rol: administrador</p> <!-- Muestra el rol del usuario -->
      </div>
      <div class ="home_iconos">
        <i class="bi bi-bell"></i> <!-- Icono de notificación -->
        <i class="bi bi-headset"></i> <!-- Icono de asistencia -->
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="grid_contenido_plantilla">

      <div class="graficas">
          <!-- Gráfico de barras -->
          <div class="grafica_barras"><canvas id="barras" width="200px" height="200px"></canvas></div>

          <!-- Gráfico de dona -->
          <div class="grafica_dona"><canvas id="donut" width="90px" height="90px"></canvas></div>
      </div>

      <!-- Tabla de objetos ingresados recientemente -->
      <div class="ingresos_usuarios">
        <div class="descripcion_tabla" style="padding-left: 20px;">
          <strong> OBJETOS INGRESADOS RECIENTEMENTE</strong>
        </div>

        <div class="tabla_home">
          <table class="tabla_users">
            <thead>
              <tr class="datos_encabezado">
                <th>FECHA</th>
                <th>DOCUMENTO</th>
                <th>NOMBRE</th>
                <th>ROL</th>
                <th>OBJECT</th>
              </tr>
            </thead>

            <tbody>
              <!-- Filas de ejemplo con objetos ingresados -->
              <tr>
                <td>2024-01-25</td>
                <td>12345678</td>
                <td>Juan Pérez</td>
                <td>Administrador</td>
                <td>Gestión de Usuarios</td>
              </tr>
              <tr>
                <td>2024-01-24</td>
                <td>87654321</td>
                <td>María Gómez</td>
                <td>Editor</td>
                <td>Control de Contenido</td>
              </tr>
              <tr>
                <td>2024-01-23</td>
                <td>11223344</td>
                <td>Carlos Ramírez</td>
                <td>Usuario</td>
                <td>Acceso a Reportes</td>
              </tr>
              <tr>
                <td>2024-01-22</td>
                <td>55667788</td>
                <td>Lucía Fernández</td>
                <td>Moderador</td>
                <td>Supervisión de Comentarios</td>
              </tr>
              <!-- Más filas aquí -->
            </tbody>
          </table>
        </div>
      </div>
    </main>

</div>

<!-- Importación de iconos -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
    // Gráfico de barras
    const barras = document.getElementById('barras');
    const datas = new Chart(barras, {
        type: "bar", // Tipo de gráfico: barras
        data: {
            labels: ['L', 'M', 'Mi', 'J', 'V', 'S'], // Etiquetas para los días de la semana
            datasets: [{
                label: "Usuarios Activos días anteriores", // Título del gráfico
                data: [15, 50, 70, 20, 80, 90], // Datos de usuarios activos
                backgroundColor: 'blue', // Color de fondo de las barras
                borderWidth: 3 // Grosor del borde de las barras
            }]
        },
        options: {
            responsive: true, // El gráfico se adapta al tamaño de la pantalla
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true // Inicia el eje Y desde cero
                    }
                }]
            }
        }
    });

    // Gráfico de dona
    const donita = document.getElementById('donut');
    const datos_donut = new Chart(donita, {
        type: "doughnut", // Tipo de gráfico: dona
        data: {
            labels: ['vehículos activos', 'vehículos inactivos'], // Etiquetas para el gráfico de dona
            datasets: [{
                label: 'Vehículos en el centro', // Título del gráfico de dona
                data: [10, 90], // Datos de vehículos activos e inactivos
                backgroundColor: ['blue', 'red'], // Colores de las porciones
                borderWidth: 2 // Grosor del borde de las porciones
            }]
        },
        options: {
            responsive: true, // El gráfico se adapta al tamaño de la pantalla
            plugins: {
                title: {
                    display: true,
                    text: 'Vehículos en el centro' // Título del gráfico
                }
            }
        }
    });
</script>
</body>
</html>