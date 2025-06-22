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
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM fichas WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT * FROM fichas 
NATURAL JOIN programa 
NATURAL JOIN nivelformacion 
NATURAL JOIN jornada 
 WHERE id_estado != 4   LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();

// --------------------------------- consultas para mostrar en el modal agregar -------------------------------------------------------
$consultaProgramas = $con->prepare("SELECT * FROM programa WHERE id_estado = 1 ORDER BY nombrePrograma");
$consultaProgramas->execute();
$programas = $consultaProgramas->fetchAll(PDO::FETCH_ASSOC);

$consultaNiveles = $con->prepare("SELECT * FROM nivelformacion WHERE id_estado = 1 ORDER BY nivelDeformacion");
$consultaNiveles->execute();
$niveles = $consultaNiveles->fetchAll(PDO::FETCH_ASSOC);

$consultaJornadas = $con->prepare("SELECT * FROM jornada ORDER BY jornada");
$consultaJornadas->execute();
$jornadas = $consultaJornadas->fetchAll(PDO::FETCH_ASSOC);

// --------------------------------- consultas para el modal agregar -------------------------------------------------------
if (isset($_POST["submitfichas"])) {

    $num_ficha = $_POST['num_ficha'];
    $programa = $_POST['programa'];
    $nivel = $_POST['nivel'];
    $jornada = $_POST['jornada'];
    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
    $dias_seleccionados = isset($_POST['dias']) ? $_POST['dias'] : [];
    $dias_string = implode(', ', $dias_seleccionados);

    $verificar = $con->prepare("SELECT * FROM fichas WHERE n_ficha = :ficha");
    $verificar->bindParam(':ficha', $num_ficha);
    $verificar->execute();
 
    if ($verificar->rowCount() > 0) {
        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Numero de ficha existente', 'fichas.php');
           });
       </script>";
        exit();
    }

    $consulta = $con->prepare("INSERT INTO fichas (n_ficha, dias, fecha_ini, fecha_fin ,id_programaFormacion, id_nivelFormacion, id_jornada, id_estado) VALUES (:ficha, :dias, :fecha_ini , :fecha_fin, :programa, :nivel, :jornada, 1)");
    $consulta->bindParam(':dias', $dias_string);
    $consulta->bindParam(':ficha', $num_ficha);
    $consulta->bindParam(':fecha_ini', $fecha_ini);
    $consulta->bindParam(':fecha_fin', $fecha_fin);
    $consulta->bindParam(':programa', $programa);
    $consulta->bindParam(':nivel', $nivel);
    $consulta->bindParam(':jornada', $jornada);
    $consulta->execute();
   
    echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('success', 'Ficha registrada exitosamente', 'fichas.php');
           });
       </script>";
        exit();
}
// ----------------------------------este apartado es para el modal para eliminar fichas-----------------------------------


