<?php
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

// apartir de aqui viene la logica para poder crear el excel ---------------------------------------
// recomendacion, dejar este scrip arriba, para eviar errores ya que chilla si hay espacios de mas 
include("../../excelComponente/generarExcel.php");

// Función que consulta datos desde la base de datos según los filtros que vienen en el array
function consultaDatosExcel($con, $consulta, $array)
{
    // Si el filtro 'id_estado' es 10 (caso especial)
    if (isset($array['id_estado']) && $array['id_estado'] == 10) {

        // Si también se proporciona un documento específico
        if (!empty($array['id_documento'])) {
            // Consulta con filtro por documento
            $consulta = "SELECT * FROM objetos 
            INNER JOIN usuario ON objetos.id_documento = usuario.id_documento 
            INNER JOIN estado ON objetos.id_estado = estado.id_estado
            INNER JOIN marca ON objetos.id_marca = marca.id_marca
            WHERE objetos.id_documento = :id_documento";
            $datos = $con->prepare($consulta);
            $datos->bindParam(':id_documento', $array['id_documento']);
            $datos->execute();
        } else {
            // Consulta todos los objetos con estado 10 (sin filtro por documento)
            $datos = $con->prepare("SELECT * FROM objetos 
            INNER JOIN usuario ON objetos.id_documento = usuario.id_documento
            INNER JOIN estado ON objetos.id_estado = estado.id_estado
            INNER JOIN marca ON objetos.id_marca = marca.id_marca");
            $datos->execute();
        }

    } else if (!empty($array)) {
        // Si hay otros filtros personalizados
        $campos = [];
        foreach ($array as $key => $value) {
            // Genera condiciones dinámicas tipo: objetos.campo = :campo
            $campos[] = "objetos.$key = :$key";
        }
        // Arma la consulta con INNER JOIN y condiciones dinámicas
        $consulta = "SELECT * FROM objetos 
        INNER JOIN usuario ON objetos.id_documento = usuario.id_documento 
        INNER JOIN estado ON objetos.id_estado = estado.id_estado
        INNER JOIN marca ON objetos.id_marca = marca.id_marca
        WHERE " . implode(" AND ", $campos);
        $datos = $con->prepare($consulta);
        // Ejecuta pasando todos los valores del array como parámetros
        $datos->execute($array);
    } else {
        // Si no se pasa ningún filtro, consulta todos los objetos con sus respectivos usuarios
        $datos = $con->prepare("SELECT * FROM objetos 
        INNER JOIN usuario ON objetos.id_documento = usuario.id_documento
        INNER JOIN estado ON objetos.id_estado = estado.id_estado
        INNER JOIN marca ON objetos.id_marca = marca.id_marca");
        $datos->execute();
    }

    // Retorna todos los resultados como array 
    return $datos->fetchAll(PDO::FETCH_ASSOC);
}


if (isset($_POST["crearExcel"])) {
    $array = []; // Array de filtros
    $tipoReporte = $_POST["tipoReporte"];
    $documentoUsuario = $_POST["documentoUsuario"];

    // Si se proporciona un documento de usuario, se añade como filtro
    if (!empty($documentoUsuario)) {
        $array['id_documento'] = $documentoUsuario;
    }

    // Si el tipo de reporte es 10, se interpreta como un filtro específico
    if ($tipoReporte == 10) {
        $array['id_estado'] = 10;
    } else if (!empty($tipoReporte)) {
        // Si se proporciona otro tipo de estado, también se añade como filtro
        $array['id_estado'] = $tipoReporte;
    }

    // Se llama a la función para obtener los datos según los filtros aplicados
    $valorConsulta = consultaDatosExcel($con, "SELECT * FROM objetos", $array);

    // Si se obtienen resultados, se preparan para exportarlos a un archivo CSV
    if (!empty($valorConsulta)) {
        // Columnas que tendrá el archivo CSV
        $columns = ['Serial_Del_Objeto', 'Nombre_Objeto', 'Caracteristica', 'Documento Del Propietario', 'Nombre Del Propietario', 'Estado Del Objeto', 'Marca'];
        $data = [];

        // Recorre cada fila del resultado y prepara los datos con nombres más legibles
        foreach ($valorConsulta as $fila) {
            $data[] = [
                'Serial_Del_Objeto' => $fila['id_serial'],
                'Nombre_Objeto' => $fila['nombre'],
                'Caracteristica' => $fila['caracteristica'],
                'Documento Del Propietario' => $fila['id_documento'],
                'Nombre Del Propietario' => $fila['nombre_completo'],
                'Estado Del Objeto' => $fila['estado'],
                'Marca' => $fila['nombre_marca'],
            ];
        }

        // Llama a la función que genera el archivo CSV con los datos y columnas
        crearCSV($columns, $data, 'usuarios.csv');
    }
}


// -------------------------------------------------------------------------------------------------

include('components/alert.php');
include('../../includes/sesion_validar.php');

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = isset($_SESSION['rol_name']) ? $_SESSION['rol_name'] : '';


if (isset($_POST["eliminarSubmir"])) {

    $identificadorObjeto = $_POST['identificador'];
    $passwordAdmin = $_POST['passwordAdmin'];

    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);



    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {

        $marcaDesactivar = $con->prepare("UPDATE objetos SET id_estado = 4 WHERE id_serial = :identificador");
        $marcaDesactivar->bindParam(':identificador', $identificadorObjeto);
        $marcaDesactivar->execute();


        if ($marcaDesactivar->rowCount() == 0) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'Objeto No Encontrado', 'objetos_index.php');
               });
           </script>";
        } else {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('success', 'Objeto Eliminado', 'objetos_index.php');
               });
           </script>";
        }
    } else {

        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Contraseña Mal Digitada',  'objetos_index.php' );
           });
       </script>";
    }
}

