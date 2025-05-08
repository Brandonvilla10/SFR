<?php
session_start();
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

$codigo = $_SESSION['documento'];
$nombre = explode(" ", $_SESSION['nombre'])[0];
$rol = $_SESSION['rol_name'];

if (!isset($_SESSION['documento'])) {
    header("Location: ../../index.php");   
    exit();
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
$consulta_total = $con->prepare("SELECT COUNT(*) FROM objetos");
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT *, objetos.foto AS ob_pic FROM objetos
    INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
    INNER JOIN estado ON estado.id_estado = objetos.id_estado
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
</head>
<body>
<div class="container_grid">
    <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="titulo">
            <div>
                <h1>Objetos</h1>
                <p style="color:#858585">Administra los objetos según tu necesidad</p>
            </div>
            <button class="buscar_objeto_titulo" onclick="plantilla()">Buscar Objetos</button>
        </div>
        <hr class="separador">
        <!-------------------------------- TABLA DE OBJETOS ------------------------------------->
        
        <div class="contenido_tabla">
            <input class="BOTON_FILTRAR" placeholder="Filtrar objeto por serial" oninput="filtrarPorSerial(this.value)">
            <?php if ($consulta->rowCount() == 0) { ?>
                <div class="notificacion">
                    <h4>No hay objetos registrados</h4>
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
                    <h4><ion-icon name="create-outline" size="large" onclick="window.location.href='editar_objetos_usu.php?id=<?php echo $generales['id_serial']; ?>&owner=<?php echo $generales['id_documento'];?>&route=objetos_index.php'"></ion-icon></h4>
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


<!------------------------------------------------ MODAL DE BUSQUEDA DE OBJETOS ----------------------------------->

        <div class="buscar_user estadoDeobjetos" id ="estado-objetos">
            <button class="objetos_cerrar_button" id="close-objects" onclick="retirarPlantilla()"><i class="bi bi-x-lg"></i></button>      <!-- ESTE ES EL TEXTO -->
                <div class="img_fondo">

                    <form action="objetos_usuario.php" class="inside_img_fondo" method="post">
                        <h1>Identificar Usuario</h1>

                        <p>Digite el documento</p>

                        <input type="number" name ="user_id" required class="objetos_input">
                        
                        <input type="submit" value="Continuar" class="objetos_submit" name ="objetos_submit">
                    </form>
                </div>
        </div>


    </main>
</div>

<script>

    // <----------------------------- CERRAR Y ABRIR MODAL DE BUSQUEDA DE OBJETOS --------------------------->
    const plant = document.getElementById('estado-objetos');

    function plantilla(){
        plant.classList.remove('estadoDeobjetos');
    }
    function retirarPlantilla(){
        plant.classList.add('estadoDeobjetos');
    }


//<----------------------------- FILTRO PARA BUSCAR POR EL SERIAL ---------------------------> 

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
                    objeto.style.display = 'none';
                }
            }
        });
    }
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
</body>
</html>