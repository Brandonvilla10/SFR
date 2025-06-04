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
            $consulta = "SELECT * FROM vehiculos 
            INNER JOIN usuario ON vehiculos.id_documento = usuario.id_documento 
            INNER JOIN estado ON vehiculos.id_estado = estado.id_estado
            INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
            INNER JOIN modelo ON vehiculos.id_modelo = modelo.id_modelo
            INNER JOIN tipovehiculo ON vehiculos.id_tipoVehiculo = tipovehiculo.id_tipoVehiculo
            WHERE vehiculos.id_documento = :id_documento";
            $datos = $con->prepare($consulta);
            $datos->bindParam(':id_documento', $array['id_documento']);
            $datos->execute();
        } else {
            // Consulta todos los vehiculos con estado 10 (sin filtro por documento)
            $datos = $con->prepare("SELECT * FROM vehiculos 
            INNER JOIN usuario ON vehiculos.id_documento = usuario.id_documento
            INNER JOIN estado ON vehiculos.id_estado = estado.id_estado
            INNER JOIN modelo ON vehiculos.id_modelo = modelo.id_modelo
            INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
            INNER JOIN tipovehiculo ON vehiculos.id_tipoVehiculo = tipovehiculo.id_tipoVehiculo");
            $datos->execute();
        }
    } else if (!empty($array)) {
        // Si hay otros filtros personalizados
        $campos = [];
        foreach ($array as $key => $value) {
            // Genera condiciones dinámicas tipo: vehiculos.campo = :campo
            $campos[] = "vehiculos.$key = :$key";
        }
        // Arma la consulta con INNER JOIN y condiciones dinámicas
        $consulta = "SELECT * FROM vehiculos 
        INNER JOIN usuario ON vehiculos.id_documento = usuario.id_documento 
        INNER JOIN estado ON vehiculos.id_estado = estado.id_estado
        INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
        INNER JOIN modelo ON vehiculos.id_modelo = modelo.id_modelo
        INNER JOIN tipovehiculo ON vehiculos.id_tipoVehiculo = tipovehiculo.id_tipoVehiculo
        WHERE " . implode(" AND ", $campos);
        $datos = $con->prepare($consulta);
        // Ejecuta pasando todos los valores del array como parámetros
        $datos->execute($array);
    } else {
        // Si no se pasa ningún filtro, consulta todos los vehiculos con sus respectivos usuarios
        $datos = $con->prepare("SELECT * FROM vehiculos 
        INNER JOIN usuario ON vehiculos.id_documento = usuario.id_documento
        INNER JOIN estado ON vehiculos.id_estado = estado.id_estado
        INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
        INNER JOIN modelo ON vehiculos.id_modelo = modelo.id_modelo
        INNER JOIN tipovehiculo ON vehiculos.id_tipoVehiculo = tipovehiculo.id_tipoVehiculo
        ");
        $datos->execute();
    }

    // Retorna todos los resultados como array 
    return $datos->fetchAll(PDO::FETCH_ASSOC);
}



if (isset($_POST["crearExcel"])) {


    if (isset($_POST["opcionDeDescarga"]) && $_POST["opcionDeDescarga"] == 1) {

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
        $valorConsulta = consultaDatosExcel($con, "SELECT * FROM vehiculos", $array);

        // Si se obtienen resultados, se preparan para exportarlos a un archivo CSV
        if (!empty($valorConsulta)) {
            // Columnas que tendrá el archivo CSV
            $columns = ['Placa Del Vehiculo', 'Caracteristica', 'Documento Del Propietario', 'Nombre Del Propietario', 'Estado Del Vehiculo', 'Marca', 'Modelo', 'Tipo de Vehiculo'];
            $data = [];

            // Recorre cada fila del resultado y prepara los datos con nombres más legibles
            foreach ($valorConsulta as $fila) {
                $data[] = [
                    'Placa Del Vehiculo' => $fila['id_placa'],
                    'Caracteristica' => $fila['caracteristicas'],
                    'Documento Del Propietario' => $fila['id_documento'],
                    'Nombre Del Propietario' => $fila['nombre_completo'],
                    'Estado Del Vehiculo' => $fila['estado'],
                    'Marca' => $fila['nombre_marca'],
                    'Modelo' => $fila['fecha_modelo'],
                    'Tipo de Vehiculo' => $fila['tipoVehiculo'],
                ];
            }

            // Llama a la función que genera el archivo CSV con los datos y columnas
            crearCSV($columns, $data, 'Vehiculos.csv');
        }
    } else if (isset($_POST["opcionDeDescarga"]) && $_POST["opcionDeDescarga"] == 2) {
    }
}
include('../../includes/sesion_validar.php');
include 'components/alert.php';

