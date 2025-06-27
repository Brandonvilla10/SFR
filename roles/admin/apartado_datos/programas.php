<?php
session_start();
require_once('../../../database/database.php');
include('../components/alert.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../../includes/sesion_validar_datos.php');

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 6;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

$consulta_total = $con->prepare("SELECT COUNT(*) FROM programa WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

$consulta = $con->prepare("SELECT * FROM programa WHERE id_estado != 4 LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();



if (isset($_POST["eliminarSubmir"])) {

    $identificadorPrograma = $_POST['identificador'];
    $passwordAdmin = $_POST['passwordAdmin'];

    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);



    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {

        $marcaDesactivar = $con->prepare("UPDATE Programa SET id_estado = 4 WHERE id_programaFormacion = :identificador");
        $marcaDesactivar->bindParam(':identificador', $identificadorPrograma);
        $marcaDesactivar->execute();


        if ($marcaDesactivar->rowCount() == 0) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'Programa No Encontrado', 'Programas.php');
               });
           </script>";
        } else {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('success', 'Programa Eliminado', 'Programas.php');
               });
           </script>";
        }
    } else {

        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Contraseña Mal Digitada',  'Programas.php' );
           });
       </script>";
    }
}
///////////////////////////////////////////////////////////////////// AGG PROGRAMA ////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['submitprograma'])) {
    $programa = ucwords(strtolower($_POST['programa']));

    $check_programa = $con->prepare("SELECT nombrePrograma FROM programa WHERE nombrePrograma = :nombre");
    $check_programa->bindParam(':nombre', $programa);
    $check_programa->execute();
    $existe_programa = $check_programa->fetch(PDO::FETCH_ASSOC);

    if ($existe_programa) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Programa de formacion ya existente','programas.php');
            });
        </script>";
        exit();
    } else {
        $consultaPrograma = $con->prepare("INSERT INTO programa (nomprePrograma,id_estado) VALUES (:nombre,1)");
        $consultaPrograma->bindParam(':nombre', $programa);
        $consultaPrograma->execute();
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Programa de formacion agregado exitosamente','programas.php');
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
                    <h1>Programas</h1>
                    <p>Administra todas los programas segun requieras</p>
                </div>
                <button class="buscar_objeto_titulo" id="abrirEliminar">Eliminar Programa</button>
                <button class="buscar_objeto_titulo" id="abrirAgregar">Crear programa</button>
            </div>

            <hr class="separador">
            <div class="contenido_tabla" id="contenido">
                <input class="BOTON_FILTRAR" placeholder="Filtrar por Programa" oninput="filtrarPorPrograma(this.value)">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay programas registrados</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>id</h4>
                        <h4>Programa de Formacion</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['id_programaFormacion']; ?></h4>
                            <h4><?php echo $filas['nombrePrograma']; ?></h4>
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
            </div>
            <button class="footer-button animate-button" onclick="window.location.href='../datos.php'">Regresar</button>
           
            <!--<--------------------------------------------------------- MODAL AGREGAR --------------------------------------------------->
            <div class="modalAgregar" id="modalAgregar">
                <div class="contenedormodalAgregar">
                    <div class="headermodalAgregar">
                        <h3>Agregar Programa De Formacion</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="" method="post">
                            <div class="campo-formulario">
                                <label for="programa">Programa de formacion</label>
                                <input type="text" id="programa" name="programa" placeholder="Digite el nombre del programa de formacion" require>
                            </div>
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitprograma" class="buscar-btn">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </main>
            <?php include("../components/modaEliminar.php"); ?>
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

            <?php include("../components/modaEliminar.php"); ?>

            <script>
                const abrirEliminar = document.getElementById('abrirEliminar');

                document.addEventListener('DOMContentLoaded', function() {
                    abrirEliminar.addEventListener('click', function() {
                        abrir("Identificador Del Programa De Formacion", 1, "Digite El Identificador Del Programa De Formacion", "Eliminar Programa");
                    });
                });


                //<------------------------ FUNCION PARA LA BARRA DE FILTRADO ----------------->
                const contenido = document.getElementById('contenido');

                function filtrarPorPrograma(global) {

                    const sololetras = global.replace(/[^a-zA-Z]/g, '');
                    const vehiculos = document.querySelectorAll('.vehiculos');
                    // Converti todo a minusculas 
                    const general = sololetras.toLowerCase();


                    let consulta = `SELECT * FROM programa WHERE nombrePrograma LIKE '%${general}%' LIMIT 6`;

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
                                    <h4>${generales.id_programaFormacion}</h4>
                                    <h4>${generales.nombrePrograma}</h4>
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