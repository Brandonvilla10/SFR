<?php
// Validaciones e includes-----------------------------------
session_start();
require_once('../../database/database.php');
include('components/alert.php');
include('../../includes/sesion_validar.php');
// ----------------------------------


$conexion = new database;
$con = $conexion->conectar();





// -------------------------------------------------------------------------------------------------



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



                

                <button class="buscar_objeto_titulo" id="abrirBuscar">Buscar Objetos</button>
            </div>
            <hr class="separador">


            


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
                            <h4 style="
    align-items: center;
    justify-content: center;
    display: flex;
">
                                <ion-icon name="create-outline" size="large" onclick="window.location.href='editar_objetos_usu.php?id=<?php echo $generales['id_serial']; ?>&owner=<?php echo $generales['id_documento']; ?>&route=objetos_index.php'"></ion-icon>
                                <form style="display: inline;" action="objetos_usuario.php" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $generales['id_documento']; ?>">
                                    <input type="hidden" name="nombre" value="<?php echo $generales['nombre_completo']; ?>">
                                    <button type="submit" style="color: white; background-color: #4CAF50; border-radius: 50%; width: 25px; height: 25px; padding: 0; border: none; cursor: pointer; font-size: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;" name="objetos_submit" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">+</button>
                                </form>
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