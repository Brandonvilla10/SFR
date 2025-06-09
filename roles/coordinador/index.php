<?php
// Validaciones e includes------------------
session_start();
include('../../excelComponente/generarExcel.php');
require_once('../../database/database.php');
include('../../includes/sesion_validar.php');
// ------------------------------------------


$conexion = new database;
$con = $conexion->conectar();

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = isset($_SESSION['rol_name']) ? $_SESSION['rol_name'] : '';



if (isset($_POST['crearExcel'])) {



  $array = [];

  $tipoReporte = $_POST['tipoReporte'];
  $fechaInicio = $_POST['fechaInicio'];
  $fechaFin = $_POST['fechaFin'];
  $filtroDeQueBuscar = isset($_POST['filtroDeQueBuscar']) ? $_POST['filtroDeQueBuscar'] : '';

if ($tipoReporte == 0) {
    if (empty($filtroDeQueBuscar)) {
        
        $sentencia = "SELECT * FROM registroentradapersona
            INNER JOIN usuario ON usuario.id_documento = registroentradapersona.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol
            INNER JOIN estado ON estado.id_estado = usuario.id_estado 
            ORDER BY fechaEntrada DESC";

        $consulta = $con->prepare($sentencia);
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

     
        $sentencia = "SELECT * FROM registroentradaobjetos
            INNER JOIN objetos ON objetos.id_serial = registroentradaobjetos.id_serial
            INNER JOIN usuario on usuario.id_documento = objetos.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol 
            ORDER BY fechaEntrada DESC";

        $consulta = $con->prepare($sentencia);
        $consulta->execute();
        $resultadoObjetos = $consulta->fetchAll(PDO::FETCH_ASSOC);

      
        $sentencia = "SELECT * FROM registroentradavehiculos
            INNER JOIN vehiculos ON vehiculos.id_placa = registroentradavehiculos.id_placa
            INNER JOIN usuario ON usuario.id_documento = vehiculos.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol
            INNER JOIN estado ON estado.id_estado = usuario.id_estado 
            ORDER BY fechaEntrada DESC";

        $consulta = $con->prepare($sentencia);
        $consulta->execute();
        $resultadoVehiculos = $consulta->fetchAll(PDO::FETCH_ASSOC);

     
        $resultado = array_merge($resultado, $resultadoObjetos, $resultadoVehiculos);

        $columnas = ['fecha de entrada', 'fecha salida', 'documento', 'nombre', 'rol', 'correo', 'placa del vehiculo', 'caracteristicas', 'objeto', 'serial'];

        $data = [];
        foreach ($resultado as $row) {
            $data[] = [
                'fecha de entrada' => $row['fechaEntrada'],
                'fecha salida' => $row['fechaSalida'],
                'documento' => $row['id_documento'],
                'nombre' => $row['nombre_completo'],
                'rol' => $row['nombre_rol'],
                'correo' => $row['correo'],
                'placa del vehiculo' => $row['id_placa'] ?? '',
                'caracteristicas' => $row['caracteristicas'] ?? '',
                'objeto' => $row['nombre'] ?? '',
                'serial' => $row['id_serial'] ?? ''
            ];
        }
    }else {

        
        $sentencia = "SELECT * FROM registroentradapersona
            INNER JOIN usuario ON usuario.id_documento = registroentradapersona.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol
            INNER JOIN estado ON estado.id_estado = usuario.id_estado
            WHERE fechaEntrada BETWEEN :fechaInicio AND :fechaFin 
            AND registroentradapersona.id_documento = :filtro 
            ORDER BY fechaEntrada DESC";
        $consulta = $con->prepare($sentencia);
        $consulta->bindParam(':fechaInicio', $fechaInicio);
        $consulta->bindParam(':fechaFin', $fechaFin); 
        $consulta->bindParam(':filtro', $filtroDeQueBuscar);
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        
        $sentencia = "SELECT * FROM registroentradaobjetos
            INNER JOIN objetos ON objetos.id_serial = registroentradaobjetos.id_serial
            INNER JOIN usuario on usuario.id_documento = objetos.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol
            WHERE fechaEntrada BETWEEN :fechaInicio AND :fechaFin 
            AND objetos.id_documento = :filtro
            ORDER BY fechaEntrada DESC";
        $consulta = $con->prepare($sentencia);
        $consulta->bindParam(':fechaInicio', $fechaInicio);
        $consulta->bindParam(':fechaFin', $fechaFin);
        $consulta->bindParam(':filtro', $filtroDeQueBuscar);
        $consulta->execute();
        $resultadoObjetos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
        
        $sentencia = "SELECT * FROM registroentradavehiculos
            INNER JOIN vehiculos ON vehiculos.id_placa = registroentradavehiculos.id_placa
            INNER JOIN usuario ON usuario.id_documento = vehiculos.id_documento
            INNER JOIN rol ON rol.id_rol = usuario.id_rol
            INNER JOIN estado ON estado.id_estado = usuario.id_estado
            WHERE fechaEntrada BETWEEN :fechaInicio AND :fechaFin 
            AND vehiculos.id_documento = :filtro
            ORDER BY fechaEntrada DESC";
        $consulta = $con->prepare($sentencia);
        $consulta->bindParam(':fechaInicio', $fechaInicio);
        $consulta->bindParam(':fechaFin', $fechaFin);
        $consulta->bindParam(':filtro', $filtroDeQueBuscar);
        $consulta->execute();
        $resultadoVehiculos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
        
        $resultado = array_merge($resultado, $resultadoObjetos, $resultadoVehiculos);

        
        $columnas = ['fecha de entrada', 'fecha salida', 'documento', 'nombre', 'rol', 'correo', 'placa del vehiculo', 'caracteristicas', 'objeto','serial'];
        $data = [];
        
        // Format data for export
        foreach ($resultado as $row) {
          $data[] = [
                'fecha de entrada' => $row['fechaEntrada'],
                'fecha salida' => $row['fechaSalida'],
                'documento' => $row['id_documento'],  
                'nombre' => $row['nombre_completo'],
                'rol' => $row['nombre_rol'],
                'correo' => $row['correo'],
                'placa del vehiculo' => $row['id_placa'] ?? '',
                'caracteristicas' => $row['caracteristicas'] ?? '',
                'objeto' => $row['nombre'] ?? '',
                'serial' => $row['id_serial'] ?? ''
            ];
        }
    }


  } 
  else if ($tipoReporte == 1) {
    if (empty($filtroDeQueBuscar)) {

      $sentencia = "SELECT * FROM registroentradapersona
        INNER JOIN usuario ON usuario.id_documento = registroentradapersona.id_documento
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        INNER JOIN estado ON estado.id_estado = usuario.id_estado
        WHERE fechaEntrada BETWEEN '$fechaInicio' AND '$fechaFin' ORDER BY fechaEntrada DESC";
    } else {
      $sentencia = "SELECT * FROM registroentradapersona
        INNER JOIN usuario ON usuario.id_documento = registroentradapersona.id_documento
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        INNER JOIN estado ON estado.id_estado = usuario.id_estado
        WHERE fechaEntrada BETWEEN '$fechaInicio' AND '$fechaFin' AND (registroentradapersona.id_documento = $filtroDeQueBuscar ) ORDER BY fechaEntrada DESC";
    }
    $consulta = $con->prepare($sentencia);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $columnas = ['fecha de entrada', 'fecha salida', 'documento', 'nombre', 'rol', 'correo', 'estado',];

    $data = [];
    foreach ($resultado as $row) {
      $data[] = [
        'fecha de entrada' => $row['fechaEntrada'],
        'fecha salida' => $row['fechaSalida'],
        'documento' => $row['id_documento'],
        'nombre' => $row['nombre_completo'],
        'rol' => $row['nombre_rol'],
        'correo' => $row['correo'],
        'estado' => $row['estado'],


      ];
    }
  } else if ($tipoReporte == 2) {
    if (empty($filtroDeQueBuscar)) {
      $sentencia = "SELECT * FROM registroentradaobjetos
        INNER JOIN objetos ON objetos.id_serial = registroentradaobjetos.id_serial
        INNER JOIN usuario on usuario.id_documento = objetos.id_documento
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        WHERE fechaEntrada BETWEEN '$fechaInicio' AND '$fechaFin' ORDER BY fechaEntrada DESC";
    } else {
      $sentencia = "SELECT * FROM registroentradaobjetos
        INNER JOIN objetos ON objetos.id_serial = registroentradaobjetos.id_serial
        INNER JOIN usuario on usuario.id_documento = objetos.id_documento
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        WHERE fechaEntrada BETWEEN '$fechaInicio' AND '$fechaFin' AND (objetos.id_documento = $filtroDeQueBuscar ) ORDER BY fechaEntrada DESC";
    }

    $consulta = $con->prepare($sentencia);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $columnas = ['fecha de entrada', 'fecha salida','objeto','serial' , 'documento', 'nombre', 'rol', 'correo', ];

    $data = [];
    foreach ($resultado as $row) {
      $data[] = [
        'fecha de entrada' => $row['fechaEntrada'],
        'fecha salida' => $row['fechaSalida'],
        'objeto' => $row['nombre'] ?? '',
        'serial' => $row['id_serial'] ?? '',
        'documento' => $row['id_documento'],
        'nombre' => $row['nombre_completo'],
        'rol' => $row['nombre_rol'],
        'correo' => $row['correo'],
        
      ];
    }
  }else if ($tipoReporte == 3) {
    if (empty($filtroDeQueBuscar)) {
      $sentencia = "SELECT * FROM registroentradavehiculos
        INNER JOIN vehiculos ON vehiculos.id_placa = registroentradavehiculos.id_placa
        INNER JOIN usuario ON usuario.id_documento = vehiculos.id_documento
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        INNER JOIN estado ON estado.id_estado = usuario.id_estado
        WHERE fechaEntrada BETWEEN '$fechaInicio' AND '$fechaFin' ORDER BY fechaEntrada DESC";
    } else {
      $sentencia = "SELECT * FROM registroentradavehiculos
        INNER JOIN vehiculos ON vehiculos.id_placa = registroentradavehiculos.id_placa
        INNER JOIN usuario ON usuario.id_documento = vehiculos.id_documento
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        INNER JOIN estado ON estado.id_estado = usuario.id_estado
        WHERE fechaEntrada BETWEEN '$fechaInicio' AND '$fechaFin' AND (vehiculos.id_documento = $filtroDeQueBuscar ) ORDER BY fechaEntrada DESC";
    }
    $consulta = $con->prepare($sentencia);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $columnas = ['fecha de entrada', 'fecha salida', 'placa del vehiculo', 'caracteristicas', 'documento', 'nombre', 'rol', 'correo', 'estado'];
    $data = [];
    foreach ($resultado as $row) {
      $data[] = [
        'fecha de entrada' => $row['fechaEntrada'],
        'fecha salida' => $row['fechaSalida'],
        'placa del vehiculo' => $row['id_placa']?? '',
        'caracteristicas' => $row['caracteristicas']?? '',
        'documento' => $row['id_documento'],
        'nombre' => $row['nombre_completo'],
        'rol' => $row['nombre_rol'],
        'correo' => $row['correo'],
        'estado' => $row['estado'], 
      ];
    }
  }




  


  generarExcelDinamico($columnas, $data, "Registros", 'excel');
}

