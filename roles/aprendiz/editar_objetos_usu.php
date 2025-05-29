<?php
session_start();
require_once('../../database/database.php');
include '../admin/components/alert.php';
$conexion = new database;
$con = $conexion->conectar();
include('../../includes/sesion_validar.php');



// !-- AQUI  ESTA LA CONSULTA A LA BASE DE DATOS ACERCA DEL OBJETO -->

try {

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
    //EXTRAIGO LOS DATOS DE LA PERSONA
    $id_documento = $_SESSION['documento'];
    $id_serial = $_SESSION['id_serial'];
    $ruta = $_SESSION['redireccion'];

    $user = $con->prepare("SELECT nombre_completo, foto, correo, id_documento FROM usuario WHERE id_documento = :doc");
    $user->bindParam(":doc", $id_documento, PDO::PARAM_INT);
    $user->execute();

    $usuario = $user->fetch(PDO::FETCH_ASSOC);

    // CONSULTO LA RUTA

    $_SESSION['objeto_buscado'] = $id_serial;
    $_SESSION['objetos_usu'] = $id_documento;


    $objetos_1 = $con->prepare("SELECT * FROM objetos 
     INNER JOIN marca ON objetos.id_marca = marca.id_marca
     INNER JOIN estado ON objetos.id_estado = estado.id_estado
     WHERE id_serial = '$_SESSION[objeto_buscado]'");
    $objetos_1->execute();

    $consulta_1 = $objetos_1->fetch();
} catch (PDOException) {

    //
}


   
                                       


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDICION DE OBJETOS</title>
    <link rel="stylesheet" href="../admin/css/editar_objeto.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <div class="container_grid">

        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">



            <div class="container">
                <h1>Editar Objeto</h1>

                <div class="content-container">
                    <div class="user-info">

                        <div class="user-avatar">
                            <img src="../../uploads/<?php echo (empty($usuario['foto'])) ?  'usu.png' :  $usuario['foto']; ?>" alt="Imagen del usuario" onerror="this.src='../../uploads/usu.png'">
                        </div>

                        <div class="user-details">
                            <p><strong>Datos del propietario</strong></p><br>
                            <p><strong>Nombre:</strong> <?php echo $usuario['nombre_completo'] ?></p>
                            <p><strong>Correo:</strong> <?php echo $usuario['correo']; ?></p>
                            <p><strong>Documento:</strong> <?php echo $usuario['id_documento'] ?></p>
                        </div>

                    </div>

                    <div class="form-container">
                        <div class="image-preview">
                            <div class="image-column">

                                <div class="image-upload">
                                    <img src="../../uploads/<?php echo isset($consulta_1['foto']) && $consulta_1['foto'] !== '' ? $consulta_1['foto'] : 'default_iconos.png' ?>" alt="" class="imagen" id="imagen_objeto" onerror="this.src='../../uploads/default_iconos.png'">
                                    <div class="overlay">
                                        <i class="bi bi-upload"></i>
                                        <span>Subir Foto</span>
                                    </div>
                                    <form action="../../uploadsGlobal/upload_objetos.php"  method="POST" enctype="multipart/form-data" class="overlay" id="form-objetos">
                                        <input type="hidden" name="direccion" value="../roles/aprendiz/editar_objetos_usu.php">
                                        <input type="hidden" name="serial" value="<?php echo $consulta_1['id_serial']; ?>">
                                        <input type="file" class="file_input" id="fileUpload" name="foto" onchange="document.getElementById('form-objetos').submit()">

                                    </form>
                                </div>
                                <div class="image-title">Foto del objeto</div>
                            </div>
                        </div>


                        <div class="form-inputs">

                            <form action="" method="POST" enctype="multipart/form-data" class="grid-form">

                                <label for="name" class="form-label">Serial</label>
                                <input type="text" id="name" class="form-input" placeholder="nombre" value="<?php echo $consulta_1['id_serial'] ?>" readonly>
                                <input type="text" id="name" name="serial" class="form-input" placeholder="nombre" value="<?php echo $consulta_1['id_serial'] ?>" hidden>

                                <label for="" class="form-label">Nombre</label>
                                <input type="text" name="nombre_nuevo" class="form-input" id="nombreObjeto" value="<?php echo $consulta_1['nombre'] ?>">

                                <label for="" class="form-label">Caracteristicas</label>
                                <textarea class="form-input caracteristica" name="carac_nueva" id="caracteristicaObjeto" placeholder="Escribe aquí..."><?php echo $consulta_1['caracteristica'] ?></textarea>

                                <div class="form-group">

                                    <label for="" class="form-label">Marca</label>
                                    <select name="marca_nueva" id="" class="form-input">


                                        <option value="<?php echo $consulta_1['id_marca'] ?> ">
                                            <?php echo $consulta_1['nombre_marca'] ?>
                                        </option>

                                        <?php
                                        $sql = $con->prepare("SELECT * FROM marca WHERE id_tipo_marca = 2 and id_marca != '$consulta_1[id_marca]'");
                                        $sql->execute();

                                        $consulta_2 = $sql->fetchAll();

                                        foreach ($consulta_2 as $marca) { ?>

                                            <option value="<?php echo $marca['id_marca'] ?>"> <?php echo $marca['nombre_marca'] ?> </option>


                                        <?php  } ?>


                                    </select>
                                </div>

                                <div class="form-group">

                                </div>

                                <div class="form-actions">
                                    <input type="submit" name="enviar" class="btn-submit">
                                    <a href="<?php echo $ruta; ?>" class="btn-return">← Volver</a>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>




    <?php
    //  ENVIO DE LOS DATOS MEDIANTE PHP 
    if (isset($_POST['enviar'])) {


        $serial = $_POST['serial'];
        $nombre_objeto = $_POST['nombre_nuevo'];
        $caracteristica = $_POST['carac_nueva'];
        $marca = $_POST['marca_nueva'];


        $cambiar_datos = $con->prepare("UPDATE objetos SET nombre = :nombre, caracteristica =  :caract, id_marca = :marca WHERE id_serial = :serial");
        $cambiar_datos->bindParam(':nombre', $nombre_objeto, PDO::PARAM_STR);
        $cambiar_datos->bindParam(':serial', $serial, PDO::PARAM_STR);
        $cambiar_datos->bindParam(':caract', $caracteristica, PDO::PARAM_STR);
        $cambiar_datos->bindParam(':marca', $marca, PDO::PARAM_STR);
        $cambiar_datos->execute();

        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Objeto Actualizado con exito', '$ruta');
                });
            </script>";
        exit();
    }
    ?>
    <script>
        const name = document.getElementById("name")
        name.disabled = true;

        function limitar(elemento, limite) {
            elemento.addEventListener("input", () => {
                if (elemento.value.length > limite) {
                    elemento.value = elemento.value.substring(0, limite)
                }

            })
        }


        const nombreObjeto = document.getElementById("nombreObjeto")
        const caracteristicaObjeto = document.getElementById("caracteristicaObjeto")

        limitar(nombreObjeto, 50)
        limitar(caracteristicaObjeto, 50)

        const imagen = document.getElementById('imagen_objeto');
        const input = document.getElementById('fileUpload')

        input.addEventListener("change", (e) => {

            let file = e.target.files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    imagen.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        })
    </script>

</body>

</html>