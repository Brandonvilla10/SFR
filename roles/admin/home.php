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
      <?php include('asideBar/asideBar.php') ?> <!-- Incluir la barra lateral desde un archivo PHP -->
    </aside>

    <!-- Encabezado de la página -->
    <header class="home_header">
      <div class="home_titulo">
        Bienvenido, <?php echo $nombre ?> 🎉 <!-- Muestra el nombre del usuario -->
        <p>Rol: administrador</p> <!-- Muestra el rol del usuario -->
      </div>
      <div class="home_iconos">
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
                <th>OBJETO</th>

              </tr>
            </thead>

            <?php
            $ultimosRegistros = $con->prepare("SELECT fechaEntrada, usuario.id_documento, usuario.nombre_completo, rol.nombre_rol, objetos.nombre FROM `registroentradaobjetos` 
            INNER JOIN objetos ON objetos.id_serial = registroentradaobjetos.id_serial
            INNER JOIN usuario on usuario.id_documento = objetos.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol
            where usuario.id_documento = 1106227450
            
            ORDER BY id_documento DESC LIMIT 5");
            $ultimosRegistros->execute();
            $filas = $ultimosRegistros->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <tbody>
              <!-- Filas de ejemplo con objetos ingresados -->
              <?php

              foreach ($filas as $fila) {
              ?>
                <tr>
                  <td><?php echo $fila['fechaEntrada'] ?></td>
                  <td><?php echo $fila['id_documento'] ?></td>
                  <td><?php echo $fila['nombre_completo'] ?></td>
                  </td>
                  <td><?php echo $fila['nombre_rol'] ?></td>
                  <td><?php echo $fila['nombre'] ?></td>

                </tr>
              <?php } ?>
              <!-- Más filas aquí -->
            </tbody>
          </table>
        </div>
      </div>
    </main>

  </div>


  <?php
  $vehiculosAcivos = $con->prepare("SELECT COUNT(*) as total FROM vehiculos WHERE id_estado = 1");
  $vehiculosAcivos->execute();
  $resultado = $vehiculosAcivos->fetch(PDO::FETCH_ASSOC);


  $vehiculosInactivos = $con->prepare("SELECT COUNT(*) as innactivos FROM vehiculos WHERE id_estado = 2");
  $vehiculosInactivos->execute();
  $resultadoInnactivo = $vehiculosInactivos->fetch(PDO::FETCH_ASSOC);


  ?>


  <?php
  // Consulta para obtener los registros de los últimos 5 días
  $ultimosCincoDias = $con->prepare("
    SELECT 
        DATE(fechaEntrada) as fecha, 
        COUNT(*) as total 
    FROM registroentradapersona 
    WHERE fechaEntrada >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)
    GROUP BY DATE(fechaEntrada) 
    ORDER BY fecha ASC
");
  $ultimosCincoDias->execute();
  $registrosPorDia = $ultimosCincoDias->fetchAll(PDO::FETCH_ASSOC);

  // Preparar arrays para las etiquetas y datos
  $etiquetas = [];
  $datos = [];

  // Obtener la fecha actual y las 4 fechas anteriores
  $fechaActual = new DateTime();
  for ($i = 4; $i >= 0; $i--) {
    $fecha = clone $fechaActual;
    $fecha->modify("-$i day");
    $fechaFormateada = $fecha->format('Y-m-d');

    // Añadir la etiqueta (puedes formatear como prefieras)
    $etiquetas[] = $fecha->format('D'); // Abreviatura del día (Lun, Mar, etc.)

    // Buscar si hay registros para esta fecha
    $encontrado = false;
    foreach ($registrosPorDia as $registro) {
      if ($registro['fecha'] == $fechaFormateada) {
        $datos[] = $registro['total'];
        $encontrado = true;
        break;
      }
    }

    // Si no hay registros para esta fecha, añadir 0
    if (!$encontrado) {
      $datos[] = 0;
    }
  }

  // Convertir los arrays a formato JSON para usar en JavaScript
  $etiquetasJSON = json_encode($etiquetas); // esto es lo que le pone mon,tue,wed,thu,fri etc
  $datosJSON = json_encode($datos); // conteo de los dias den cuanto entraron
  
  
  ?>

  <!-- Importación de iconos -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <script>
    // Gráfico de barras
    const barras = document.getElementById('barras');
    const datas = new Chart(barras, {
      type: "bar", // Tipo de gráfico: barras
      data: {
        labels: <?php echo $etiquetasJSON; ?>, // Etiquetas dinámicas de los últimos 5 días
        datasets: [{
          label: "Registros de personas por día",
          data: <?php echo $datosJSON; ?>, // Datos de usuarios activos
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
          data: [<?php echo $resultado['total'] ?>, <?php echo $resultadoInnactivo['innactivos'] ?>], // Datos de vehículos activos e inactivos
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

    window.addEventListener('load', function() {

      const barras = document.querySelector('.grafica_barras');
      const dona = document.querySelector('.grafica_dona');
      const usuario = document.querySelector('.ingresos_usuarios ');
      const global = [barras, dona, usuario]


      global.forEach(element => {
        element.classList.add('animar');
      });

    });
  </script>
</body>

</html>