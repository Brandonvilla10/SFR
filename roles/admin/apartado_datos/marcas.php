<?php
session_start();
require_once('../../../database/database.php');
include('../components/alert.php');
$conexion = new database;
$con = $conexion->conectar();

$codigo = $_SESSION['documento'];
$nombre = explode(" ", $_SESSION['nombre'])[0];
$rol = $_SESSION['rol_name'];

//agg marca
if (isset($_POST['submitmarca'])) {
    $nom_marca = strtoupper($_POST['marca']);
    $tipo_marca = 1;

    // Verificar si la marca ya existe
    $check_marca = $con->prepare("SELECT nombre_marca FROM marca WHERE nombre_marca = :nombre_marca");
    $check_marca->bindParam(':nombre_marca', $nom_marca);
    $check_marca->execute();
    $existe_marca = $check_marca->fetch(PDO::FETCH_ASSOC);

    if ($existe_marca) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'la marca ya existe en el sistema','vehiculos.php');
            });
        </script>";
        exit();
    } else {
        $marca = $con->prepare("INSERT INTO marca (nombre_marca,id_tipo_marca) VALUES (:nombre_marca, :id_tipo_marca)");
        $marca->bindParam(':nombre_marca', $nom_marca);
        $marca->bindParam(':id_tipo_marca', $tipo_marca);

        if ($marca->execute()) {
            echo "<script>alert('Marca agregada correctamente');
            window.location.href = 'vehiculos.php';</script>";
        }
    }
}

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM vehiculos");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT * FROM marca
    LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../assets/img/LogoFR.png">
    <title>MARCAS</title>
    <link rel="stylesheet" href="css/marcas.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="titulo">
                <div>
                    <h1>Marcas</h1>
                    <p>Administra las marcas segun tu necesidad</p>
                </div>
                <button class="buscar_objeto_titulo">Crear Marca</button>


            </div>

            <hr class="separador">
            <div class="contenido_tabla">
                <input class="BOTON_FILTRAR" placeholder="Filtrar vehiculo por placa" oninput="filtrarPorPlaca(this.value)">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay marcas registradas</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>ID</h4>
                        <h4>Nombre Marca</h4>
                        <h4>Accion</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['id_marca']; ?></h4>
                            <h4><?php echo $filas['nombre_marca']; ?></h4>
                            <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='editar_vehiculo.php?placa=<?php echo $filas['id_placa']; ?>'"></ion-icon></h4>
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

            <button class="footer-button animate-button" onclick="window.location.href='../datos.php'"> Regresar</button>



        </main>
    </div>

    <script>
        const plant = document.getElementById('estado-vehiculos');
        const plant2 = document.getElementById('estado-vehiculos2');
        const formMarca = document.getElementById('formulario-marca');
        const formModelo = document.getElementById('formulario-modelo');

        function plantilla() {
            plant.classList.remove('estadoDevehiculos');
        }

        function retirarPlantilla() {
            plant.classList.add('estadoDevehiculos');
        }

        function plantilla2() {
            plant2.classList.remove('estadoDevehiculos');
        }

        function retirarPlantilla2() {
            plant2.classList.add('estadoDevehiculos');
        }

        function mostrarFormularioVehiculo() {
            plant2.classList.add('estadoDevehiculos');
            formMarca.classList.remove('estadoDevehiculos');
        }

        function cerrarFormularioVehiculo() {
            formMarca.classList.add('estadoDevehiculos');
        }

        function mostrarFormularioModelo() {
            plant2.classList.add('estadoDevehiculos');
            formModelo.classList.remove('estadoDevehiculos');
        }

        function cerrarFormularioModelo() {
            formModelo.classList.add('estadoDevehiculos');
        }

        //Funcion para devolver a el formulario de agregar
        function volverAOpciones() {
            formMarca.classList.add('estadoDevehiculos');
            formModelo.classList.add('estadoDevehiculos');
            plant2.classList.remove('estadoDevehiculos');
        }


        const modalEliminar = document.getElementById('modal-eliminar');

        function mostrarEliminar(placa) {
            document.getElementById('placa-eliminar').textContent = placa;
            document.getElementById('id-placa-eliminar').value = placa;
            modalEliminar.classList.remove('estadoDevehiculos');
        }




        function cerrarEliminar() {
            modalEliminar.classList.add('estadoDevehiculos');
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        function filtrarPorPlaca(placa) {

            const soloLetras = placa.replace(/[^a-zA-Z]/g, '');
            const vehiculos = document.querySelectorAll('.vehiculos');
            // Converti todo a minusculas 
            const placa_buscada = soloLetras.toLowerCase();

            vehiculos.forEach(vehiculo => {
                const celdaPlaca = vehiculo.querySelectorAll('h4')[0];

                if (celdaPlaca) {
                    const textoPlaca = celdaPlaca.textContent || celdaPlaca.innerText;
                    const letrasPlaca = textoPlaca.replace(/[^a-zA-Z]/g, '').toLowerCase();

                    if (letrasPlaca.includes(placa_buscada)) {
                        vehiculo.style.display = '';
                    } else {
                        vehiculo.style.display = 'none';
                    }
                }
            });
        }
    </script>
</body>

</html>