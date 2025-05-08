<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

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
                        <p id="textoEliminar">+</p>Eliminar Usuario
                    </button>
                </div>

                <div class="AgregarUsuarioDiv">
                    <button class="buscar_objeto_titulo" id="botonAgregar" type="button">
                        <p id="textoAbrir">+</p>Agregar Usuario
                    </button>
                </div>
            <!-- el modal empieza a partid de aca -->
        </div>
        
        
        <div class="EliminarUsuario mostrar" id="EliminarUsuario" style="display: flex; opacity: 1;">
        <form class="formEliminar" method="POST">
            <p class="EliminarTitulo">Eliminar Usuario</p>
            <p class="messageelimnar">Tenga En Cuenta Que Los Cambios No Son Reversibles.</p>

            <span>Documento Del Usuario</span>
            <input name="documentoUsuario" required="" type="number" class="documentoUsuario" placeholder="Documento Del Usuario">
            <span>Contraseña Administrador</span>

            <input name="contraseñaAdmin" required="" type="text" class="contraseñaAdmin" placeholder="Contraseña Administrador">


            <input type="submit" value="Eliminar" name="Eliminar" class="EliminarUsuarioboton">
        </form>
            </div>
            <hr class="separador">
            <!-------------------------------- TABLA DE USUARIOS ------------------------------------->

            <div class="contenido_tabla">
                <input class="BOTON_FILTRAR" placeholder="Filtrar usuario por documento" oninput="filtrarPorSerial(this.value)">
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

                <div class="paginacion">
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

        function filtrarPorSerial(serial) {
                const objetos = document.querySelectorAll('.objetos');
                const serial_buscado = serial.toUpperCase();

                objetos.forEach(objeto => {
                    const serialElement = objeto.querySelectorAll('h4')[1];

                    if (serialElement) {
                        const textoSerial = serialElement.textContent || serialElement.innerText;

                        if (textoSerial.toUpperCase().includes(serial_buscado)) {
                            objeto.style.display = '';
                        } else {
                            objeto.style.display = '';
                        }
                    }
                });
            }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="./js/crearUsuario.js"></script>
    <script src="./js/buscarUsuario.js"></script>
    <script src="./js/seleccionarVariosUsuario.js"></script>

</body>

</html>