if (isset($_POST["eliminarSubmir"])) {

    $identificadorFicha = $_POST['identificador'];
    $passwordAdmin = $_POST['passwordAdmin'];

    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1 ");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);



    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {

        $marcaDesactivar = $con->prepare("UPDATE fichas SET id_estado = 4 WHERE n_ficha = :identificador");
        $marcaDesactivar->bindValue(':identificador', $identificadorFicha);
        $marcaDesactivar->execute();


        if ($marcaDesactivar->rowCount() == 0) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'Ficha No Encontrada', 'fichas.php');
               });
           </script>";
        } else {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('success', 'Ficha Eliminada', 'fichas.php');
               });
           </script>";
        }
    } else {

        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Contraseña Mal Digitada',  'fichas.php' );
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
    <title>Fichas</title>
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
                <div class="tituloSection">
                    <h1>Fichas</h1>
                    <p>Administra todas las fichas segun tu necesidad</p>
                </div>
                <button class="buscar_objeto_titulo" id="abrirEliminar">Eliminar Ficha</button>
                <button class="buscar_objeto_titulo" id= "abrirAgregar">Crear Ficha</button>
            </div>

            <hr class="separador">
            <div class="contenido_tabla">
                <input class="BOTON_FILTRAR" placeholder="Filtrar por ficha " oninput="filtrarPorPlaca(this.value)">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay fichas registradas</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>Ficha</h4>
                        <h4>Programa de Formacion</h4>
                        <h4>Nivel de Formacion</h4>
                        <h4>Jornada de Formacion</h4>
                        <h4>Dias de formacion</h4>
                        <h4>Fecha Fin</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['n_ficha']; ?></h4>
                            <h4><?php echo $filas['nombrePrograma']; ?></h4>
                            <h4><?php echo $filas['nivelDeformacion']; ?></h4>
                            <h4><?php echo $filas['jornada']; ?></h4>
                            <h4><?php echo $filas['dias'] ?? "sin dias" ;?></h4>
                            <h4><?php echo $filas['fecha_fin'] ?? "Sin fecha Fin"; ?></h4>

                        </div>
                    <?php } ?>
                <?php }  ?>

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

     <!------------------------------------------------ MODAL DE CREAR FICHA ----------------------------------->

      
            <div class="modalAgregar" id="modalAgregar">
                <div class="contenedormodalAgregar">
                    <div class="headermodalAgregar">
                        <h3>Crear Ficha</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="fichas.php" method="post">
                            <div class="campo-formulario">
                                <label for="num_ficha">Numero de ficha</label>
                                <input class="input-modal" type="number" id="num_ficha" name="num_ficha" placeholder="Digite el numero de ficha" required>
                            </div>
                            <div class="campo-formulario">
                                <label for="programa">Programa de Formacion</label>
                                <select id="programa" name="programa" required>
                                    <option value="">Seleccione un programa</option>
                                    <?php foreach ($programas as $programa): ?>
                                        <option value="<?php echo $programa['id_programaFormacion'] ?>"><?php echo $programa['nombrePrograma']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                
                            <div class="conjuntoModal">
                                
                                <div>
                                    <label for="nivel">Nivel de Formacion</label>
                                    <select id="nivel" name="nivel" required>
                                        <option value="">Seleccione un nivel</option>
                                        <?php foreach ($niveles as $nivel): ?>
                                            <option value="<?php echo $nivel['id_nivelFormacion'] ?>"><?php echo $nivel['nivelDeformacion']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                            
                                <div >
                                    <label for="jornada">Jornada de Formacion</label>
                                    <select id="jornada" name="jornada" required>
                                        <option value="">Seleccione una jornada</option>
                                        <?php foreach ($jornadas as $jornada): ?>
                                            <option value="<?php echo $jornada['id_jornada']; ?>"><?php echo $jornada['jornada']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                            </div>

                            <div class="conjuntoModal">
                                <div>    
                                    <label for="fecha_ini">Fecha Inicio</label>
                                    <input class="input-modal" type="date" id="fecha_ini" name="fecha_ini" required>
                                </div>
                                <div>
                                    <label for="fecha_fin">Fecha Fin</label>
                                    <input class="input-modal" type="date" id="fecha_fin" name="fecha_fin"  required>
                                </div>
                            </div>

                            <div class="campo-formulario">
                                <label>Dias de Formacion</label>
                                <div class="checkbox-group">
                                    <input type="checkbox" name="dias[]" value="Lunes"> 
                                    <h5>Lunes</h5>
                                    <input type="checkbox" name="dias[]" value="Martes">
                                    <h5>Martes</h5>
                                    <input type="checkbox" name="dias[]" value="Miercoles">
                                    <h5>Miercoles</h5>
                                    <input type="checkbox" name="dias[]" value="Jueves">
                                    <h5>Jueves</h5>
                                    <input type="checkbox" name="dias[]" value="Viernes">
                                    <h5>Viernes</h5>
                                    <input type="checkbox" name="dias[]" value="Sabado">
                                    <h5>Sabado</h5>
                                </div>
                            </div>
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitfichas" class="buscar-btn">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>

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
             abrir("Identificador De La Ficha", 2 ,"Digite El Numero De Ficha", "Eliminar Ficha" );
         });
     }); 


    //  Validacion Formulario

    const fechaInicio = document.getElementById('fecha_ini');
    const fechaFin = document.getElementById('fecha_fin');
    
    fechaFin.addEventListener('blur', function() {
        if (fechaInicio.value >  fechaFin.value) {
            mostrarModal('mistake', 'Fecha Fin Mayor a la fecha inicio' );
            fechaFin.value = '';
        }
    })

 </script>
 <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

</body>

</html>