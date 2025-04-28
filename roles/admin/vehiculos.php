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

//si le da a el icono de eliminar
if (isset($_POST['eliminar'])) {
    $id_placa = $_POST['id_placa'];
    $password_admin = $_POST['password_admin'];

    $consulta = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :id_documento");
    $consulta->bindParam(':id_documento', $codigo);
    $consulta->execute();
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);


    if (password_verify($password_admin, $usuario['contraseña']) || $password_admin === $usuario['contraseña']) {

        $eliminar = $con->prepare("DELETE FROM vehiculos WHERE id_placa = :id_placa");
        $eliminar->bindParam(':id_placa', $id_placa);

        if ($eliminar->execute()) {
            echo "<script>alert('Vehiculo eliminado correctamente');
            window.location.href = 'vehiculos.php';</script>";
        } else {
            echo "<script>alert('Algo salio mal al eliminar el vehiculo');
            window.location.href = 'vehiculos.php';</script>";
        }
    } else {
        echo "<script>alert('Contraseña de administrador incorrecta');
        window.location.href = 'vehiculos.php';</script>";
    }

}

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 10;

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
                <p>Administra los vehiculos</p>
            </div>
            <button class="buscar_objeto_titulo" onclick="plantilla()">Buscar Vehiculos</button>
            <button class="buscar_objeto_titulo" onclick="plantilla2()">Agregar</button>

        </div>

        <hr class="separador">
        <div class="contenido_tabla">
            <input class="BOTON_FILTRAR" placeholder="Filtrar vehiculo por placa" oninput="filtrarPorPlaca(this.value)">
            <div class="regulador_tamaño">
                <table class="tabla_de_objetos">
                    <thead>
                        <tr>
                            <th>Vehiculo</th>
                            <th>Placa</th>
                            <th>DNI Dueño</th>
                            <th>Nombre Propietario</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($consulta->rowCount() == 0) { ?>
                            <tr>
                                <td colspan="6"><h3>No hay vehiculos</h3></td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($consulta as $filas) { ?>
                            <tr>
                                <td><img src="../../uploads/<?php echo $filas['veh_pic'] ?>" alt="img" height="60px" width="60px"></td>
                                <td><?php echo $filas['id_placa']; ?></td>
                                <td><?php echo $filas['id_documento']; ?></td>
                                <td><?php echo $filas['nombre_completo']; ?></td>
                                <td><?php echo $filas['estado']; ?></td>
                                <td>
                                    <ion-icon name="create-outline" size="large" onclick="window.location.href='editar_vehiculo.php?placa=<?php echo $filas['id_placa']; ?>'"></ion-icon>
                                    <ion-icon name="trash-outline" size="large" onclick="mostrarEliminar('<?php echo $filas['id_placa']; ?>')"></ion-icon>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

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
    const tabla = document.querySelector('.tabla_de_objetos');
    const filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    // Converti todo en minusculas porque si no no funciona buscando en minusculas
    const placa_buscada = placa.toLowerCase();

    for (let i = 0; i < filas.length; i++) {
        //  EN ESTA PARTE LO QUE HAGO ES HACER UN CICLO PARA RECORRER LAS FILAS
        const celdaPlaca = filas[i].getElementsByTagName('td')[1]; // La placa esta en la segunda columna

        if (celdaPlaca) {
            const textoPlaca = celdaPlaca.textContent || celdaPlaca.innerText;
            // Converti tmbn en minusculas el de la placa de las columnas
            if (textoPlaca.toLowerCase().includes(placa_buscada)) {
                filas[i].style.display = '';
            } else {
                filas[i].style.display = 'none';
            }
        }
    }
}
</script>
</body>
</html>