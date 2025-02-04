<?php 
include ('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

$documentoVentana = $_GET['documento'];
$_SESSION['documentoUpload'] = $documentoVentana;

// Consulta principal para obtener los datos del usuario
$sql = $con->prepare("SELECT usuario.*, usuario.foto, fichas.n_ficha, programa.*,rol.nombre_rol,estado.estado, nivelformacion.*, 

jornada.*  FROM usuario            INNER JOIN rol ON rol.id_rol = usuario.id_rol
                                        INNER JOIN fichas ON fichas.n_ficha = usuario.ficha
                                        INNER JOIN estado ON estado.id_estado = usuario.id_estado
                                        INNER JOIN programa ON fichas.id_programaFormacion = programa.id_programaFormacion
                                        INNER JOIN nivelformacion ON fichas.id_nivelFormacion = nivelformacion.id_nivelFormacion
                                        INNER JOIN jornada ON fichas.id_jornada = jornada.id_jornada
                                        WHERE id_documento = '$documentoVentana'");
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
            $params[':documento'] = $documentoVentana;

            $actualizacion = $con->prepare($sql);
            $actualizacion->execute($params);
        } else {
            echo "No hay datos para actualizar.";
        }

        if(!empty($_POST['jornada'])){
            $jornada = $_POST['jornada'];        
            $sql = $con->prepare("UPDATE fichas SET id_jornada = $jornada WHERE n_ficha = :n_ficha ");;
            $sql->execute([':n_ficha' => $fila['n_ficha']]);
        }

        if(!empty($_POST['nivelDeformacion'])){
            $nivelDeformacion = $_POST['nivelDeformacion'];        
            $sql = $con->prepare("UPDATE fichas SET id_nivelFormacion = $nivelDeformacion WHERE n_ficha = :n_ficha  ");;
            $sql->execute([':n_ficha' => $fila['n_ficha']]);
        }

        if(!empty($_POST['programa'])){
            $programa = $_POST['programa'];        
            $sql = $con->prepare("UPDATE fichas SET id_programaFormacion = $programa WHERE n_ficha = :n_ficha  ");;
            $sql->execute([':n_ficha' => $fila['n_ficha']]);
        }

        echo "<script>window.opener.location.reload()</script>";
        echo "<script>window.opener.location.reload()</script>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar foto de perfil
$foto = $con->prepare("SELECT foto FROM usuario WHERE id_documento = :documento");
$foto->bindParam(':documento', $documentoVentana, PDO::PARAM_STR);
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

    <div class="backgrond">
        <div class="personaCard">
            <div class="FotoDePerfil">
                <div class="ContainerImagen">
                    <img class="imgDeLaPersona" id="fotoUsuario" src="<?php echo !empty($resultado['foto']) ? $mostar : $usu ?>" alt="Foto de perfil">
                    <div class="containerr">
                        <form action="../../uploadsAdmin/upload.php?documento=<?php echo $documentoVentana ?>" style="margin-top: 6px;" method="POST" enctype="multipart/form-data">
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
                        <input id="documento" name="id_documento" class="inputsClaseGeneral" type="text" value="<?php echo $fila['id_documento']; ?>" >
                    </div>
                    <div class="column">
                        <p class="TipoCampo">Nombre</p>
                        <input id="nombreCompleto" name="nombre_completo" class="inputsClaseGeneral" type="text" value="<?php echo $fila['nombre_completo']; ?>" >
                    </div>

                    <div class="column">
                        <p class="TipoCampo">Correo</p>
                        <input id="correo" name="correo" class="inputsClaseGeneral" type="text" value="<?php echo $fila['correo']; ?>" >
                    </div>
                   
                    <div class="column">
                    <p class="TipoCampo">Formacion</p>
                        <select name="programa" class="inputsClaseGeneral"  id="">
                            <option class="" value=""><?php echo $fila['nombrePrograma']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM programa  WHERE id_programaFormacion != :id_programaFormacion");
                            $sqlrol->bindParam(':id_programaFormacion', $fila['id_programaFormacion']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['id_programaFormacion']; ?> "><?php echo $resu['nombrePrograma']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                    <p class="TipoCampo">Nivel De Formacion</p>
                        <select name="nivelDeformacion" class="inputsClaseGeneral"  id="">
                            <option class="" value=""><?php echo $fila['nivelDeformacion']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM nivelformacion  WHERE id_nivelFormacion != :id_nivelFormacion");
                            $sqlrol->bindParam(':id_nivelFormacion', $fila['id_nivelFormacion']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['id_nivelFormacion']; ?> "><?php echo $resu['nivelDeformacion']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                    <p class="TipoCampo">Ficha</p>
                        <select name="ficha" class="inputsClaseGeneral"  id="">
                            <option class="" value="">Ficha Actual:  <?php echo $fila['ficha']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM fichas INNER JOIN programa on programa.id_programaFormacion = fichas.id_programaFormacion WHERE n_ficha != :n_ficha");
                            $sqlrol->bindParam(':n_ficha', $fila['n_ficha']);
                            $sqlrol->execute();
                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['n_ficha']; ?> " ><?php echo $resu['n_ficha']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="column">
                    <p class="TipoCampo">Jornada</p>
                        <select name="jornada" class="inputsClaseGeneral"  id="">
                            <option class="" value="">Jornada Actual:  <?php echo $fila['jornada']; ?></option>
                            <?php 
                            $sqlrol = $con->prepare("SELECT * FROM jornada  WHERE jornada != :jornada");
                            $sqlrol->bindParam(':jornada', $fila['id_jornada']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sqlrol as $resu){
                            ?>
                                <option value="<?php echo $resu['id_jornada']; ?>" ><?php echo $resu['jornada']; ?> </option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                        <p class="TipoCampo">Código de Barras</p>
                        <input id="codigoDeBarras" name="codigo_barras" class="inputsClaseGeneral" type="text" value="<?php echo $fila['codigo_barras']; ?>" >
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

                    <input id="submit" type="submit" name="submit" class="enviarConfig" value="Confirmar">
                </form>
            </div>
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

<script>
    
    const expresiones = {
    documento: /^\d{6,10}$/,
    nombre: /^[a-zA-ZÀ-ÿ\s]{4,40}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%?&])[A-Za-z\d@#$!%?&]{8,16}$/,
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
    }

const documento = document.getElementById("documento");
const nombreCompleto = document.getElementById("nombreCompleto");
const correo = document.getElementById("correo");
const codigoDeBarras = document.getElementById("docucodigoDeBarrasmento");
const submit = document.getElementById("submit");



submit.addEventListener("click",(e)=>{
    
    if(!expresiones.documento.test(documento.value) || documento.value == "" || !expresiones.nombre.test(nombreCompleto.value) || nombreCompleto.value == "" || !expresiones.correo.test(correo.value) || correo.value == "" || !expresiones.documento.test(codigoDeBarras.value) || codigoDeBarras.value == ""){
        validar =  false
    }

    if(validar == false){
        e.preventDefault()
        submit.disabled = true
        setTimeout(() => {
            submit.disabled = false
        }, 1000);
    }else{
        submit.disabled = false
    }
    
})

</script>
</body>
</html>
