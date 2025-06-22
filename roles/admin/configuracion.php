<?php
// Validaciones-------------------------------------------
session_start();
include('../../database/database.php');
include('components/alert.php');
include('../../includes/sesion_validar.php');

// ---------------------------------

$conexion = new database;
$con = $conexion->conectar();

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

$codigo = $_SESSION['documento'];

$sql = $con->prepare("SELECT * FROM usuario INNER JOIN rol ON rol.id_rol = usuario.id_rol WHERE id_documento = :documento");
$sql->bindParam(':documento', $codigo);
$sql->execute();

$fila = $sql->fetch(PDO::FETCH_ASSOC);




if (isset($_POST['submit'])) {
    try {
        $updates = [];
        $params = [];

        if (!empty($_POST['nombre_completo'])) {
            $updates[] = 'nombre_completo = :nombre_completo';
            $params['nombre_completo'] = $_POST['nombre_completo'];
        }

        if (!empty($_POST['id_documento'])) {
            $updates[] = 'id_documento = :id_documento';
            $params['id_documento'] = $_POST['id_documento'];
        }

        if (!empty($_POST['id_rol'])) {
            $updates[] = 'id_rol = :id_rol';
            $_SESSION['rol'] = $_POST['id_rol'];
            $params['id_rol'] = $_POST['id_rol'];
        }

        if (!empty($_POST['correo'])) {
            $updates[] = 'correo = :correo';
            $params['correo'] = $_POST['correo'];
        }

        $actualizacion = $con->prepare("UPDATE usuario SET " . implode(', ', $updates) . " WHERE id_documento = :documento");
        $params[':documento'] = $codigo;
        $actualizacion->execute($params);
        
         echo "
                
            <script>
            
             document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Usuario Actualizado con exito', 'configuracion.php');
            });
            </script>
        ";
    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuracion</title>
    <link rel="stylesheet" href="css/configuracion.css">
    <link rel="stylesheet" href="asidebar/asidebar.css">

</head>

<body>

    <div class="container_grid">

        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>






        <main class="grid_contenido_plantilla">

            <div class="tituloConfig">
                <h1 style="color: #63AEE8 ;">Configuracion</h1>
            </div>

            <section class="mainConfig">
                <div class="configuracion">

                    <form action="" method="POST" class="formConfig" id="formConfig">
                        <input name="nombre_completo" class="inpustConfig" id="nombre" value= "<?php echo $fila['nombre_completo']; ?>" type="text">

                        <input name="id_documento" id="documento" class="inpustConfig" placeholder="<?php echo 'Documento: ' . $fila['id_documento']; ?>" type="Number">

                        <select name="id_rol" class="inpustConfig" id="rol">
                            <option value="">Rol actual: <?php echo $fila['nombre_rol']; ?></option>
                            <?php
                            $sqlrol = $con->prepare("SELECT * FROM rol WHERE id_rol != :id_rol");
                            $sqlrol->bindParam(':id_rol', $fila['id_rol']);
                            $sqlrol->execute();
                            $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sqlrol as $resu) {
                            ?>
                                <option value="<?php echo $resu['id_rol']; ?> "><?php echo $resu['nombre_rol']; ?> </option>

                            <?php
                            }
                            ?>
                        </select>

                        <input name="correo" type="email"  id="correo" class="inpustConfig" value="<?php echo  $fila['correo'];  ?>" >
                        <input type="submit" name="submit" class="enviarConfig" value="Confirmar">
                    </form>


                    <!-- Aqui voy a dejar la consulta para sacar la foto de perfil -->

                    <?php

                    $foto = $con->prepare("SELECT foto FROM usuario Where id_documento = $codigo");
                    $foto->execute();

                    $resultado = $foto->fetch(PDO::FETCH_ASSOC);
                    $fotoActual = $resultado['foto'];

                    $mostar = "../../uploads/" . $fotoActual;
                    $usu = "../../uploads/usu.png";


                    ?>

                    <div class="fotoPerfil">
                        <div class="ContainerImagen">
                            <img class="personaAzul" id="fotoUsuario" src="<?php echo !empty($resultado['foto']) ? $mostar : $usu  ?>" alt="Foto de perfil" onerror="this.src='../../uploads/usu.png'">

                            <div class="containerr">
                                <form action="../../uploads/upload.php" style="margin-top: 6px;" method="POST" enctype="multipart/form-data" id="form-foto">
                                    <label class="custom-file-upload">
                                        <input type="file" name="foto" id="fileInput" onchange="document.getElementById('form-foto').submit()" />
                                        Subir Foto
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <img class="imgConfig" src="../../assets/img/configuracion/imgconfi.webp" alt="Img configuraciones" width="400px" height="400px">
            </section>

        </main>

    </div>




   
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   


    <script>
        function limitar(elemento, limite) {
            elemento.addEventListener("input", () => {
                if (elemento.value.length > limite) {
                    elemento.value = elemento.value.substring(0, limite)
                }

            })
        }

        let documento = document.getElementById('documento');
        documento.disabled = true;

        let rol = document.getElementById('rol');
        rol.readonly = true;

        const nombre = document.getElementById('nombre');
        nombre.readonly = true;
        
        limitar(nombre, 50);
        
        
        const correo = document.getElementById("correo")
        limitar(correo, 50);

     
       const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
       const formulario = document.getElementById('formConfig');

       
        correo.addEventListener("blur", function () {
            
            if (!regex.test(this.value)) {
                mostrarModal("mistake", "Correo no válido, letras,numeros y caracteres especiales permitidos",'');
            
                   console.log("entro aca");
            }
               console.log("entro aca");
        });
        
        formulario.addEventListener("submit", function (e) {
            
            if (!regex.test(correo.value)) {
                e.preventDefault(); // Detiene el envío
                mostrarModal("mistake", "No se puede enviar el formulario. Correo inválido.");
            }
        });

     


      
           
    </script>

</body>

</html>