include('components/alert.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../../assets/img/LogoFR.png">
  <title>Home</title>
  <link rel="stylesheet" href="css/home.css">
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
        <p>Rol: administrador</p>
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
        <button id="btnReportes" class="pdfBoton"> 
          <!-- reportes para pdf -->
          <img class="imgReportes" src="../../assets/pdf.png"  alt="">
        </button>
        <button id="modalExcel" class="excelBoton">
          <!-- reportes para excel -->
          <img src="../../assets/excel.png" class="imgReportes"  alt=" ">
      </button>
      </div>


    </header>



    <div id="modal-generarexel" class="modal-generarexel">
      <div class="modal-generarexel-content">
        <button class="modal-generarexel-close cerrarModalBuscar" id="cerrarModalExcel">&times;</button>
        <h2 class="modal-generarexel-title">Generar archivos</h2>
        <form method="post" class="form-generarexel">
          <label for="fechaFin-generarexel" class="label-generarexel">Buscar Por Documento, placa, serial o similares...</label>
          <input type="number" id="fechaFin-generarexel" name="filtroDeQueBuscar" placeholder="Digite Aqui..." class="input-generarexel">
          <label for="tipoReporte-generarexel" class="label-generarexel">Tipo de reporte:*</label>
          <select id="tipoReporte-generarexel" name="tipoReporte" class="select-generarexel" required>
            <option value="">Elija Un Tipo De Reporte...</option>
            <option value="0">Todos Los Datos</option>
            <option value="1">Usuarios</option>
            <option value="2">Objetos</option>
            <option value="3">Vehiculos</option>
          </select>

          <!-- <label for="opcionDeDescarga" class="label-generarexel">Tipo De Archivo</label>
          <select name="opcionDeDescarga" id="opcionDeDescarga" class="select-generarexel">
            <option value="3">Tipo De Archivo</option>
            <option value="1">Generar Archivo Excel</option>
            <option value="2">Generar Archivo PDF</option>
          </select>
          -->
          <label for="fechaInicio" class="label-generarexel">Fecha Inicio*</label>
          <input type="date" name="fechaInicio" id="fechaInicio" style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;" required>

          <label for="fechaFin" class="label-generarexel">Fecha Fin*</label>
          <input type="date" name="fechaFin" id="fechaFin" style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;" required>



          <button type="submit" name="crearExcel" class="btn-generarexel-submit">Generar Archivo</button>
        </form>
      </div>
    </div>



    <!-- Contenido principal -->
    <main class="grid_contenido_plantilla">

      <div class="graficas">
        <div class="grafica_barras"><canvas id="barras" width="200px" height="200px"></canvas></div>
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
            <tbody>
              <?php
              $ultimosRegistros = $con->prepare("SELECT fechaEntrada, usuario.id_documento, usuario.nombre_completo, rol.nombre_rol, objetos.nombre FROM `registroentradaobjetos` 
                INNER JOIN objetos ON objetos.id_serial = registroentradaobjetos.id_serial
                INNER JOIN usuario on usuario.id_documento = objetos.id_documento
                INNER JOIN rol ON rol.id_rol = usuario.id_rol
                ORDER BY fechaEntrada DESC LIMIT 5");
              $ultimosRegistros->execute();
              $filas = $ultimosRegistros->fetchAll(PDO::FETCH_ASSOC);
              foreach ($filas as $fila) {
              ?>
                <tr>
                  <td><?php echo $fila['fechaEntrada'] ?></td>
                  <td><?php echo $fila['id_documento'] ?></td>
                  <td><?php echo $fila['nombre_completo'] ?></td>
                  <td><?php echo $fila['nombre_rol'] ?></td>
                  <td><?php echo $fila['nombre'] ?></td>
                </tr>
              <?php } ?>
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

  $etiquetas = [];
  $datos = [];
  $fechaActual = new DateTime();
  for ($i = 4; $i >= 0; $i--) {
    $fecha = clone $fechaActual;
    $fecha->modify("-$i day");
    $fechaFormateada = $fecha->format('Y-m-d');
    $etiquetas[] = $fecha->format('D');
    $encontrado = false;
    foreach ($registrosPorDia as $registro) {
      if ($registro['fecha'] == $fechaFormateada) {
        $datos[] = $registro['total'];
        $encontrado = true;
        break;
      }
    }
    if (!$encontrado) {
      $datos[] = 0;
    }
  }
  $etiquetasJSON = json_encode($etiquetas);
  $datosJSON = json_encode($datos);
  ?>

  <!-- Importación de iconos -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <script>

    
  


    const modalExcel = document.getElementById('modal-generarexel');
    const modalExcelBtn = document.getElementById('modalExcel');

    modalExcelBtn.addEventListener("mouseenter", () => {
      modalExcelBtn.classList.add("afterParaBoton"); 
    });

    modalExcelBtn.addEventListener("mouseleave", () => {
      modalExcelBtn.classList.remove("afterParaBoton");
    });



    modalExcelBtn.addEventListener('click', () => {
      modalExcel.style.visibility = 'visible';
      modalExcel.style.opacity = '1';

    });
    modalExcel.addEventListener('click', (event) => {
      if (event.target === modalExcel) {
        modalExcel.style.visibility = 'hidden';
        modalExcel.style.opacity = '0';
      }
    });

    const botonpdf = document.getElementById("btnReportes");

    botonpdf.addEventListener("mouseenter", () => {
      botonpdf.classList.add("afterParaPdf");
    });

    botonpdf.addEventListener("mouseleave", () =>{
      botonpdf.classList.remove("afterParaPdf")
    })

    const cerrarModalExcel = document.getElementById('cerrarModalExcel').addEventListener('click', () => {
      modalExcel.style.visibility = 'hidden';
      modalExcel.style.opacity = '0';
    });

    // Gráfico de barras
    const barras = document.getElementById('barras');
    const datas = new Chart(barras, {
      type: "bar",
      data: {
        labels: <?php echo $etiquetasJSON; ?>,
        datasets: [{
          label: "Registros de personas por día",
          data: <?php echo $datosJSON; ?>,
          backgroundColor: 'blue',
          borderWidth: 3
        }]
      },
      options: {
        responsive: true,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });

    // Gráfico de dona
    const donita = document.getElementById('donut');
    const datos_donut = new Chart(donita, {
      type: "doughnut",
      data: {
        labels: ['vehículos activos', 'vehículos Inactivos'],
        datasets: [{
          label: 'Vehículos Activos',
          data: [<?php echo $resultado['total'] ?>, <?php echo $resultadoInnactivo['innactivos'] ?>],
          backgroundColor: ['blue', 'red'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Vehículos en el centro'
          }
        }
      }
    });

    window.addEventListener('load', function() {
      const barras = document.querySelector('.grafica_barras');
      const dona = document.querySelector('.grafica_dona');
      const usuario = document.querySelector('.ingresos_usuarios ');
      const global = [barras, dona, usuario];
      global.forEach(element => {
        element.classList.add('animar');
      });
    });

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

    document.addEventListener('DOMContentLoaded', function() {

      document.getElementById('btnReportes').onclick = function() {
        document.getElementById('modalReportes').style.display = 'flex';
      };
      document.getElementById('cerrarModalReportes').onclick = function() {
        document.getElementById('modalReportes').style.display = 'none';
      };
      document.getElementById('modalReportes').onclick = function(e) {
        if (e.target === this) this.style.display = 'none';
      };
    })
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
</body>

</html>