// Establecer la variable @documento_generador en MySQL desde la sesión
try {
    $sql = $con->prepare("SELECT nombre_completo FROM usuario WHERE id_documento = $_SESSION[documento]");
    $sql->execute();
    $nombre = $sql->fetch(PDO::FETCH_ASSOC);
    $con->exec("SET @documento_generador = '$nombre[nombre_completo]'");

    // Verificar que la variable se haya establecido correctamente
    $check = $con->query("SELECT @documento_generador as nombre");
    $result = $check->fetch(PDO::FETCH_ASSOC);
    if (empty($result['nombre'])) {
        // Si sigue vacía, establecer un valor predeterminado
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
    echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM objetos WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT o.foto AS ob_pic, o.id_serial,o.nombre, o.id_documento, e.estado, u.nombre_completo FROM objetos o
    INNER JOIN usuario u ON u.id_documento = o.id_documento
    INNER JOIN estado  e ON e.id_estado = o.id_estado
    WHERE o.id_estado!= 4
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
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>Objetos</title>
    <link rel="stylesheet" href="css/objetos_index.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./components/modaEliminar.css">
</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="titulo">
                <div>
                    <h1>Objetos</h1>
                    <p style="color:#858585">Administra los objetos según tu necesidad</p>
                </div>



                <button class="exportarExcel" id="modalExcel">Exportar Excel</button>

                <button class="buscar_objeto_titulo" id="abrirBuscar">Buscar Objetos</button>
            </div>
            <hr class="separador">


            <!-- -----------------------------------modal para exportarExcel------------------------->

            <div id="modal-generarexel" class="modal-generarexel">
                <div class="modal-generarexel-content">
                    <button class="modal-generarexel-close cerrarModalBuscar" id="cerrarModalExcel">&times;</button>
                    <h2 class="modal-generarexel-title">Generar archivo Excel</h2>
                    <form method="post" class="form-generarexel">
                        <label for="fechaFin-generarexel" class="label-generarexel">Documento(No obligatorio):</label>
                        <input type="number" id="fechaFin-generarexel" name="documentoUsuario" placeholder="Documento..." class="input-generarexel">
                        <label for="tipoReporte-generarexel" class="label-generarexel">Tipo de reporte:</label>
                        <select id="tipoReporte-generarexel" name="tipoReporte" class="select-generarexel" required>
                            <option value="">Elija Un Tipo De Reporte...</option>
                            <option value="10">Todos</option>
                            <option value="4">Objetos eliminados</option>
                            <option value="1">Objetos fuera</option>
                            <option value="2">Objetos inactivos</option>
                        </select>

                        <button type="submit" name="crearExcel" class="btn-generarexel-submit">Generar Excel</button>
                    </form>
                </div>
            </div>


            <!-------------------------------- TABLA DE OBJETOS ------------------------------------->

            <div class="contenido_tabla" id="contenido_tabla">
                <input class="BOTON_FILTRAR" placeholder="Filtrar objeto por serial" oninput="filtrarPorSerial(this.value)" id="Filtro">
                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>SIN OBJETOS</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>Objeto</h4>
                        <h4>Serial</h4>
                        <h4>Nombre</h4>
                        <h4>DNI Dueño</h4>
                        <h4>Nombre Propietario</h4>
                        <h4>Estado</h4>
                        <h4>Accion</h4>
                    </div>
                    <?php foreach ($consulta as $generales) { ?>
                        <div class="objetos">
                            <h4><img src="../../uploads/<?php echo isset($generales['ob_pic']) && $generales['ob_pic'] !== '' ? $generales['ob_pic'] : 'default_iconos.png'; ?>" alt="img" height="60px" width="60px" onerror="this.src='../../uploads/default_iconos.png'"></h4>
                            <h4><?php echo $generales['id_serial']; ?></h4>
                            <h4><?php echo $generales['nombre']; ?></h4>
                            <h4><?php echo $generales['id_documento']; ?></h4>
                            <h4><?php echo $generales['nombre_completo']; ?></h4>
                            <h4><?php echo $generales['estado']; ?></h4>
                            <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='editar_objetos_usu.php?id=<?php echo $generales['id_serial']; ?>&owner=<?php echo $generales['id_documento']; ?>&route=objetos_index.php'"></ion-icon></h4>
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


            <!------------------------------------------------ MODAL DE BUSQUEDA DE OBJETOS ----------------------------------->


            <div class="modalBuscar" id="modalBuscar">
                <div class="contenedorModalBuscar">
                    <div class="headerModalBuscar">
                        <h3>Buscar Usuario</h3>
                        <button class="cerrarModalBuscar" id="cerrarModalBuscar">&times;</button>
                    </div>
                    <div class="cuerpoModalBuscar">
                        <form action="objetos_usuario.php" method="post">
                            <div class="campo-formulario">
                                <label for="user_id">Documento del Usuario</label>
                                <input type="number" id="user_id" name="user_id" placeholder="Digite el documento" required>
                            </div>
                            <div class="botonesModalBuscar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="objetos_submit" class="buscar-btn">Continuar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



        </main>
    </div>



    <script>
        // Modal de Excel
        const modalExcel = document.getElementById('modal-generarexel');
        const modalExcelBtn = document.getElementById('modalExcel');

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

        const cerrarModalExcel = document.getElementById('cerrarModalExcel').addEventListener('click', () => {
            modalExcel.style.visibility = 'hidden';
            modalExcel.style.opacity = '0';
        });
        // Modal de Búsqueda
        const modalBuscar = document.getElementById('modalBuscar');
        const abrirBuscar = document.getElementById('abrirBuscar');
        const cerrarModalBuscar = document.getElementById('cerrarModalBuscar');
        const cancelarBuscar = document.getElementById('cancelarBuscar');

        function cerrarBuscar() {
            modalBuscar.style.visibility = 'hidden';
            modalBuscar.style.opacity = '0';
        }

        function abrirBuscarFunction() {
            modalBuscar.style.visibility = 'visible';
            modalBuscar.style.opacity = '1';
        }

        function cerrarBuscar() {
            modalBuscar.style.visibility = 'hidden';
            modalBuscar.style.opacity = '0';
        }

        abrirBuscar.addEventListener('click', abrirBuscarFunction);
        cerrarModalBuscar.addEventListener('click', cerrarBuscar);
        cancelarBuscar.addEventListener('click', cerrarBuscar);

        const user_id = document.getElementById("user_id");

        user_id.addEventListener('input', () => {
            if (user_id.value.length > 10) {
                user_id.value = user_id.value.substring(0, 10)
            }
        })
    </script>


    <script>
        // <----------------------------- CERRAR Y ABRIR MODAL DE BUSQUEDA DE OBJETOS --------------------------->
        const plant = document.getElementById('estado-objetos');

        function plantilla() {
            plant.classList.remove('estadoDeobjetos');
        }

        function retirarPlantilla() {
            plant.classList.add('estadoDeobjetos');
        }


        //<----------------------------- FILTRO PARA BUSCAR POR EL SERIAL ---------------------------> 

        const contenido = document.getElementById('contenido_tabla');

        const Filtro = document.getElementById('Filtro');

        Filtro.addEventListener('input', () => {
            if (Filtro.value.length > 10) {
                Filtro.value = Filtro.value.substring(0, 10)
            }
        })

        function filtrarPorSerial(serial) {
            const serial_buscado = serial.toUpperCase();


            let consulta = `SELECT o.foto AS ob_pic, o.id_serial,o.nombre, o.id_documento, e.estado, u.nombre_completo FROM objetos o
            INNER JOIN usuario u ON u.id_documento = o.id_documento
            INNER JOIN estado  e ON e.id_estado = o.id_estado
            WHERE o.id_estado  != 4 and id_serial LIKE '%${serial_buscado}%' LIMIT 5`;

            fetch('fetch/BusquedaBarra.php', {
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
                        const objetos = document.querySelectorAll('.objetos');
                        objetos.forEach(objetos => {
                            objetos.remove();
                        });

                    } else {
                        const objetos = document.querySelectorAll('.objetos');
                        objetos.forEach(objetos => {
                            objetos.remove();
                        });
                        data.forEach(generales => {
                            const cartas = document.createElement('div');
                            cartas.classList.add('objetos');
                            cartas.innerHTML = '';
                            const imagen = generales.ob_pic ? generales.ob_pic : 'default_iconos.png';
                            cartas.innerHTML = ` 
                                <h4><img src='../../uploads/${imagen}' alt="img" height="60px" width="60px" onerror="this.src='../../uploads/default_iconos.png'"></h4>
                                <h4>${generales.id_serial}</h4>
                                <h4>${generales.nombre}</h4>
                                <h4>${generales.id_documento}</h4>
                                <h4>${generales.nombre_completo}</h4>
                                <h4>${generales.estado}</h4>
                                <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='editar_objetos_usu.php?id=${generales.id_serial}&owner=${generales.id_documento}&route=objetos_index.php'"></ion-icon></h4>
                            
                                `;


                            const paginacion = document.getElementById('paginacion');
                            contenido.insertBefore(cartas, paginacion);
                        })

                    }

                }).catch(error => {
                    console.error('Error:', error);
                })


        }

        //<-------------------------- fin del filtro para buscar por serial ----------------------------------->
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>

</html>