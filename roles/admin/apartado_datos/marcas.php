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
$consulta_total = $con->prepare("SELECT COUNT(*) FROM marca WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT * FROM marca
INNER JOIN tipomarca ON marca.id_tipo_marca = tipomarca.id_tipo_marca 
WHERE id_estado  != 4
LIMIT :start, :limit ");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();


include("../components/alert.php");

if (isset($_POST["eliminarSubmir"])) {
    
    $identificador = $_POST['identificador'];
    $passwordAdmin = $_POST['passwordAdmin'];
    
    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);
    
 
    
    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {
        
        $marcaDesactivar = $con->prepare("UPDATE marca SET id_estado = 4 WHERE id_marca = :identificador");
        $marcaDesactivar->bindParam(':identificador', $identificador);
        $marcaDesactivar->execute();
        
        
            if ($marcaDesactivar->rowCount() == 0) {
                echo "
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Objeto No Encontrado', 'marcas.php');
                    });
                </script>";
            } else {
                echo "
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Objeto Eliminado', 'marcas.php');
                    });
                    </script>";
            }
    } else {
        echo "
        <script>
        document.addEventListener('DOMContentLoaded', function () {
       mostrarModal('mistake', 'Contraseña Mal Digitada',  'marcas.php' );
       });
       </script>";
    }
}


//////////////////////////////////////////////////////////// AGG MARCA ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        
if (isset($_POST['submitmarca'])) {
    $nom_marca = ucwords(strtolower($_POST['marca']));
    $tipo_marca = $_POST['tipomarca'];

    
    $check_marca = $con->prepare("SELECT nombre_marca FROM marca WHERE nombre_marca = :nombre_marca");
    $check_marca->bindParam(':nombre_marca', $nom_marca);
    $check_marca->execute();
    $existe_marca = $check_marca->fetch(PDO::FETCH_ASSOC);

    if ($existe_marca) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Marca ya existente','marcas.php');
            });
        </script>";
        exit();
    } else {
        $marca = $con->prepare("INSERT INTO marca (nombre_marca,id_tipo_marca,id_estado) VALUES (:nombre_marca, :id_tipo_marca, 1)");
        $marca->bindParam(':nombre_marca', $nom_marca);
        $marca->bindParam(':id_tipo_marca', $tipo_marca);
        $marca->execute();

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Marca agregada exitosamente','marcas.php');
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
    <title>MARCAS</title>
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
                    <h1>Marcas</h1>
                    <p>Administra las marcas segun tu necesidad</p>
                </div>
                <button type="submit" name="abrirModal" class="buscar_objeto_titulo" id="abrirEliminar">Eliminar</button>

                <button type="submit" name="abrirModal" class="buscar_objeto_titulo" id="abrirAgregar">Agregar</button>


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
                        <h4>Tipo Marca</h4>
                        
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><?php echo $filas['id_marca']; ?></h4>
                            <h4><?php echo $filas['nombre_marca']; ?></h4>
                            <h4><?php echo $filas['nombre_tipo_marca'];?></h4>
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
           
<!------------------------------------------------ MODAL DE AGREGAR MARCA ----------------------------------->
    
            <div class="modalAgregar" id="modalAgregar">
                <div class="contenedormodalAgregar">
                    <div class="headermodalAgregar">
                        <h3>Agregar Marca</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="marcas.php" method="post">
                            <div class="campo-formulario">
                                <label for="nom_marca">Nombre de la marca</label>
                                <input class="input-modal" type="text" id="nom_marca" name="marca" placeholder="Digite el nombre de la marca " required>
                            </div>
                            <div class="campo-formulario">
                                <label for="tipomarca">Tipo de marca</label>
                                <select class="input-modal" id="tipomarca" name="tipomarca" required>
                                    <option value="">Seleccione un tipo de marca</option>
                                    <option value="1">Vehiculo</option>
                                    <option value="2">Objeto</option>
                                </select>
                            </div>
                        
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitmarca" class="buscar-btn">Crear</button>
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
        const plant = document.getElementById('estado-vehiculos');
        const plant2 = document.getElementById('estado-vehiculos2');
        const formMarca = document.getElementById('formulario-marca');
        const formModelo = document.getElementById('formulario-modelo');


        const abrirEliminar = document.getElementById('abrirEliminar');

        document.addEventListener('DOMContentLoaded', function() {
            abrirEliminar.addEventListener('click', function() {
                abrir("Identificador De La Marca", 1,"Digite el identificador de la marca", "Eliminar Marca" );
            });
        });
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const encabezado = document.querySelector('.encabezado');
            if (encabezado && encabezado.querySelectorAll('h4').length === 2) {
                const contenidoTabla = document.querySelector('.contenido_tabla');
                if (contenidoTabla) {
                    contenidoTabla.style.width = '80%';
                }

            }
        });
    </script>

</body>

</html>