<?php
session_start();
include('../../database/database.php');
include 'components/alert.php';
include('../../includes/sesion_validar.php');

$conexion = new database;
$con = $conexion->conectar();

$documentoVentana = $_GET['documento'];

// Establecer la variable @documento_generador en MySQL desde la sesión
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
      if (!empty($_POST['fichaActual'])) {
            $fichaNueva = $_POST['fichaActual'];

            $consultaExistencia = $con->prepare("SELECT id_detalle FROM detallematricula WHERE id_documento = :id_documento AND id_estado = 1");
            $consultaExistencia->bindParam(':id_documento', $documentoVentana, PDO::PARAM_STR);
            $consultaExistencia->execute();
            $fichaActiva = $consultaExistencia->fetch(PDO::FETCH_ASSOC);

            if ($fichaActiva) {
                
                $actualizarFicha = $con->prepare("UPDATE detallematricula SET id_ficha = :id_ficha WHERE id_detalle = :id_detalle");
                $actualizarFicha->bindParam(':id_ficha', $fichaNueva, PDO::PARAM_STR);
                $actualizarFicha->bindParam(':id_detalle', $fichaActiva['id_detalle'], PDO::PARAM_INT);
                $actualizarFicha->execute();
            } else {
                
                $insertarDetalle = $con->prepare("INSERT INTO detallematricula (id_documento, id_ficha, id_estado) VALUES (:id_documento, :id_ficha, 1)");
                $insertarDetalle->bindParam(':id_documento', $documentoVentana, PDO::PARAM_STR);
                $insertarDetalle->bindParam(':id_ficha', $fichaNueva, PDO::PARAM_STR);
                $insertarDetalle->execute();
            }
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
                                        <select name="id_rol" id="id_rol" class="form-input">
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

                                        <?php

                                        $sqlficha = $con->prepare('SELECT * FROM detallematricula WHERE id_documento = :documento AND id_estado != 4 ORDER BY id_detalle DESC LIMIT 1');
                                        $sqlficha->bindParam(':documento', $documentoVentana, PDO::FETCH_ASSOC);
                                        $sqlficha->execute();
                                        $resultado = $sqlficha->fetch(PDO::FETCH_ASSOC);

                                        
                                        if($fila['id_rol'] == 2){ ?>
                                            
                                            
                                        
                                        
                                        
                                        

                                        <label for="ficha" class="form-label">Ficha</label>

                                      <div class="buscarFicha" id="buscarFicha">
                                            <input name="fichaActual" id="fichaActual" type="number" readonly value="<?php echo ($resultado) ? $resultado['id_ficha'] : "No Asignada"  ?>">

                                            <div class="fichaContenido">

                                                <input type="number" oninput="buscarFichaFunction(this.value)" class="inputBuscar" placeholder="Buscar Ficha">

                                                <div class="contenedorResultado" id="contenedorResultado">

                                                    <?php
                                                    $sqlficha = $con->prepare('SELECT * FROM fichas WHERE id_estado != 4');
                                                    $sqlficha->execute();
                                                    $resultado = $sqlficha->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($resultado as $resu) {
                                                    ?>


                                                        <button class="busquedaFicha" value="<?php echo $resu['n_ficha'] ?>"> <?php echo $resu['n_ficha'] ?> </button>


                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        
                                        <?php }?>
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



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const buscarFicha = document.getElementById("buscarFicha");
                const fichaContenido = document.querySelector(".fichaContenido");

                buscarFicha.addEventListener("change", () => {
                    if (fichaContenido.style.visibility == "hidden") {
                        fichaContenido.style.display = "block"
                        fichaContenido.style.opacity = 1
                    } else {
                        fichaContenido.style.display = "none"
                        fichaContenido.style.opacity = 0
                    }


                })

                const id_rol = document.getElementById("id_rol")
              

                fichaContenido.addEventListener("click", (e) => {
                    e.stopPropagation()
                })

                const fichaActual = document.getElementById("fichaActual")

                function fichaAsignar(ficha) {

                    const valorNumerico = parseInt(ficha, 10);

                    if (!isNaN(valorNumerico)) {
                        fichaActual.value = valorNumerico;

                    }
                }

                const busquedaFicha = document.querySelectorAll(".busquedaFicha")
                busquedaFicha.forEach((elemento) => {
                    elemento.addEventListener("click", (e) => {
                        fichaAsignar(elemento.value)
                        e.preventDefault()
                    })
                })




            })


            function fichaAsignar(ficha) {

                const valorNumerico = parseInt(ficha, 10);

                if (!isNaN(valorNumerico)) {
                    fichaActual.value = valorNumerico;

                }
            }

            const contenedorResultado = document.getElementById("contenedorResultado")

            async function buscarFichaFunction(filtro) {

                const response = await fetch("./fetch/consultaFichas.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: filtro
                })
                const data = await response.json()
                

                contenedorResultado.innerHTML = ""
                data.forEach(element => {
                    contenedorResultado.innerHTML += `
                    
                    <button type="button" class="busquedaFicha"  value="${element.n_ficha}"> ${element.n_ficha} </button>

                    `
                });

                document.querySelectorAll(".busquedaFicha").forEach((elemento) => {
                    elemento.addEventListener("click", (e) => {
                        e.preventDefault();
                        fichaAsignar(elemento.value);
                    });
                });
            }

            id_rol.addEventListener("change", () => {
                if (id_rol.value == 2) {
                    buscarFicha.style.visibility = "visible"
                } else {
                    buscarFicha.style.visibility = "hidden"; fichaActual.value = null
                }
            })



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