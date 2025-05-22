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

$consulta_total = $con->prepare("SELECT COUNT(*) FROM estado");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

$consulta = $con->prepare("SELECT * FROM estado LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();

//////////////////////////////////////////////////////////// AGG ESTADO ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        
if (isset($_POST['submitestado'])) {
    $nom_estado = ucwords(strtolower($_POST['estado']));
    
    $check_estado = $con->prepare("SELECT estado FROM estado WHERE estado = :nombre_estado");
    $check_estado->bindParam(':nombre_estado', $nom_estado);
    $check_estado->execute();
    $existe_estado = $check_estado->fetch(PDO::FETCH_ASSOC);

    if ($existe_estado) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Estado ya existente','estado.php');
            });
        </script>";
        exit();
    } else {
        $estado = $con->prepare("INSERT INTO estado (estado) VALUES (:nombre_estado)");
        $estado->bindParam(':nombre_estado', $nom_estado);
        $estado->execute();
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Estado agregado exitosamente','estado.php');
            });
        </script>";
        exit();
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
    <link rel="stylesheet" href="../components/modalAgregar.css">
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
                    <h1>Estados</h1>
                    <p>Administra todos las estados segun requieras</p>
                </div>
                <button class="buscar_objeto_titulo" id="abrirAgregar">Crear estado</button>
            </div>

            <hr class="separador">
            <div class="contenido_tabla" id="contenido">
                <input class="BOTON_FILTRAR" placeholder="Filtrar por estado" oninput="filtrarPorEstado(this.value)">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay estados registrados</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>ID</h4>
                        <h4>Estado</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['id_estado']; ?></h4>
                            <h4><?php echo $filas['estado']; ?></h4>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="paginacion" id="paginacion">
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
                
            <button class="footer-button animate-button" onclick="window.location.href='../datos.php'">Regresar</button>
           
            </div>

            <!--<--------------------------------------------------------- MODAL AGREGAR --------------------------------------------------->
            <div class="modalAgregar" id="modalAgregar">
                <div class="contenedormodalAgregar">
                    <div class="headermodalAgregar">
                        <h3>Agregar Estado</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="" method="post">
                            <div class="campo-formulario">
                                <label for="nom_marca">Nombre de el estado</label>
                                <input class="input-modal" type="text" id="estado" name="estado" placeholder="Digite el nombre de el estado " required>
                            </div>
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitestado" class="buscar-btn">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <script>
            const modalAgregar = document.getElementById('modalAgregar');
            const abrirBuscar = document.getElementById('abrirAgregar');
            const cerrarmodalAgregar = document.getElementById('cerrarmodalAgregar');
            const cancelarBuscar = document.getElementById('cancelarBuscar');

            function cerrarBuscar() {
                modalAgregar.style.visibility = 'hidden';
                modalAgregar.style.opacity = '0';
            }
        
            function abrirBuscarFunction() {
                modalAgregar.style.visibility = 'visible';
                modalAgregar.style.opacity = '1';
            }
        
            function cerrarBuscar(){
                modalAgregar.style.visibility = 'hidden';
                modalAgregar.style.opacity = '0';
            }
        
            abrirBuscar.addEventListener('click', abrirBuscarFunction);
            cerrarmodalAgregar.addEventListener('click', cerrarBuscar);
            cancelarBuscar.addEventListener('click', cerrarBuscar);
        </script>
            <script>
                //<------------------------ FUNCION PARA LA BARRA DE FILTRADO ----------------->
                const contenido = document.getElementById('contenido');

                function filtrarPorEstado(global) {

                    const sololetras = global.replace(/[^a-zA-Z]/g, '');
                    const vehiculos = document.querySelectorAll('.vehiculos');
                    // Converti todo a minusculas 
                    const general = sololetras.toLowerCase();


                    let consulta = `SELECT * FROM estado WHERE estado LIKE '%${general}%' LIMIT 5`;

                    fetch('../fetch/BusquedaBarra.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                consulta: consulta
                            })
                        })  
                        .then((response) => response.json())
                        .then(data => {
                            if (data.error) {
                                const vehiculos = document.querySelectorAll('.vehiculos');
                                vehiculos.forEach(vehiculo => {
                                    vehiculo.remove();
                                });
                            } else {
                                const vehiculos = document.querySelectorAll('.vehiculos');
                                vehiculos.forEach(vehiculo => {
                                    vehiculo.remove();
                                });
                                data.forEach(generales => {
                                    const cartas = document.createElement('div');
                                    cartas.classList.add('vehiculos');
                                    cartas.innerHTML = '';
                                    cartas.innerHTML = ` 
                                    <h4>${generales.id_estado}</h4>
                                    <h4>${generales.estado}</h4>
                                    `;

                                    const paginacion = document.getElementById('paginacion');
                                    contenido.insertBefore(cartas, paginacion);
                                });
                            }
                        });
                }
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const encabezado = document.querySelector('.encabezado');
                    if (encabezado && encabezado.querySelectorAll('h4').length === 2) {
                        const contenidoTabla = document.querySelector('.contenido_tabla');
                        if (contenidoTabla) {
                            contenidoTabla.style.width = '80%';
                            contenidoTabla.style.backgroundcolor = 'red';
                        }
                    
                    }
                });
            </script>
            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>