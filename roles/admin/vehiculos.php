<?php
session_start();
require_once('../../database/database.php');
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
        echo "<script>alert('La marca ya existe en el sistema');
        window.location.href = 'vehiculos.php';</script>";
    } else {
        $marca = $con->prepare("INSERT INTO marca (nombre_marca,id_tipo_marca) VALUES (:nombre_marca, :id_tipo_marca)");
        $marca->bindParam(':nombre_marca', $nom_marca);
        $marca->bindParam(':id_tipo_marca', $tipo_marca);

        if ($marca->execute()){
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
        echo "<script>alert('El modelo ya existe en el sistema');
        window.location.href = 'vehiculos.php';</script>";
    } else {
        $modelo = $con->prepare("INSERT INTO modelo (fecha_modelo) VALUES (:fecha_modelo)");
        $modelo->bindParam(':fecha_modelo', $nom_modelo);

        if ($modelo->execute()){
            echo "<script>alert('Modelo agregado correctamente');
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
$consulta = $con->prepare("SELECT *, vehiculos.foto AS veh_pic FROM vehiculos
    INNER JOIN usuario ON usuario.id_documento = vehiculos.id_documento
    INNER JOIN estado ON estado.id_estado = vehiculos.id_estado
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
    <title>VEHICULOS</title>
    <link rel="stylesheet" href="css/vehiculos.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
</head>
<body>
<div class="container_grid">
    <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="titulo">
            <div>
                <h1>Vehiculos</h1>
                <p>Administra los vehiculos segun tu necesidad</p>
            </div>
            <button class="buscar_objeto_titulo" onclick="plantilla()">Buscar Vehiculos</button>
            <button class="buscar_objeto_titulo" onclick="plantilla2()">Agregar</button>

        </div>

        <hr class="separador">
        <div class="contenido_tabla">
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

            <div class="paginacion" >
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $paginas): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
                <?php endif; ?>
            </div>
        </div>




        <div class="buscar_user estadoDevehiculos" id="estado-vehiculos">
            <button class="vehiculos_cerrar_button" id="close-vehiculos" onclick="retirarPlantilla()"><i class="bi bi-x-lg"></i></button>      <!-- ESTE ES EL TEXTO -->
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

                        <input type="button" value="Marca" class="vehiculos_submit" onclick="mostrarFormularioVehiculo()">

                        <input type="button" value="Modelo" class="vehiculos_submit" onclick="mostrarFormularioModelo()">
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
                        for($year = $current_year; $year >= 1800; $year--) {
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

        <!-- formulario para eliminar -->
                <div class="buscar_user estadoDevehiculos" id="modal-eliminar">
                    <button class="vehiculos_cerrar_button" onclick="cerrarEliminar()"><i class="bi bi-x-lg"></i></button>
                    <div class="img_fondo">
                        <form action="" class="inside_img_fondo" method="post">
                            <h2>¿Esta seguro de eliminar este vehiculo?</h2>

                            <p style="margin-top: 11px; color:black; font-size: bold;">Para eliminar el vehiculo con placa <span id="placa-eliminar"></span> ingrese su contraseña de administrador</p>

                            <input type="hidden" name="id_placa" id="id-placa-eliminar">
                            <input type="password" name="password_admin" required class="vehiculos_input" placeholder="Contraseña de administrador">

                            <div style="justify-content: center; width: 100%; margin-top: 15px;">
                                <input type="submit" value="Eliminar" class="vehiculos_submit" style="background-color: #e74c3c" name="eliminar">
                            </div>
                        </form>
                    </div>
                </div>
    </main>
</div>

<script>
    const plant = document.getElementById('estado-vehiculos');
    const plant2 = document.getElementById('estado-vehiculos2');
    const formMarca = document.getElementById('formulario-marca');
    const formModelo = document.getElementById('formulario-modelo');

    function plantilla(){
        plant.classList.remove('estadoDevehiculos');
    }
    function retirarPlantilla(){
        plant.classList.add('estadoDevehiculos');
    }

    function plantilla2(){
        plant2.classList.remove('estadoDevehiculos');
    }
    function retirarPlantilla2(){
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
    const vehiculos = document.querySelectorAll('.vehiculos');
    // Converti todo en minusculas porque si no no funciona buscando en minusculas
    const placa_buscada = placa.toLowerCase();

    vehiculos.forEach(vehiculo => {
        const celdaPlaca = vehiculo.querySelectorAll('h4')[1];

        if (celdaPlaca) {
            const textoPlaca = celdaPlaca.textContent || celdaPlaca.innerText;
            // Converti tmbn en minusculas el de la placa de las columnas
            if (textoPlaca.toLowerCase().includes(placa_buscada)) {
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