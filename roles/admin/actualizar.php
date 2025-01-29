<?php 
include ('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

$documento = $_GET['documento'];
$_SESSION['documentoUpload'] = $documento;

// Consulta principal para obtener los datos del usuario
$sql = $con->prepare("SELECT * FROM usuario 
                      INNER JOIN rol ON rol.id_rol = usuario.id_rol
                      INNER JOIN fichas ON fichas.n_ficha = usuario.ficha
                      INNER JOIN estado ON estado.id_estado = usuario.id_estado 
                      WHERE id_documento = '$documento'");
$sql->execute();
$fila = $sql->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    try {
        $updates = [];
        $params = [];

        // Verificar si hay datos para actualizar
        if (!empty($_POST['id_documento'])) {
            $updates[] = 'id_documento = :id_documento';
            $params[':id_documento'] = $_POST['id_documento'];
        }
        
        if (!empty($_POST['nombre_completo'])) {
            $updates[] = 'nombre_completo = :nombre_completo';
            $params[':nombre_completo'] = $_POST['nombre_completo'];
        }

        if (!empty($_POST['correo'])) {
            $updates[] = 'correo = :correo';
            $params[':correo'] = $_POST['correo'];
        }

        if (!empty($_POST['ficha'])) {
            $updates[] = 'ficha = :ficha';
            $params[':ficha'] = $_POST['ficha'];
        }
        
        if (!empty($_POST['codigo_barras'])) {
            $updates[] = 'codigo_barras = :codigo_barras';
            $params[':codigo_barras'] = $_POST['codigo_barras'];
        }
        
        if (!empty($_POST['id_estado'])) {
            $updates[] = 'id_estado = :id_estado';
            $params[':id_estado'] = $_POST['id_estado'];
        }

        if (!empty($_POST['id_rol'])) {
            $updates[] = 'id_rol = :id_rol';
            $params[':id_rol'] = $_POST['id_rol'];
        }

        // Ejecutar actualización si hay datos
        if (!empty($updates)) {
            $sql = "UPDATE usuario SET " . implode(', ', $updates) . " WHERE id_documento = :documento";
            $params[':documento'] = $documento;

            $actualizacion = $con->prepare($sql);
            $actualizacion->execute($params);
        } else {
            echo "No hay datos para actualizar.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar foto de perfil
$foto = $con->prepare("SELECT foto FROM usuario WHERE id_documento = :documento");
$foto->bindParam(':documento', $documento, PDO::PARAM_STR);
$foto->execute();

$resultado = $foto->fetch(PDO::FETCH_ASSOC);
$fotoActual = $resultado['foto'];

$mostar = "../../uploads/" . $fotoActual;
$usu = "../../uploads/usu.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Datos</title>
    <link rel="stylesheet" href="css/actualizar.css">
</head>
<body>

    <section class="mainSection">
        <div class="personaCard">
            <div class="FotoDePerfil">
                <div class="ContainerImagen">
                    <img class="imgDeLaPersona" id="fotoUsuario" src="<?php echo !empty($resultado['foto']) ? $mostar : $usu ?>" alt="Foto de perfil">
                    <div class="containerr">
                        <form action="../../uploadsAdmin/upload.php?" style="margin-top: 6px;" method="POST" enctype="multipart/form-data">
                            <label class="custom-file-upload">
                                <input type="file" name="foto" id="fileInput" onchange="previewFoto(event)" />
                                Subir Foto
                            </label>
                            <button type="submit" class="guardar" id="botonGuardar" name="submit">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="inputsDatos">
                <form action="" method="POST">
                    <div class="column">
                        <p class="TipoCampo">Documento</p>
                        <input name="id_documento" class="inputsClaseGeneral" type="text" value="<?php echo $fila['id_documento']; ?>" >
                    </div>
                    <div class="column">
                        <p class="TipoCampo">Nombre</p>
                        <input name="nombre_completo" class="inputsClaseGeneral" type="text" value="<?php echo $fila['nombre_completo']; ?>" >
                    </div>
                    <div class="column">
                        <p class="TipoCampo">Correo</p>
                        <input name="correo" class="inputsClaseGeneral" type="text" value="<?php echo $fila['correo']; ?>" >
                    </div>
                    <div class="column">
                    <p class="TipoCampo">Ficha</p>
                        <select name="ficha" class="inputsClaseGeneral"  id="">
                            <option class="" value="">Ficha Actual:  <?php echo $fila['ficha']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM fichas WHERE n_ficha != :n_ficha");
                            $sqlrol->bindParam(':n_ficha', $fila['n_ficha']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['n_ficha']; ?> " ><?php echo $resu['nombre_formacion']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="column">
                        <p class="TipoCampo">Código de Barras</p>
                        <input name="codigo_barras" class="inputsClaseGeneral" type="text" value="<?php echo $fila['codigo_barras']; ?>" >
                    </div>
                    <div class="column">
                    <p class="TipoCampo">Estado</p>
                        <select name="id_estado" class="inputsClaseGeneral"  id="">
                            <option class="" value="">Estado Actual:  <?php echo $fila['estado']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM estado WHERE id_estado != :id_estado");
                            $sqlrol->bindParam(':id_estado', $fila['id_estado']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['id_estado']; ?> " ><?php echo $resu['estado']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div >
                    <div class="column">
                        <p class="TipoCampo">Roles</p>
                        <select name="id_rol" class="inputsClaseGeneral"  id="">
                            <option  value="">Rol Actual:  <?php echo $fila['nombre_rol']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM rol WHERE id_rol != :id_rol");
                            $sqlrol->bindParam(':id_rol', $fila['id_rol']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['id_rol']; ?> " ><?php echo $resu['nombre_rol']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>

                    <input type="submit" name="submit" class="enviarConfig" value="Confirmar">
                </form>
            </div>
        </div>
    </section>

    <script>
        let botonGuardar = document.getElementById("botonGuardar");

        function previewFoto(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const fotoUsuario = document.getElementById("fotoUsuario");

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoUsuario.src = e.target.result;
                };

                botonGuardar.classList.add("botonGuardarActivo");
                reader.readAsDataURL(file);
            } else {
                fotoUsuario.src = "<?php echo $usu; ?>";
            }
        }
    </script>
</body>
</html>
