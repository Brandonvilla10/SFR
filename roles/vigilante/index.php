<?php
session_start();
include('components/alert.php');
require_once('../../database/database.php');
include('../../includes/sesion_validar.php');
$conexion = new database;
$con = $conexion->conectar();

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = isset($_SESSION['rol_name']) ? $_SESSION['rol_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../../assets/img/LogoFR.webp"> <!-- Icono de la página -->
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
        <p>Rol: Vigilante</p> <!-- Muestra el rol del usuario -->
      </div>
      <div class="home_iconos">
        <div id="notificacion-wrapper" style="position: relative; display: inline-block; cursor:pointer;">
          <i class="bi bi-bell" id="iconoNotificaciones" style="font-size: 2rem;"></i>
          <span id="contadorNotificaciones" style="
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 12px;
            display: none;
          ">0</span>
        </div>
        <button id="btnReportes" style="margin-left: 15px; padding: 8px 16px; border-radius: 5px; border: none; background: #007bff; color: #fff; cursor: pointer;">
          Reportes
        </button>
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="grid_contenido_plantilla">

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
            where usuario.id_documento = $_SESSION[documento]
            
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
  <!-- Importación de iconos -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script>
  document.getElementById('iconoNotificaciones').addEventListener('click', function() {
      window.location.href = 'notificaciones.php';
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
  actualizarContadorNotificaciones();
  setInterval(actualizarContadorNotificaciones, 30000);

  // Reportes: abrir/cerrar modal
  document.getElementById('btnReportes').onclick = function() {
    document.getElementById('modalReportes').style.display = 'flex';
  };
  document.getElementById('cerrarModalReportes').onclick = function() {
    document.getElementById('modalReportes').style.display = 'none';
  };
  document.getElementById('modalReportes').onclick = function(e) {
    if (e.target === this) this.style.display = 'none';
  };
  </script>
  <!-- Modal Reportes -->
  <div id="modalReportes" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:10px; min-width:300px; position:relative;">
      <h2>Generar Reporte PDF</h2>
        <form id="formReporte" method="POST" action="generar_reporte.php" target="_blank" style="display: flex; flex-direction: column; gap: 16px; min-width: 260px;">
          <label for="fecha_inicio" style="font-weight: 500;">Fecha inicio:</label>
          <input type="date" name="fecha_inicio" id="fecha_inicio" required style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;">

          <label for="fecha_fin" style="font-weight: 500;">Fecha fin:</label>
          <input type="date" name="fecha_fin" id="fecha_fin" required style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;">

          <label for="tabla_afectada" style="font-weight: 500;">Módulo:</label>
          <select name="tabla_afectada" id="tabla_afectada" style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;">
              <option value=""> Modulo </option>
              <option value="usuario">Usuarios</option>
              <option value="vehiculos">Vehículos</option>
              <option value="objetos">Objetos</option>
          </select>

          <div style="display: flex; gap: 12px; margin-top: 10px; justify-content: flex-end;">
            <button type="submit" style="background:#007bff; color:#fff; border:none; padding:8px 16px; border-radius:5px;">Descargar PDF</button>
            <button type="button" id="cerrarModalReportes" style="background:#007bff; color:#fff; border:none; padding:8px 16px; border-radius:5px;">Cancelar</button>
          </div>
        </form>
    </div>
  </div>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('btnReportes').onclick = function() {
      document.getElementById('modalReportes').style.display = 'flex';
    };
    document.getElementById('cerrarModalReportes').onclick = function() {
      document.getElementById('modalReportes').style.display = 'none';
    };
    document.getElementById('modalReportes').onclick = function(e) {
      if (e.target === this) this.style.display = 'none';
    };
  });
</script>
</body>

</html>