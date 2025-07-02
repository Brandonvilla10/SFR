<?php
// Validacion e includes--------------------------------
session_start();
require_once('../../database/database.php');
include("components/alert.php");
include('../../includes/sesion_validar.php');

$conexion = new database;
$con = $conexion->conectar();

// Establecer la variable @documento_generador en MySQL desde la sesión
try {
    $nombre = $_SESSION['nombre'];
    $con->exec("SET @documento_generador = '$nombre'");

    if (empty($nombre)) {
        // Si sigue vacía, establecer un valor predeterminado
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
    echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = isset($_SESSION['rol_name']) ? $_SESSION['rol_name'] : '';

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM usuario WHERE id_estado != 4");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT u.id_documento, u.nombre_completo, u.correo, u.foto, estado.estado, rol.nombre_rol  FROM usuario u
    INNER JOIN rol ON u.id_rol = rol.id_rol
    INNER JOIN estado ON estado.id_estado = u.id_estado
    WHERE u.id_estado!= 4
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

    try {
        $id_documento = $_POST['id_documento'];
        $nombre_completo = $_POST['nombre_completo'];
        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];
        $id_rol = $_POST['id_rol'];

       
        if (empty($id_documento) || empty($nombre_completo) || empty($correo) || empty($contraseña) || empty($id_rol)) {
            echo "
            <script>
               document.addEventListener('DOMContentLoaded', function () {
                   mostrarModal('mistake', 'No se puede realizar esta accion', 'usuarios.php');
               });
            </script>";
        }

        $hasPassword = password_hash($contraseña, PASSWORD_DEFAULT);

        $nuevoUser = $con->prepare("INSERT INTO usuario (id_documento, nombre_completo, correo, contraseña, id_estado,id_rol) VALUES (:id_documento, :nombre_completo, :correo, :contrasena,1, :id_rol)");
        $nuevoUser->bindParam(':id_documento', $id_documento);
        $nuevoUser->bindParam(':nombre_completo', $nombre_completo);
        $nuevoUser->bindParam(':correo', $correo);
        $nuevoUser->bindParam(':contrasena', $hasPassword);
        $nuevoUser->bindParam(':id_rol', $id_rol);
        $nuevoUser->execute();

         if(!empty($_POST['fichaUsuario'])){
            $id_ficha = $_POST['fichaUsuario'];

            $detalleInsert = $con->prepare("INSERT INTO detallematricula (id_ficha, id_documento, id_estado) 
            VALUES ($id_ficha,$id_documento,1)");
            $detalleInsert->execute();
        }
        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('success', 'Usuario registrado con exito', 'usuarios.php');
           });
       </script>";
    } catch (PDOException $e) {
        echo "
        <script>
           document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'No se puede realizar esta accion', 'usuarios.php');
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
                                <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ingrese el nombre completo" maxlength="50" required>
                            </div>
                            <div class="campo-formulario">
                                <label for="correo">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" placeholder="Ingrese el correo electrónico" maxlength="65" required>
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
                            <div class="campo-formulario" id="fichaNuevo">
                                <label for="fichaNuevo">Ficha</label>
                                <select name="fichaUsuario" id="fichaUsuario" >
                                    <option value="" disabled selected>Seleccione una ficha</option>
                                    <?php
                                    $consultaFichas = $con->prepare("SELECT * FROM fichas WHERE n_ficha != 1");
                                    $consultaFichas->execute();
                                    $fichas = $consultaFichas->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($fichas as $ficha) {
                                        echo "<option value='" . $ficha['n_ficha'] . "'>" . $ficha['n_ficha'] . "</option>";
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
                <input class="BOTON_FILTRAR" placeholder="Filtrar usuario por documento" oninput="filtrarPordocumento(this.value)" id="filtro">
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
                            <h4>
                                <div class="dropdown">
                                    <button onclick="desplegar('<?php echo $usuario['id_documento']; ?>')" class="dropbtn">
                                        <ion-icon name="chevron-down-outline" size="large"></ion-icon>
                                    </button>
                                    <div id="myDropdown_<?php echo $usuario['id_documento']; ?>" class="dropdown-content">
                                        <a href="actualizar.php?documento=<?php echo $usuario['id_documento'] ?>&route=usuarios.php">Editar Usuario</a>
                                        <a href="apartado_datos/GenerarQR.php?documento=<?php echo $usuario['id_documento']; ?>">Generar QR</a>
                                        <form action="set_session.php" method="post">


                                            <button class="crearBoton" name="documentoUsuario_vehiculo" type="submit" value="<?php echo $usuario['id_documento']; ?>">Crear Vehiculos</button>
                                        </form>
                                        <form action="set_session.php" method="post">


                                            <button class="crearBoton" name="documento_usuario" type="submit" value="<?php echo $usuario['id_documento']; ?>">Crear Objetos</button>
                                        </form>
                                    </div>
                                </div>
                            </h4>
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
        
        const id_rol = document.getElementById("id_rol");
        const fichaNuevo = document.getElementById("fichaNuevo");

        const fichaUsuario = document.getElementById("fichaUsuario")

        fichaNuevo.style.visibility = "hidden"; fichaNuevo.style.opacity = 0;
        
        id_rol.addEventListener("change", () => {
            if(id_rol.value == 2){
                fichaNuevo.style.visibility = "visible"; fichaNuevo.style.opacity = 1;
            }else{
                fichaNuevo.style.visibility = "hidden"; fichaNuevo.style.opacity = 0;
                fichaUsuario.value = null
            }
        })


        function desplegar(userId) {

            let dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                let openDropdown = dropdowns[i];
                if (openDropdown.id !== "myDropdown_" + userId && openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }

            document.getElementById("myDropdown_" + userId).classList.toggle("show");
        }


        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn, .dropbtn *')) {
                let dropdowns = document.getElementsByClassName("dropdown-content");
                let i;
                for (i = 0; i < dropdowns.length; i++) {
                    let openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        document.getElementById('formAgregarUsuario').addEventListener('submit', function(e) {

            const documento = document.getElementById('id_documento');
            const nombre = document.getElementById('nombre_completo');
            const correo = document.getElementById('correo');
            const password = document.getElementById('contraseñaCrearUsuario');
            const rol = document.getElementById('id_rol');

            if (documento.value.length < 8 || documento.value.length > 10) {
                e.preventDefault();
                mostrarModal('mistake', 'El documento debe tener entre 8 y 10 dígitos', '' );
                return;
            }


            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,50}$/.test(nombre.value)) {
                e.preventDefault();
                mostrarModal('mistake', 'El nombre solo debe contener letras y espacios (3-50 caracteres)', '');
                return;
            }


            if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(correo.value)) {
                e.preventDefault();
                mostrarModal('mistake', 'Ingrese un correo electrónico válido', '');
                return;
            }


            if (password.value.length < 8) {
                e.preventDefault();
                mostrarModal('mistake', 'La contraseña debe tener al menos 8 caracteres', '' );
                return;
            }

            if (!/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])/.test(password.value)) {
                e.preventDefault();
                mostrarModal('mistake', 'La contraseña debe contener al menos una mayúscula, una minúscula y un número', '');
                return;
            }


            if (!rol.value) {
                e.preventDefault();
                mostrarModal('mistake', 'Debe seleccionar un rol', '');
                return;
            }
        });


        document.getElementById('id_documento').addEventListener('input', function(e) {
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }

            this.value = this.value.replace(/[^0-9]/g, '');
        });


        document.getElementById('nombre_completo').addEventListener('input', function(e) {
            // Only allow letters, spaces and accents
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });



        //<----------------------------- FILTRO PARA BUSCAR POR EL DOCUMENTO DE LA PERSONA ---------------------------> 

        const contenido = document.getElementById('contenido');

        function filtrarPordocumento(serial) {
            const objetos = document.querySelectorAll('.objetos');
            const usuario_buscado = serial.toUpperCase();

            const filtro = document.getElementById('filtro')

            filtro.addEventListener("input", () => {
                if (filtro.value.length > 10) {
                    filtro.value = filtro.value.substring(0, 10)
                }
            })

            let consulta = `SELECT u.id_documento, u.nombre_completo, u.correo, u.foto, estado.estado, rol.nombre_rol  FROM usuario u
            INNER JOIN rol ON u.id_rol = rol.id_rol
            INNER JOIN estado ON estado.id_estado = u.id_estado
            WHERE id_documento LIKE '%${usuario_buscado}%' LIMIT 5`;

            fetch('fetch/busquedaBarra.php', {
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
                        <h4>
                                <div class="dropdown">
                                    <button onclick="desplegar('${generales.id_documento}')" class="dropbtn">
                                        <ion-icon name="chevron-down-outline" size="large"></ion-icon>
                                    </button>
                                    <div id="myDropdown_${generales.id_documento}" class="dropdown-content">
                                        <a href="actualizar.php?documento=${generales.id_documento}&route=usuarios.php">Editar Usuario</a>
                                        <a href="apartado_datos/GenerarQR.php?documento=${generales.id_documento}">Generar QR</a>
                                        <form action="set_session.php" method="post">
                                            <button  class="crearBoton" type="submit" name="documentoUsuario_vehiculo" value="${generales.id_documento}">Crear Vehiculos</button>
                                        </form>
                                        <form action="set_session.php" method="post">
                                            <button class="crearBoton" type="submit" name="documento_usuario" value="${generales.id_documento}">Crear Objetos</button>
                                        </form>
                                    </div>
                                </div>
                        </h4>
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