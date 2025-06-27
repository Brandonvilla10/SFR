<?php
session_start();
require_once('../../database/database.php');
include ('components/alert.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../includes/sesion_validar.php');

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = isset($_SESSION['rol_name']) ? $_SESSION['rol_name'] : '';

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


$buscar_usu = $_SESSION['documento'];


$mostrar = $con->prepare("SELECT * FROM  usuario WHERE id_documento = '$buscar_usu'");
$mostrar->execute();
$primera_parte = $mostrar->fetch();


//LOGICA DE BORRAR EL OBJETO

if (isset($_POST['borrar'])) {


    $borrar = $con->prepare("UPDATE objetos SET id_estado = 4 WHERE id_serial = :id_serial");
    $borrar->bindParam(':id_serial', $_POST['borrar']);
    $borrar->execute();

    echo "
   
    <script>
            document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('success', 'Objeto eliminado con éxito'); 
            });

          
    </script>";
}

if(isset($_POST['editarBoton'])){
    $array = explode("," , $_POST['editarBoton']);

    $id_serial = $array[0];
    $redireccion = $array[1];
    
    $_SESSION['id_serial'] = $id_serial;
    $_SESSION['redireccion'] = $redireccion;

    echo "<script>window.location = './editar_objetos_usu.php'</script> ";
    exit();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../admin/css/objetos_usuario.css">
    <link rel="stylesheet" href="../admin/components/modalConfirmacion.css">

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
                    <button class="add-button" onclick="abrirModalObjetos()"> + </button>
                </div>



                <!-- modal de confirmacion de eliminacion -->

                <div class="modalConfirmacion" id="modalConfirmacion">
                    <div class="contenedorModalConfirmacion">
                        <div class="headerModalConfirmacion">
                            <h3>Confirmar Acción</h3>
                            <button class="cerrarModalConfirmacion" id="cerrarModalConfirmacion">&times;</button>
                        </div>
                        <div class="cuerpoModalConfirmacion">
                            <div class="mensaje-confirmacion">
                                <i class="bi bi-exclamation-triangle-fill icono-alerta"></i>
                                <p id="mensajeConfirmacion">¿Estás seguro que deseas eliminar este elemento?</p>
                            </div>
                            <div class="botonesModalConfirmacion">
                                <button type="button" class="cancelar-btn" id="cancelarConfirmacion">Cancelar</button>
                                <form method="post">

                                    <button type="submit" class="confirmar-btn" name="borrar" id="confirmarAccion">Confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!------------- FIN DE LA SECCION 1 --------------------------->

                <!-- APARTADO DE LAS CARTAS DE LOS OBJETOS -->

                <div class="objects-grid">

                    <?php $sql = $con->prepare("SELECT *, usuario.foto AS user_foto , objetos.foto AS object_foto FROM  objetos 
                    INNER JOIN marca ON marca.id_marca  = objetos.id_marca
                    INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
                    INNER JOIN estado ON estado.id_estado = objetos.id_estado 
                    INNER JOIN tipoobjeto ON tipoobjeto.id_tipoObjeto = objetos.id_tipoObjeto
                    WHERE objetos.id_documento ='$buscar_usu' and objetos.id_estado = 1");
                    $sql->execute();

                    $consulta_2 = $sql->fetchAll();


                    //<-------------------------------  CARTA DE LOS OBJETOS--------------------->

                    foreach ($consulta_2 as $objeto) {
                        $array = array(
                            "id_serial"=> $objeto["id_serial"],
                            "redireccion" => './objetos_usuario.php'
                        )
                        ?>
                        
                        <div class="object-card">

                            <div class="object-title">

                                <div class="object-id"><?php echo $objeto['id_serial']; ?></div>
                                <div class="object-name"><strong><?php echo $objeto['nombre'] ?></strong></div>

                                <div class="object-icon">

                                    <img src="../../uploads/<?php echo isset($objeto['object_foto']) && $objeto['object_foto'] !== '' ? $objeto['object_foto'] : 'default_iconos.png'; ?>" alt="" onerror="this.src='../../uploads/default_iconos.png'">

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


                                    <button type="submit" name="borrar" value="<?php echo $objeto['id_serial'] ?>" class="action-button delete-button modalConfirmacionButton" onclick="mostrarConfirmacion(this.value,'Estas seguro de eliminar este objeto')"> <i class="bi bi-trash-fill"></i></button>


                                    <form action="" method="post">

                                        <button class="action-button edit-button" name="editarBoton" type="submit"
                                        value="<?php echo implode("," , $array ) ?>">
                                        <i class="bi bi-pencil-square"></i> </button>
                                        
                                    </form>
                                </div>

                            </div>


                        </div>
                    <?php } ?>

                </div>

                
            </section>
        </main>

        <!-------------------------------- MODAL DE CREACION DE OBJETOS ----------------------->

        <div id="agregar" class="modal">

            <form action="../../uploadsGlobal/upload_crear_objeto.php" id="formCrearObjeto" method="POST" enctype="multipart/form-data" class="datos_basicos">

                <label for="serial" class="label_titulo">Serial</label>
                <input type="text" id="serial" name="serial" class="data_input" placeholder="Serial">

                <label for="" class="label_titulo">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="data_input">

                <label for="foto" class="label_titulo">Foto</label>

                <input type="file" id="foto" name="foto" class="data_input" accept=".jpg,.jpeg,.png">

                <label for="" class="label_titulo">Caracteristicas</label>
                <textarea class="input_caracteristica" id="caracteristicas" name="caracteristica" placeholder="Escribe aquí..."></textarea>

                <label for="" class="label_titulo">Marca</label>
                <select name="marca" id="marca" class="selectos">

                    <?php $sql = $con->prepare("SELECT * FROM marca WHERE id_tipo_marca = 2 ORDER BY nombre_marca ASC");
                    $sql->execute();
                    $consulta_2 = $sql->fetchAll();

                    foreach ($consulta_2 as $marca) { ?>
                        <option value="<?php echo $marca['id_marca'] ?>"> <?php echo $marca['nombre_marca'] ?> </option>
                    <?php  } ?>

                </select>

                <label for="tipo" class="label_titulo">Tipo de Objeto</label>
                <select name="tipoObjeto" id="tipo" class="selectos">

                    <?php $sql_3 = $con->prepare('SELECT * FROM tipoobjeto ORDER BY tipoObjeto ASC');
                    $sql_3->execute();
                    $consulta_4 = $sql_3->fetchAll();


                    foreach ($consulta_4 as $tipo) {?>

                        <option value="<?php echo $tipo['id_tipoObjeto']?>"> <?php echo $tipo['tipoObjeto']?> </option>
                  
                    <?php } ?>
                </select>

                <input type="hidden" name="user_id" value="<?php echo $buscar_usu ?>">
                <button type="submit" id="CrearObjeto" name="CrearObjeto" class="CrearObjeto"> Crear Objeto </button>
                <button type="button" class="regresar" onclick="cerrarModalObjetos()"> CERRAR</button>

            </form>
        </div>

        <!----------------- fin del modal------------------->




        <script>
            const modalConfirmacion = document.getElementById('modalConfirmacion');
            const cerrarModalConfirmacion = document.getElementById('cerrarModalConfirmacion');
            const cancelarConfirmacion = document.getElementById('cancelarConfirmacion');
            const confirmarAccion = document.getElementById('confirmarAccion');
            const mensajeConfirmacion = document.getElementById('mensajeConfirmacion');

            let callbackConfirmacion = null;

            const modalConfirmacionButton = document.querySelectorAll(".modalConfirmacionButton")

            // modalConfirmacionButton.forEach((element) => {
            //     element.addEventListener("click", () => {
            //         mostrarConfirmacion("Estas Seguro De Eliminar Este Objeto")
            //     })
            // })

            function mostrarConfirmacion(valorBoton,mensaje, callback) {
                mensajeConfirmacion.textContent = mensaje;
                callbackConfirmacion = callback;
                modalConfirmacion.style.visibility = 'visible';
                modalConfirmacion.style.opacity = '1';
                confirmarAccion.value = valorBoton;

            }

            function cerrarConfirmacion() {
                modalConfirmacion.style.visibility = 'hidden';
                modalConfirmacion.style.opacity = '0';
            }

            cerrarModalConfirmacion.addEventListener('click', cerrarConfirmacion);
            cancelarConfirmacion.addEventListener('click', cerrarConfirmacion);

            confirmarAccion.addEventListener('click', function() {
                if (typeof callbackConfirmacion === 'function') {
                    callbackConfirmacion();
                }
                cerrarConfirmacion();
            });
        </script>

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

                    if (!marca || !tipo) {
                        mostrarModal('mistake', 'Debes seleccionar marca, estado y tipo de objeto..');
                        return;
                    }

                    // Confirmación final
                    form.submit(); // Todo OK, se envía
                    
                    

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

            function abrirModalObjetos() {
                document.getElementById("agregar").classList.add("modal-activo");
                document.getElementById("modal-backdrop").classList.add("backdrop-activo");
            }

            function cerrarModalObjetos() {
                document.getElementById("agregar").classList.remove("modal-activo");
                document.getElementById("modal-backdrop").classList.remove("backdrop-activo");
            }
        </script>
</body>


</html>