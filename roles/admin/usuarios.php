<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
include("components/alert.php");

$codigo = $_SESSION['documento'];
$nombre = explode(" ", $_SESSION['nombre'])[0];
$rol = $_SESSION['rol_name'];

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM objetos");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT u.id_documento, u.nombre_completo, u.correo, u.foto, estado.estado, rol.nombre_rol  FROM usuario u
    INNER JOIN rol ON u.id_rol = rol.id_rol
    INNER JOIN estado ON estado.id_estado = u.id_estado
    LIMIT :start, :limit");
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();


if (isset($_POST['eliminarSubmir'])) {
    $documentoUsuario = $_POST['documentoUsuario'];
    $passwordAdmin = $_POST['passwordAdmin'];

    $consultaAdmin = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :documento AND id_rol = 1");
    $consultaAdmin->bindParam(':documento', $codigo);
    $consultaAdmin->execute();
    $result = $consultaAdmin->fetch(PDO::FETCH_ASSOC);



    if ($result && $passwordAdmin && password_verify($passwordAdmin, $result['contraseña'])) {

        $usuarioDesactivar = $con->prepare("UPDATE usuario SET id_estado = 4 WHERE id_documento = :documento");
        $usuarioDesactivar->bindParam(':documento', $documentoUsuario);
        $usuarioDesactivar->execute();


        if ($usuarioDesactivar->rowCount() == 0) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'Usuario No Encontrado', 'usuarios.php');
               });
           </script>";
        } else {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('success', 'Usuario Eliminado', 'usuarios.php');
               });
           </script>";
        }
    } else {

        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Contraseña Mal Digitada', 'usuarios.php');
           });
       </script>";
    }
}