$codigo = $_SESSION['documento'];
$nombre = explode(" ", $_SESSION['nombre'])[0];
$rol = $_SESSION['rol_name'];

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM vehiculos WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare(
    "SELECT  v.foto AS veh_pic, v.id_placa, e.estado,u.id_documento, u.nombre_completo FROM vehiculos v
        INNER JOIN usuario  u ON u.id_documento = v.id_documento
        INNER JOIN estado e ON e.id_estado = v.id_estado WHERE v.id_estado != 4
        LIMIT :start, :limit"
);
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();


if (isset($_POST["objetos_submit"])) {
    $documento = $_POST["user_id"];


    $_SESSION['user_id'] = $documento;

    echo "<script>    window.location.href = 'vehiculos_usuarios.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>Vehiculos</title>
    <link rel="stylesheet" href="css/vehiculos.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./components/modalBuscar.css">

</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="titulo">
                <div>
                    <h1>Vehiculos</h1>
                    <p>Administra los vehiculos segun tu necesidad</p>
                </div>

                <button class="buscar_objeto_titulo" id="modalExcel">Generar Registros</button>
                <button class="buscar_objeto_titulo" onclick="plantilla2()">Agregar</button>

                <button class="buscar_objeto_titulo" id="abrirBuscar">Buscar Vehiculos</button>



            </div>

            <!-- -----------------------------------modal para exportarExcel------------------------->

            <div id="modal-generarexel" class="modal-generarexel">
                <div class="modal-generarexel-content">
                    <button class="modal-generarexel-close cerrarModalBuscar" id="cerrarModalExcel">&times;</button>
                    <h2 class="modal-generarexel-title">Generar archivos</h2>
                    <form method="post" class="form-generarexel">
                        <label for="fechaFin-generarexel" class="label-generarexel">Documento del Propietario (No obligatorio):</label>
                        <input type="number" id="fechaFin-generarexel" name="documentoUsuario" placeholder="Documento..." class="input-generarexel">
                        <label for="tipoReporte-generarexel" class="label-generarexel">Tipo de reporte:</label>
                        <select id="tipoReporte-generarexel" name="tipoReporte" class="select-generarexel" required>
                            <option value="">Elija Un Tipo De Reporte...</option>
                            <option value="10">Todos Los Vehiculos</option>
                            <option value="4">Vehiculos eliminados</option>
                            <option value="1">Vehiculos fuera</option>
                            <option value="2">Vehiculos inactivos</option>
                        </select>

                        <label for="opcionDeDescarga" class="label-generarexel">Tipo De Archivo</label>
                        <select name="opcionDeDescarga" id="opcionDeDescarga" class="select-generarexel">
                            <option value="3">Tipo De Archivo</option>
                            <option value="1">Generar Archivo Excel</option>
                            <option value="2">Generar Archivo PDF</option>
                        </select>

                        <button type="submit" name="crearExcel" class="btn-generarexel-submit">Generar Archivo</button>
                    </form>
                </div>
            </div>



            <!-- formulario para buscar -->
            <div class="modalBuscar" id="modalBuscar">
                <div class="contenedorModalBuscar">
                    <div class="headerModalBuscar">
                        <h3>Buscar Vehiculo</h3>
                        <button class="cerrarModalBuscar" id="cerrarModalBuscar">&times;</button>
                    </div>
                    <div class="cuerpoModalBuscar">
                        <form method="post">
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


            <hr class="separador">
            <div class="contenido_tabla" id="contenido">
                <input class="BOTON_FILTRAR" placeholder="Filtrar vehiculo por placa" oninput="filtrarPorPlaca(this.value)" id="filtro">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay vehiculos registrados</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>Vehiculo</h4>
                        <h4>Placa</h4>
                        <h4>DNI Dueño</h4>
                        <h4>Nombre Propietario</h4>
                        <h4>Estado</h4>
                        <h4>Accion</h4>
                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><img src="../../uploads/<?php echo $filas['veh_pic'] ?>" alt="img" height="60px" width="60px" onerror="this.src='../../uploads/default_vehiculos.png'"></h4>
                            <h4><?php echo htmlentities($filas['id_placa']); ?></h4>
                            <h4><?php echo $filas['id_documento']; ?></h4>
                            <h4><?php echo $filas['nombre_completo']; ?></h4>
                            <h4><?php echo $filas['estado']; ?></h4>
                            <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='editar_vehiculo.php?placa=<?php echo $filas['id_placa']; ?>'"></ion-icon></h4>
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




            <div class="buscar_user estadoDevehiculos" id="estado-vehiculos">
                <button class="vehiculos_cerrar_button" id="close-vehiculos" onclick="retirarPlantilla()"><i class="bi bi-x-lg"></i></button> <!-- ESTE ES EL TEXTO -->
                <div class="img_fondo">

                    <form action="vehiculos_usuarios.php" class="inside_img_fondo" method="post">
                        <h1>Identificar Vehiculo de Usuario</h1>

                        <p>Digite el documento del usuario</p>

                        <input type="number" name="user_id" required class="vehiculos_input">

                        <input type="submit" value="Continuar" class="vehiculos_submit" name="vehiculos_submit">
                    </form>
                </div>
            </div>

            <div class="buscar_user estadoDevehiculos" id="estado-vehiculos2">
                <button class="vehiculos_cerrar_button" id="close-vehiculos2" onclick="retirarPlantilla2()"><i class="bi bi-x-lg"></i></button>
                <div class="img_fondo">
                    <form class="inside_img_fondo" method="post">
                        <h1>¿Que deseas agregar?</h1>

                        <input type="button" value="Vehiculo" class="vehiculos_submit" onclick="window.location.href='aggVehiculo.php'">

                        <input type="button" value="Marca" class="vehiculos_submit" onclick="window.location.href='apartado_datos/marcas.php'">

                        <input type="button" value="Modelo" class="vehiculos_submit" onclick="window.location.href='apartado_datos/modelo.php'">
                    </form>
                </div>
            </div>

            <!-- Formulario para agregar marca -->
            <div class="buscar_user estadoDevehiculos" id="formulario-marca">
                <button class="vehiculos_cerrar_button" onclick="cerrarFormularioVehiculo()"><i class="bi bi-x-lg"></i></button>
                <div class="img_fondo">
                    <form action="" class="inside_img_fondo" method="post" enctype="multipart/form-data">
                        <h1>Registrar Marca</h1>

                        <input type="text" name="marca" required class="vehiculos_input" placeholder="Nombre de la marca">

                        <div style="display: flex; justify-content: space-between; width: 100%; margin-top: 15px;">
                            <input type="button" value="Volver" class="vehiculos_submit" style="background-color: #63AEE8" onclick="volverAOpciones()">
                            <input type="submit" value="Registrar" class="vehiculos_submit" name="submitmarca">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulario para agregar modelo -->
            <div class="buscar_user estadoDevehiculos" id="formulario-modelo">
                <button class="vehiculos_cerrar_button" onclick="cerrarFormularioModelo()"><i class="bi bi-x-lg"></i></button>
                <div class="img_fondo">
                    <form action="" class="inside_img_fondo" method="post">
                        <h1>Registrar Modelo</h1>

                        <select name="nombre_modelo" required class="vehiculos_input">
                            <option value="">Seleccione el año del modelo</option>
                            <?php
                            $current_year = date('Y');
                            for ($year = $current_year; $year >= 1800; $year--) {
                                echo "<option value='$year'>$year</option>";
                            }
                            ?>
                        </select>

                        <div style="display: flex; justify-content: space-between; width: 100%; margin-top: 15px;">
                            <input type="button" value="Volver" class="vehiculos_submit" style="background-color: #63AEE8" onclick="volverAOpciones()">
                            <input type="submit" value="Registrar" class="vehiculos_submit" name="modelo">
                        </div>
                    </form>
                </div>
            </div>



            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

            <script>
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

                const user_id = document.getElementById("user_id")

                user_id.addEventListener("input", () => {
                    if (user_id.value.length > 10) {
                        user_id.value = user_id.value.substring(0, 10)
                    }
                })
            </script>

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

                // <------------------------------------------- Hasta aqui va el codigo de abrir los modales ------------------------------->

                const contenido = document.getElementById('contenido');

                function filtrarPorPlaca(placa) {
                    const vehiculos = document.querySelectorAll('.vehiculos');
                    // Converti todo en minusculas porque si no no funciona buscando en minusculas
                    const placa_buscada = placa.toLowerCase();

                    const filtro = document.getElementById('filtro');

                    filtro.addEventListener('input', () => {
                        if (filtro.value.length > 10) {
                            filtro.value = filtro.value.substring(0, 10)
                        }
                    })


                    let consulta = `SELECT  v.foto AS veh_pic, v.id_placa, e.estado,u.id_documento, u.nombre_completo FROM vehiculos v
                    INNER JOIN usuario  u ON u.id_documento = v.id_documento
                    INNER JOIN estado e ON e.id_estado = v.id_estado
                    WHERE v.id_estado != 4 and v.id_placa LIKE '%${placa_buscada}%' LIMIT 5`;


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
                                    const imagen = generales.veh_pic ? generales.veh_pic : 'default_vehiculos.png';
                                    cartas.innerHTML = ` 
                                    <h4><img src='../../uploads/${imagen}' alt="img" height="60px" width="60px" onerror="this.src='../../uploads/default_vehiculos.png'"></h4>
                                    <h4>${generales.id_placa}</h4>
                                    <h4>${generales.id_documento}</h4>
                                    <h4>${generales.nombre_completo}</h4>
                                    <h4>${generales.estado}</h4>
                                    <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='editar_vehiculo.php?placa=${generales.id_placa}'"></ion-icon></h4>
                            
                                    `;

                                    const paginacion = document.getElementById('paginacion');
                                    contenido.insertBefore(cartas, paginacion);
                                })

                            }
                        });


                }
            </script>
</body>

</html>