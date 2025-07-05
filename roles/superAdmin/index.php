<?php 
// Validaciones-------------------------------------------
session_start();

require_once('../../database/database.php');
require_once('../../includes/sesion_validar.php');

// ---------------------------------------------

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
  <title>Home</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="asideBar/asidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="container_grid" id="ocultar_contenido">

    <!-- Barra lateral -->
    <aside class="aside_barra">
      <?php include('asideBar/asideBar.php') ?>
    </aside>

    <!-- Encabezado de la página -->
    <header class="home_header">
      <div class="home_titulo">
        Bienvenido, <?php echo $nombre ?> 🎉
        <p>Rol: Super Administrador</p>
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="grid_contenido_plantilla">
      <div class="stats">
          <div class="stat-card">
            <h3 id="totalLicenses">2</h3>
            <p>Total Licencias</p>
          </div>
          <div class="stat-card">
            <h3 id="activeLicenses">1</h3>
            <p>Activas</p>
          </div>
          <div class="stat-card">
            <h3 id="expiredLicenses">1</h3>
            <p>Expiradas</p>
          </div>
      </div>
      <div class="table-container">
        <h2>Licencias Registradas</h2>
        <a href="licencias.php" class="btn-registrar">Registrar licencias</a>
        <table>
          <thead>
            <tr>
              <th>NIT Empresa</th>
              <th>Nombre Empresa</th>
              <th>Fecha Inicio</th>
              <th>Fecha Fin</th>
              <th>Tipo de Licencia</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>900123456</td>
              <td>Sena Centro de Industria y Construccion</td>
              <td>2024-01-01</td>
              <td>2025-01-01</td>
              <td>Anual</td>
              <td style="color: green;">Activa</td>
            </tr>
            <tr>
              <td>800987654</td>
              <td>Sena Centro de Comercios y Servicios</td>
              <td>2025-05-06</td>
              <td>2025-05-07</td>
              <td>Mensual</td>
              <td style="color: red;">Expirada</td>
            </tr>
          </tbody>
        </table>

      </div>
      </div>
    </main>

  </div>
  
</body>

</html>