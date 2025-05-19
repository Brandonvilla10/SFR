<?php
session_start();
include('../../database/database.php');
include 'components/alert.php';
$conexion = new database;
$con = $conexion->conectar();

$documentoVentana = $_GET['documento'];


//<-----------------------------AQUI ESTA LA RUTA ------------------------------------>
$ruta = $_GET['route'] ?? 'usuarios.php';

$_SESSION['documentoUpload'] = $documentoVentana;
// Consulta principal para obtener los datos del usuario
$sql = $con->prepare("SELECT 
    usuario.nombre_completo, 
    usuario.foto, 
    usuario.id_documento, 
    usuario.correo, 
    rol.*,     
    estado.*   
FROM usuario
LEFT JOIN estado ON estado.id_estado = usuario.id_estado
LEFT JOIN rol ON rol.id_rol = usuario.id_rol
WHERE usuario.id_documento = $documentoVentana ");

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


          
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Usuario actualizado con exito', '$ruta');
                });
            </script>";
            exit();
        } else {
            echo "No hay datos para actualizar.";
        }
    } catch (PDOException $e) {
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
    <link rel="stylesheet" href="asideBar/asidebar.css">
</head>

<body>



    <body>

        <div class="container_grid">

            <aside class="aside_barra">
                <?php include('asideBar/asideBar.php') ?>
            </aside>

            <main class="grid_contenido_plantilla">



                <div class="container">
                    <h1>Editar Usuario </h1>

                    <div class="content-container">

                        <div class="form-container">
                            <div class="image-preview">
                                <div class="image-column">

                                    <div class="image-upload">
                                        <img src="<?php echo (!empty($fotoActual)) ? $mostar : $usu ?>" alt="imagen Del usuario" class="imagen" id="imagen_objeto" onerror="this.src='../../uploads/usu.png'">
                                        <div class="overlay">
                                            <i class="bi bi-upload"></i>
                                            <span>Subir Foto</span>
                                        </div>
                                        <form action="../../uploads/upload_Usuario.php" method="POST" enctype="multipart/form-data" class="overlay" id="form-objetos">

                                            <input type="hidden" name="documento" value="<?php echo $documentoVentana ?>">
                                            <input type="text" name="ruta" value="<?php echo $ruta ?>" hidden>
                                            <input type="file" class="file_input" id="fileUpload" name="foto" onchange="document.getElementById('form-objetos').submit()">

                                        </form>
                                    </div>
                                    <div class="image-title">Foto del usuario</div>
                                </div>
                            </div>


                            <div class="form-inputs">

                                <form action="" method="POST" enctype="multipart/form-data" class="grid-form">

                                    <label for="name" class="form-label">Documento </label>
                                    <input type="text" id="name" class="form-input" placeholder="nombre" value="<?php echo $fila['id_documento']; ?>" readonly>
                                    <input type="text" id="name" name="id_documento" class="form-input" placeholder="nombre" value="<?php echo $fila['id_documento']; ?>" hidden>

                                    <label for="" class="form-label">Nombre</label>
                                    <input type="text" name="nombre_completo" class="form-input" value="<?php echo $fila['nombre_completo']; ?>">

                                    <label for="" class="form-label">correo</label>
                                    <input type="text" class="form-input caracteristica" name="correo" placeholder="Escribe aquí..." value="<?php echo $fila['correo']; ?>">



                                    <div class="form-group">

                                        <label for="" class="form-label">Estado</label>
                                        <select name="id_estado" id="" class="form-input">
                                            <option value="<?php echo $fila['id_estado']; ?>">
                                                <?php echo $fila['estado']; ?>
                                            </option>


                                            <?php
                                            $sqlrol = $con->prepare("SELECT * FROM estado WHERE id_estado != $fila[id_estado] and id_estado != 3 and id_estado != 4;");
                                            $sqlrol->execute();
                                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($sqlrol as $resu) {
                                            ?>
                                                <option value="<?php echo $resu['id_estado']; ?> "><?php echo $resu['estado']; ?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">


                                        <label for="rol" class="form-label">rol</label>
                                        <select name="id_rol" id="" class="form-input">
                                            <option value="<?php echo $fila['id_rol']; ?>">
                                                <?php echo $fila['nombre_rol']; ?>
                                            </option>

                                            <?php
                                            $sqlrol = $con->prepare("SELECT * FROM rol WHERE id_rol != $fila[id_rol]");
                                            $sqlrol->execute();
                                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($sqlrol as $resu) {
                                            ?>
                                                <option value="<?php echo $resu['id_rol']; ?> "><?php echo $resu['nombre_rol']; ?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn-submit">
                                        <a href="<?php echo $ruta; ?>" class="btn-return">← Volver</a>

                                    </div>

                                </form>

                            </div>


                        </div>




                    </div>
                </div>
            </main>
        </div>

        <!-- <section class="mainSection">

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
                    <p class="TipoCampo">Ficha</p>
                        <select name="ficha" class="inputsClaseGeneral"  id="">
                            <option class="" value="">Ficha Actual:  <?php echo empty($fila['n_ficha']) ? "No tiene ficha" : $fila['n_ficha']; ?></option>
                            <?php

                            $sqlrol = $con->prepare("SELECT * FROM fichas   ");
                            // $sqlrol->bindParam(':n_ficha', $fila['n_ficha'], PDO::PARAM_INT);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($sqlrol as $resu) {
                            ?>
                                <option value="<?php echo $resu['n_ficha'] ?> " ><?php echo $resu['n_ficha']; ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                   
                    <div class="column">
                    <p class="TipoCampo">Formacion</p>
                        <select name="programa" class="inputsClaseGeneral"  id="">
                            <option class="" value=""><?php echo empty($fila['nombrePrograma']) ? "No Tiene Programa" : $fila['nombrePrograma']; ?></option>
                            <?php
                            $sqlrol = $con->prepare("SELECT * FROM programa ");
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sqlrol as $resu) {
                            ?>
                                <option value="<?php echo empty($resu['id_programaFormacion']) ? 'vacio' : $resu['id_programaFormacion']; ?> "><?php echo $resu['nombrePrograma']; ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                    <p class="TipoCampo">Nivel De Formacion</p>
                        <select name="nivelDeformacion" class="inputsClaseGeneral"  id="">
                            <option class="" value=""><?php echo empty($fila['nivelDeformacion']) ? "Sin Formacion" : $fila['nivelDeformacion']; ?></option>
                            <?php
                            $sqlrol = $con->prepare("SELECT * FROM nivelformacion ");
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sqlrol as $resu) {
                            ?>
                                <option value="<?php echo $resu['id_nivelFormacion']; ?> "><?php echo $resu['nivelDeformacion']; ?> </option>
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
                            $sqlrol = $con->prepare("SELECT * FROM jornada ");
                            // $sqlrol->bindParam(':jornada', $fila['id_jornada']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sqlrol as $resu) {
                            ?>
                                <option value="<?php echo $resu['id_jornada']; ?>" ><?php echo $resu['jornada']; ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                    <p class="TipoCampo">Estado</p>
                        <select name="id_estado" class="inputsClaseGeneral"  id="">
                            <option class="" value="">Estado Actual:  <?php echo $fila['estado']; ?></option>
                            <?php
                            $sqlrol = $con->prepare("SELECT * FROM estado ");
                            // $sqlrol->bindParam(':id_estado', $fila['id_estado']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sqlrol as $resu) {
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
                            $sqlrol = $con->prepare("SELECT * FROM rol ");
                            // $sqlrol->bindParam(':id_rol', $fila['id_rol']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sqlrol as $resu) {
                            ?>
                                <option value="<?php echo $resu['id_rol']; ?> " ><?php echo $resu['nombre_rol']; ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <input id="submit" type="submit" name="submit" class="enviarConfig"  value="Confirmar">
                </form>
            </div>
        </div>
    </div>
</section> -->


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



            submit.addEventListener("click", (e) => {

                if (!expresiones.documento.test(documento.value) || documento.value == "" || !expresiones.nombre.test(nombreCompleto.value) || nombreCompleto.value == "" || !expresiones.correo.test(correo.value) || correo.value == "" || !expresiones.documento.test(codigoDeBarras.value) || codigoDeBarras.value == "") {
                    validar = false
                }

                if (validar == false) {
                    e.preventDefault()
                    submit.disabled = true
                    setTimeout(() => {
                        submit.disabled = false
                    }, 1000);
                } else {
                    submit.disabled = false

                }


            })
        </script>
    </body>

</html>