if (isset($_POST["agregarSubmit"])) {

    $id_documento = $_POST['id_documento'];
    $nombre_completo = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $id_rol = $_POST['id_rol'];

    $hasPassword = password_hash($contraseña, PASSWORD_DEFAULT);

    $nuevoUser = $con->prepare("INSERT INTO usuario (id_documento, nombre_completo, correo, contraseña, id_estado,id_rol) VALUES (:id_documento, :nombre_completo, :correo, :contrasena,1, :id_rol)");
    $nuevoUser->bindParam(':id_documento', $id_documento);
    $nuevoUser->bindParam(':nombre_completo', $nombre_completo);
    $nuevoUser->bindParam(':correo', $correo);
    $nuevoUser->bindParam(':contrasena', $hasPassword);
    $nuevoUser->bindParam(':id_rol', $id_rol);
    $nuevoUser->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/objetos_index.css">
    <link rel="stylesheet" href="css/usuario.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
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
                    <h1>Usuarios</h1>
                    <p style="color:#858585">Administra los usuarios segun tu necesidad</p>
                </div>


                <div class="EliminarUsuarioDiv">
                    <button class="buscar_objeto_titulo" id="botonEliminar" type="button">
                        <p id="textoEliminar"></p>Eliminar Usuario
                    </button>
                </div>

                <div class="AgregarUsuarioDiv">
                    <button class="buscar_objeto_titulo" id="botonAgregar" type="button">
                        <p id="textoAbrir">+</p>Agregar Usuario
                    </button>
                </div>
                <!-- el modal empieza a partid de aca -->
            </div>


            <!-- Modal para Eliminar Usuario -->
            <div class="modalEliminar" id="modalEliminar">
                <div class="contenedorModalEliminar">

                    <div class="headerModalEliminar">
                        <h3>Verificación de Administrador</h3>
                        <button class="cerrarModalEliminar" id="cerrarModalEliminar">&times; </button>
                    </div> <!-- fin del heeader para eliminar usuario -->

                    <div class="cuerpoModalEliminar">

                        <form id="formEliminarUsuario" method="post"> <!-- aqui inicia el formulario pues para hacer todo el proceso -->

                            <div class="campo-formulario">
                                <label for="documentoUsuario">Documento del Usuario a Eliminar</label>
                                <input type="text" id="documentoUsuario" name="documentoUsuario" placeholder="Ingrese el documento" required>
                            </div>

                            <div class="campo-formulario">
                                <label for="passwordAdmin">Contraseña de Administrador.</label>
                                <div class="password-container">
                                    <input type="password" id="passwordAdmin" name="passwordAdmin" placeholder="Ingrese su contraseña" required>
                                    <button type="button" class="toggle-password" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="botonesModalEliminar">
                                <button type="button" class="cancelar-btn" id="cancelarEliminar">Cancelar</button>
                                <button type="submit" name="eliminarSubmir" class="eliminar-btn">Eliminar Usuario</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div> <!-- fin del container main del modal eliminar usuario -->

            <!------------------------------ fin del modal para eliminar usuario ------------------------------------ -->



            <!-- inicio del modal para agregar usuario -->


            <div class="modalAgregar" id="modalAgregar">
                <div class="contenedorModalAgregar">
                    <div class="headerModalAgregar">
                        <h3>Agregar Nuevo Usuario</h3>
                        <button class="cerrarModalAgregar" id="cerrarModalAgregar">&times;</button>
                    </div>
                    <div class="cuerpoModalAgregar">
                        <form id="formAgregarUsuario" method="post">
                            <div class="campo-formulario">
                                <label for="id_documento">Documento</label>
                                <input type="number" id="id_documento" name="id_documento" placeholder="Ingrese el documento" required>
                            </div>
                            <div class="campo-formulario">
                                <label for="nombre_completo">Nombre Completo</label>
                                <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ingrese el nombre completo" required>
                            </div>
                            <div class="campo-formulario">
                                <label for="correo">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" placeholder="Ingrese el correo electrónico" required>
                            </div>
                            <div class="campo-formulario">
                                <label for="contraseña">Contraseña</label>
                                <div class="password-container">
                                    <input type="password" id="contraseñaCrearUsuario" name="contraseña" placeholder="Ingrese la contraseña" required>
                                    <button type="button" class="toggle-password" id="togglePasswordAgregar">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="campo-formulario">
                                <label for="id_rol">Asignar Rol</label>
                                <select id="id_rol" name="id_rol" required>
                                    <option value="" disabled selected>Seleccione un rol</option>
                                    <?php
                                    // Consulta para obtener los roles disponibles
                                    $consultaRoles = $con->prepare("SELECT id_rol, nombre_rol FROM rol");
                                    $consultaRoles->execute();
                                    $roles = $consultaRoles->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($roles as $rol) {
                                        echo "<option value='" . $rol['id_rol'] . "'>" . $rol['nombre_rol'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="botonesModalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarAgregar">Cancelar</button>
                                <button type="submit" name="agregarSubmit" class="agregar-btn">Agregar Usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <hr class="separador">
            <!-------------------------------- TABLA DE USUARIOS ------------------------------------->

            <div class="contenido_tabla" id="contenido">
                <input class="BOTON_FILTRAR" placeholder="Filtrar usuario por documento" oninput="filtrarPordocumento(this.value)">
                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay usuarios registrados</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>Usuario</h4>
                        <h4>Documento</h4>
                        <h4>Nombre</h4>
                        <h4>Rol</h4>
                        <h4>Correo</h4>
                        <h4>Estado</h4>
                        <h4>Accion</h4>
                    </div>
                    <?php foreach ($consulta as $usuario) { ?>
                        <div class="objetos">
                            <h4><img src="../../uploads/<?php echo isset($usuario['foto']) && $usuario['foto'] !== '' ? $usuario['foto'] : 'usu.png'; ?>" alt="img" height="60px" width="60px" onerror="this.src='../../uploads/usu.png'"></h4>
                            <h4><?php echo $usuario['id_documento']; ?></h4>
                            <h4><?php echo $usuario['nombre_completo']; ?></h4>
                            <h4><?php echo $usuario['nombre_rol']; ?></h4>
                            <h4><?php echo $usuario['correo']; ?></h4>
                            <h4><?php echo $usuario['estado']; ?></h4>
                            <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='actualizar.php?documento=<?php echo $usuario['id_documento'] ?>&route=usuarios.php'"></ion-icon></h4>
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


            <!------------------------------------------------ MODAL DE ELIMINAR USUARIOS ----------------------------------->



        </main>
    </div>

    <script>
        //<----------------------------- FILTRO PARA BUSCAR POR EL DOCUMENTO DE LA PERSONA ---------------------------> 

        const contenido = document.getElementById('contenido');

        function filtrarPordocumento(serial) {
            const objetos = document.querySelectorAll('.objetos');
            const usuario_buscado = serial.toUpperCase();


            let consulta = `SELECT u.id_documento, u.nombre_completo, u.correo, u.foto, estado.estado, rol.nombre_rol  FROM usuario u
            INNER JOIN rol ON u.id_rol = rol.id_rol
            INNER JOIN estado ON estado.id_estado = u.id_estado
            WHERE id_documento LIKE '%${usuario_buscado}%' LIMIT 5`;

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
                            const imagen = generales.foto ? generales.foto : 'usu.png';
                            cartas.innerHTML = ` 
                        <h4><img src='../../uploads/${imagen}' alt="img" height="60px" width="60px" onerror="this.src='../../uploads/usu.png'"></h4>
                        <h4>${generales.id_documento}</h4>
                        <h4>${generales.nombre_completo}</h4>
                        <h4>${generales.nombre_rol}</h4>
                        <h4>${generales.correo}</h4>
                        <h4>${generales.estado}</h4>
                        <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='actualizar.php?documento=${generales.id_documento}&route=usuarios.php'"></ion-icon></h4>
                        `;


                            const paginacion = document.getElementById('paginacion');
                            contenido.insertBefore(cartas, paginacion);
                        })

                    }

                }).catch(error => {
                    console.error('Error:', error);
                })
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="./js/modalUsuario.js"></script>
    <script src="./js/buscarUsuario.js"></script>
    <script src="./js/seleccionarVariosUsuario.js"></script>

</body>

</html>