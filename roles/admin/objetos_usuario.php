<?php
session_start();
require_once('../../database/database.php');
// include('../../includes/sesion_validar.php');
include 'components/alert.php';
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
    


if (isset($_POST['objetos_submit'])) {

    $_SESSION['objetos_usu'] = $_POST['user_id'];
    $usuario = $_POST['user_id'];

    $validacion = $con->prepare("SELECT * FROM  usuario WHERE id_documento = '$usuario'");
    $validacion->execute();


    $consulta_1 = $validacion->fetch();

    if (!$consulta_1) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Usuario no encontrado', 'objetos_index.php');
            });
        </script>";
        exit();
    }
}


$buscar_usu = $_SESSION['objetos_usu'];

$mostrar = $con->prepare("SELECT * FROM  usuario WHERE id_documento = '$buscar_usu'");
$mostrar->execute();

$primera_parte = $mostrar->fetch();


//LOGICA DE BORRAR EL OBJETO

if (isset($_POST['borrar'])) {


    $borrar = $con->prepare("DELETE FROM objetos WHERE id_serial = :id_serial");
    $borrar->bindParam(':id_serial', $_POST['borrar']);
    $borrar->execute();
    
    echo "
    <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Objeto eliminado con éxito');
            });

          
    </script>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>OBJETOS </title>
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="css/vehiculos_usuarios.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="body">
    <div class="container_grid">

        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>


        <main class="grid_contenido_plantilla">

            <!---------------------------------------------- SECCION 1   NOMBRE DEL PROPIETARIO----------------------->
            <section class="container">

                <div class="header">
                    <div>
                        <strong>SECCION OBJETOS </strong>
                        <br>
                        Todos los objetos de <?php echo  $primera_parte['nombre_completo']  ?> numero de identificacion <?php echo  $primera_parte['id_documento'] ?>
                    </div>
                    <button class="add-button" onclick="document.getElementById('agregar').showModal()"> + </button>
                </div>





                <!------------- FIN DE LA SECCION 1 --------------------------->

                <!-- APARTADO DE LAS CARTAS DE LOS OBJETOS -->

                <div class="objects-grid">

                    <?php $sql = $con->prepare("SELECT *, usuario.foto AS user_foto , objetos.foto AS object_foto FROM  objetos 
                INNER JOIN marca ON marca.id_marca  = objetos.id_marca
                INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
                INNER JOIN estado ON estado.id_estado = objetos.id_estado 
                INNER JOIN tipoobjeto ON tipoobjeto.id_tipoObjeto = objetos.id_tipoObjeto
                WHERE objetos.id_documento ='$buscar_usu'");
                    $sql->execute();

                    $consulta_2 = $sql->fetchAll();


                    //<-------------------------------  CARTA DE LOS OBJETOS--------------------->

                    foreach ($consulta_2 as $objeto) { ?>

                        <div class="object-card">

                            <div class="object-title">

                                <div class="object-id"><?php echo $objeto['id_serial']; ?></div>
                                <div class="object-name"><strong><?php echo $objeto['nombre'] ?></strong></div>

                                <div class="object-icon">

                                    <img src="../../uploads/<?php echo isset($objeto['object_foto']) && $objeto['object_foto'] !== '' ? $objeto['object_foto'] : 'default_iconos.png'; ?>" alt="">

                                </div>
                                <div class="object-description"> <?php echo $objeto['caracteristica'] ?></div>
                            </div>

                            <div class="object-details">


                                <div class="options_card">
                                    <div class="consulta">
                                        <p> <strong>Marca:</strong> <?php echo $objeto['nombre_marca'] ?> </p>
                                        <p> <strong>Estado:</strong> <?php echo $objeto['estado'] ?> </p>
                                        <p><strong>Tipo:</strong> <?php echo $objeto['tipoObjeto']  ?></p>
                                    </div>
                                </div>

                                <div class="object-actions">

                                    <form action="" method="POST">
                                        <button type="submit" name="borrar" value="<?php echo $objeto['id_serial'] ?>" class="action-button delete-button" onclick=" return confirm('desesas borrar el objeto de serial <?php echo $objeto['id_serial'] ?>')"> <i class="bi bi-trash-fill"></i></button>
                                    </form>
                                    <button class="action-button edit-button" onclick="window.location.href='editar_objetos_usu.php?id=<?php echo $objeto['id_serial'] ?>&owner=<?php echo $primera_parte['id_documento'] ?>&route=objetos_usuario.php'"><i class="bi bi-pencil-square"></i> </button>

                                </div>

                            </div>


                        </div>
                    <?php } ?>

                </div>

                <!------------------------------------ Boton de regresar ----------------------->

                <button class="footer-button" onclick="window.location.href='objetos_index.php'"> Regresar</button>
            </section>
        </main>

        <!-------------------------------- MODAL DE CREACION DE OBJETOS ----------------------->

        <dialog id="agregar" class="modal">

            <form action="../../uploads/upload_crear_objeto.php" id="formCrearObjeto" method="POST" enctype="multipart/form-data" class="datos_basicos">

                <label for="serial" class="label_titulo">Serial</label>
                <input type="text" id="serial" name="serial" class="data_input" placeholder="Serial">

                <label for="" class="label_titulo">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="data_input">

                <label for="foto" class="label_titulo">Foto</label>

                <input type="file" id="foto" name="foto" class="data_input">

                <label for="" class="label_titulo">Caracteristicas</label>
                <textarea class="input_caracteristica" id="caracteristicas" name="caracteristica" placeholder="Escribe aquí..."></textarea>

                <label for="" class="label_titulo">Marca</label>
                <select name="marca" id="marca" class="selectos">

                    <?php $sql = $con->prepare("SELECT * FROM marca ");
                    $sql->execute();
                    $consulta_2 = $sql->fetchAll();

                    foreach ($consulta_2 as $marca) { ?>
                        <option value="<?php echo $marca['id_marca'] ?>"> <?php echo $marca['nombre_marca'] ?> </option>
                    <?php  } ?>

                </select>

                <label for="estado" class="label_titulo">Estado</label>
                <select name="estado" id="estado" class="selectos">

                    <?php $sql_2 = $con->prepare("SELECT * FROM estado");
                    $sql_2->execute();
                    $consulta_3 = $sql_2->fetchAll();

                    foreach ($consulta_3 as $estados) { ?>

                        <option value="<?php echo $estados['id_estado'] ?>"> <?php echo $estados['estado'] ?></option>

                    <?php } ?>

                </select>

                <label for="tipo">Tipo de Objeto</label>
                <select name="tipoObjeto" id="tipo" class="selectos">

                    <?php $sql_3 = $con->prepare('SELECT * FROM tipoobjeto');
                    $sql_3->execute();
                    $consulta_4 = $sql_3->fetchAll();


                    foreach ($consulta_4 as $tipo) { ?>

                        <option value="<?php echo $tipo['id_tipoObjeto'] ?>"> <?php echo $tipo['tipoObjeto'] ?> </option>

                    <?php } ?>
                </select>

                <input type="hidden" name="user_id" value="<?php echo $_SESSION['objetos_usu'] ?>">

                <button type="submit" id="CrearObjeto" name="CrearObjeto" class="CrearObjeto"> Crear Objeto </button>
                <button type="button" class="regresar" onclick="document.getElementById('agregar').close()"> CERRAR</button>

            </form>
        </dialog>

        <!----------------- fin del modal------------------->

        <script src="pruebas/script.js"></script>


        <!----------------- Envio del modal con JS -------------------->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('formCrearObjeto');
                const botonEnviar = document.getElementById('CrearObjeto');

                form.addEventListener('submit', function(e) {

                    e.preventDefault();

                    const serial = document.getElementById('serial').value;
                    const nombre = document.getElementById('nombre').value;
                    const foto = document.getElementById('foto').value;
                    const caracteristicas = document.getElementById('caracteristicas').value;
                    const marca = document.getElementById('marca').value;
                    const estado = document.getElementById('estado').value;
                    const tipo = document.getElementById('tipo').value;

                    // expresiones

                    const serialExp = /^[A-Z0-9\-]{3,20}$/i;
                    const nombreExp = /^[A-Za-z\s]{2,}$/;

                    if (!serialExp.test(serial)) {
                        
                            mostrarModal('mistake', 'El serial no es valido. Solo letras, numeros y guiones. Mínimo 3 caracteres.');
                            
                            
                            return;
                        }
                        
                        if (!nombreExp.test(nombre)) {
                            mostrarModal('mistake', 'El nombre no es valido. Solo letras, numeros y espacios, mínimo 2 caracteres.');
                            return;
                        }
                        
                        if (foto === '') {
                            mostrarModal('mistake', 'Debes seleccionar una imagen para el objeto.');
                            
                            return;
                        }
                        
                        if (caracteristicas === '') {
                            mostrarModal('mistake', 'Debes escribir las características del objeto..');
                            return;
                        }
                        
                        if (!marca || !estado || !tipo) {
                            mostrarModal('mistake', 'Debes seleccionar marca, estado y tipo de objeto..');
                        return;
                    }

                    // Confirmación final
                    if (confirm('¿Deseas crear este objeto?')) {
                        form.submit(); // Todo OK, se envía
                    }


                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                const header = document.querySelector('.header');
                header.classList.add('animate-header');

                const cards = document.querySelectorAll('.object-card');
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('visible');
                    }, 100 * index);
                });

                const footerButton = document.querySelector('.footer-button');
                setTimeout(() => {
                    footerButton.classList.add('animate-button');
                }, 100 * cards.length + 200);
            });
        </script>
</body>


</html>