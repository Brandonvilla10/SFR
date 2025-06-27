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

$consulta_total = $con->prepare("SELECT COUNT(*) FROM nivelformacion WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

$consulta = $con->prepare("SELECT * FROM nivelformacion WHERE id_estado != 4 LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();



if (isset($_POST["eliminarSubmir"])) {

    $identificadorNivel = $_POST['identificador'];
    $passwordAdmin = $_POST['passwordAdmin'];

    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);



    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {

        $marcaDesactivar = $con->prepare("UPDATE nivelformacion SET id_estado = 4 WHERE id_nivelFormacion = :identificador");
        $marcaDesactivar->bindParam(':identificador', $identificadorNivel);
        $marcaDesactivar->execute();


        if ($marcaDesactivar->rowCount() == 0) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'Nivel No Encontrado', 'nivel.php');
               });
           </script>";
        } else {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('success', 'Nivel Eliminado', 'nivel.php');
               });
           </script>";
        }
    } else {

        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Contraseña Mal Digitada',  'nivel.php' );
           });
       </script>";
    }
}
///////////////////////////////////////////// AGG NIVEL /////////////////////////////////////////////
    
if (isset($_POST['submitnivel'])) {
    $nivel = ucwords(strtolower($_POST['nivel']));

    $check_nivel = $con->prepare("SELECT nivelDeformacion FROM nivelformacion WHERE nivelDeformacion = :nivel");
    $check_nivel->bindParam(':nivel', $nivel);
    $check_nivel->execute();
    $existe_nivel = $check_nivel->fetch(PDO::FETCH_ASSOC);

    if ($existe_nivel) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Nivel de formacion ya existente','nivel.php');
            });
        </script>";
        exit();
    } else {
        $consultaNivel = $con->prepare("INSERT INTO nivelformacion (nivelDeFormacion,id_estado) VALUES (:nivel,1)");
        $consultaNivel->bindParam(':nivel', $nivel);
        $consultaNivel->execute();
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Nivel de formacion agregado exitosamente','nivel.php');
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
    <title>Nivel Formacion</title>
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
                    <h1>Niveles de formacion</h1>
                    <p>Administra todos los niveles segun lo requieras</p>
                </div>
                <button class="buscar_objeto_titulo" id="abrirEliminar">Eliminar Nivel</button>
                <button class="buscar_objeto_titulo" id="abrirAgregar">Crear Nivel de Formacion</button>
            </div>

            <hr class="separador">
            <div class="contenido_tabla" id="contenido">
                <input class="BOTON_FILTRAR" placeholder="Filtrar por nivel de formacion" oninput="filtrarPorNivel(this.value)">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay niveles registrados</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>id</h4>
                        <h4>Nivel de Formacion</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['id_nivelFormacion']; ?></h4>
                            <h4><?php echo $filas['nivelDeformacion']; ?></h4>
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
                        <h3>Agregar Nivel De Formacion</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="" method="post">
                            <div class="campo-formulario">
                                <label for="nivel">Nivel de formacion</label>
                                <input type="text" id="nivel" name="nivel" placeholder="Digite el nivel de formacion" require>
                            </div>
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitnivel" class="buscar-btn">Crear</button>
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
            <script>
                const abrirEliminar = document.getElementById('abrirEliminar');

                document.addEventListener('DOMContentLoaded', function() {
                    abrirEliminar.addEventListener('click', function() {
                        abrir("Identificador Del Nivel De Programa", 2, "Digite El Identificador Del Nivel De Programa", "Eliminar Nivel");
                    });
                });



                //<------------------------ FUNCION PARA LA BARRA DE FILTRADO ----------------->
                const contenido = document.getElementById('contenido');

                function filtrarPorNivel(global) {

                    const sololetras = global.replace(/[^a-zA-Z]/g, '');
                    const vehiculos = document.querySelectorAll('.vehiculos');
                    // Converti todo a minusculas 
                    const general = sololetras.toLowerCase();


                    let consulta = `SELECT * FROM nivelformacion WHERE id_estado != 4 and nivelDeformacion LIKE '%${general}%' LIMIT 5`;

                    fetch('../fetch/busquedaBarra.php', {
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
                                    <h4>${generales.id_nivelFormacion}</h4>
                                    <h4>${generales.nivelDeformacion}</h4>
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