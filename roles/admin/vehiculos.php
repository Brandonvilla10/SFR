<?php
session_start();
require_once('../../database/database.php');
include 'components/alert.php';
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

//agg modelo
if (isset($_POST['modelo'])) {
    $nom_modelo = $_POST['nombre_modelo'];

    // Verificar si el modelo ya existe
    $check_modelo = $con->prepare("SELECT fecha_modelo FROM modelo WHERE fecha_modelo = :fecha_modelo");
    $check_modelo->bindParam(':fecha_modelo', $nom_modelo);
    $check_modelo->execute();
    $existe_modelo = $check_modelo->fetch(PDO::FETCH_ASSOC);

    if ($existe_modelo) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Modelo ya existente', 'vehiculos.php');
            });
        </script>";
        exit();
    } else {
        $modelo = $con->prepare("INSERT INTO modelo (fecha_modelo) VALUES (:fecha_modelo)");
        $modelo->bindParam(':fecha_modelo', $nom_modelo);

        if ($modelo->execute()) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Modelo agregado correctamente', 'vehiculos.php');
                });
            </script>";
            exit();
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
$consulta = $con->prepare(
"SELECT  v.foto AS veh_pic, v.id_placa, e.estado,u.id_documento, u.nombre_completo FROM vehiculos v
        INNER JOIN usuario  u ON u.id_documento = v.id_documento
        INNER JOIN estado e ON e.id_estado = v.id_estado WHERE v.id_estado != 4
        LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();


if(isset($_POST["objetos_submit"])) {
    $documento = $_POST["user_id"];
    
    // Store document ID in session to pass it to next page
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
                <button class="buscar_objeto_titulo" onclick="plantilla2()">Agregar</button>

                <button class="buscar_objeto_titulo" id="abrirBuscar">Buscar Vehiculos</button>



            </div>


            <!-- formulario para buscar -->
            <div class="modalBuscar" id="modalBuscar">
                <div class="contenedorModalBuscar">
                    <div class="headerModalBuscar">
                        <h3>Buscar Vehiculo</h3>
                        <button class="cerrarModalBuscar" id="cerrarModalBuscar">&times;</button>
                    </div>
                    <div class="cuerpoModalBuscar">
                        <form  method="post">
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
                <input class="BOTON_FILTRAR" placeholder="Filtrar vehiculo por placa" oninput="filtrarPorPlaca(this.value)">

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
                            <h4><?php echo $filas['id_placa']; ?></h4>
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