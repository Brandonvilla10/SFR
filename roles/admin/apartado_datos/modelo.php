<?php
session_start();
require_once('../../../database/database.php');
include('../components/alert.php');
$conexion = new database;
$con = $conexion->conectar();


$codigo = $_SESSION['documento'];
$nombre = explode(" ", $_SESSION['nombre'])[0];
$rol = $_SESSION['rol_name'];

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 6;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

$consulta_total = $con->prepare("SELECT COUNT(*) FROM modelo");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

$consulta = $con->prepare("SELECT * FROM modelo WHERE id_estado != 4 LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();



if (isset($_POST["eliminarSubmir"])) {

    $identificadorModelo = $_POST['identificador'];
    $passwordAdmin = $_POST['passwordAdmin'];

    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);



    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {

        $marcaDesactivar = $con->prepare("UPDATE modelo SET id_estado = 4 WHERE id_modelo = :identificador");
        $marcaDesactivar->bindParam(':identificador', $identificadorModelo);
        $marcaDesactivar->execute();


        if ($marcaDesactivar->rowCount() == 0) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'Modelo No Encontrado', 'modelo.php');
               });
           </script>";
        } else {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('success', 'Modelo Eliminado', 'modelo.php');
               });
           </script>";
        }
    } else {

        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Contraseña Mal Digitada',  'modelo.php' );
           });
       </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../assets/img/LogoFR.png">
    <title>Programas</title>
    <link rel="stylesheet" href="css/marcas.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../components/modaEliminar.css">

</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="titulo">
                <div>
                    <h1>Modelos de vehiculos</h1>
                    <p>Administra todos los modelos segun lo que necesites</p>
                </div>
                <button class="buscar_objeto_titulo" id="abrirEliminar">Eliminar Nivel</button>
                <button class="buscar_objeto_titulo">Crear modelo</button>
            </div>

            <hr class="separador">
            <div class="contenido_tabla">
                <input class="BOTON_FILTRAR" placeholder="Filtrar por modelo ">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay modelos registrados</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>identificador</h4>
                        <h4>Modelo</h4>
                        <h4>Accion</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['id_modelo']; ?></h4>
                            <h4><?php echo $filas['fecha_modelo']; ?></h4>
                            <h4><ion-icon name="create-outline" size="large"></ion-icon></h4>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="paginacion">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="pag-btn">&laquo; Anterior</a>
                    <?php endif; ?>

                    <?php
                    $start_page = max(1, $page - 1);
                    $end_page = min($paginas, $page + 1);

                    if ($start_page == 1 && $paginas >= 3) {
                        $end_page = 3;
                    }

                    if ($end_page == $paginas && $paginas >= 3) {
                        $start_page = max(1, $paginas - 2);
                    }

                    for ($i = $start_page; $i <= $end_page; $i++):
                    ?>
                        <a href="?page=<?php echo $i; ?>" class="pag-btn <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $paginas): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="pag-btn">Siguiente &raquo;</a>
                    <?php endif; ?>
                </div>

  
            </div>

            <?php include("../components/modaEliminar.php"); ?>

            <script>
                const abrirEliminar = document.getElementById('abrirEliminar');

                document.addEventListener('DOMContentLoaded', function() {
                    abrirEliminar.addEventListener('click', function() {
                        abrir("Identificador Del Nivel De Programa", 2, "Digite El Identificador Del Nivel De Programa", "Eliminar Nivel");
                    });
                });
            </script>
            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>