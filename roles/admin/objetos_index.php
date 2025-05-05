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
$limite_registros = 10;

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
    <title>OBJETOS</title>
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
        <div class="contenido_tabla">
            <input class="BOTON_FILTRAR" placeholder="Filtrar objeto por serial" oninput="filtrarPorSerial(this.value)">
            <div class="regulador_tamaño">
                <table class="tabla_de_objetos">
                    <thead>
                        <tr>
                            <th>Objeto</th>
                            <th>Serial</th>
                            <th>Nombre</th>
                            <th>DNI Dueño</th>
                            <th>Nombre Propietario</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consulta as $generales) { ?>
                            <tr>
                                <td><img src="../../uploads/<?php echo $generales['ob_pic'] ?>" alt="img" height="60px" width="60px"></td>
                                <td><?php echo $generales['id_serial']; ?></td>
                                <td><?php echo $generales['nombre']; ?></td>
                                <td><?php echo $generales['id_documento']; ?></td>
                                <td><?php echo $generales['nombre_completo']; ?></td>
                                <td><?php echo $generales['estado']; ?></td>
                                <td><img src="./imgAdmin/tres puntos.png" alt="" width="30px"></td>
                            </tr>
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
    const plant = document.getElementById('estado-objetos');

    function plantilla(){
        plant.classList.remove('estadoDeobjetos');
    }
    function retirarPlantilla(){
        plant.classList.add('estadoDeobjetos');
    }

</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
<script> 

function filtrarPorSerial(serial) {
    const tabla = document.querySelector('.tabla_de_objetos');
    const filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < filas.length; i++) {

        //  EN ESTA PARTE LO QUE HAGO ES HACER UN CICLO PARA RECORRER LAS FILAS
        const celdaSerial = filas[i].getElementsByTagName('td')[1]; // El serial está en la segunda columna
        if (celdaSerial) {
            const textoSerial = celdaSerial.textContent || celdaSerial.innerText;
            if (textoSerial.toUpperCase().indexOf(serial.toUpperCase()) > -1) {
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
