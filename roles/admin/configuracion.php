    <?php
    include ('../../database/database.php');
    $conexion = new database;
    $con = $conexion->conectar();
    session_start();


    $_SESSION['referer'] = $_SERVER['PHP_SELF'];
    $codigo = $_SESSION['documento'];

    $sql = $con->prepare("SELECT * FROM usuario INNER JOIN rol ON rol.id_rol = usuario.id_rol WHERE id_documento = :documento");
    $sql->bindParam(':documento', $codigo);
    $sql->execute();

    $fila = $sql->fetch(PDO::FETCH_ASSOC);




    if (isset($_POST['submit'])){
    try{
        $updates = [];
        $params = [];

        if(!empty($_POST['nombre_completo'])){
            $updates[] = 'nombre_completo = :nombre_completo';
            $params['nombre_completo'] = $_POST['nombre_completo'];
        }

        if(!empty($_POST['id_documento'])){
            $updates[] = 'id_documento = :id_documento';
            $params['id_documento'] = $_POST['id_documento'];
        }

        if(!empty($_POST['id_rol'])){
            $updates[] = 'id_rol = :id_rol';
            $params['id_rol'] = $_POST['id_rol'];
        }

        if(!empty($_POST['correo'])){
            $updates[] = 'correo = :correo';
            $params['correo'] = $_POST['correo'];
        }

        $actualizacion = $con->prepare("UPDATE usuario SET ". implode(', ', $updates) . " WHERE id_documento = :documento"); 
        $params[':documento'] = $codigo;
        $actualizacion->execute($params);   

    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
    }

    ?>



<?php 

    if(isset($_GET['mensaje'])){
        echo "<script>alert('$_GET[mensaje2]')</script>";
    }

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Configuracion</title>
        <link rel="stylesheet" href="css/home.css">
        <link rel="stylesheet" href="asidebar/asidebar.css">
        <link rel="stylesheet" href="css/configuracion.css">
    </head>
    <body>
        
    <div class="container_grid">
        
        <aside class="aside_barra">
            <?php  include ('asidebar/asideBar.php')?>
        </aside>

        

    


        <main class="grid_contenido_plantilla">
            
            <div class="tituloConfig"><h1 style="color: #63AEE8 ;">Configuracion</h1></div>
            
            <section class="mainConfig">
                <div class="configuracion">

                    <form action="" method="POST" class="formConfig">
                        <input name="nombre_completo" class="inpustConfig" placeholder="<?php echo $fila['nombre_completo']; ?> " type="text">

                        <input name="id_documento" id="documento" class="inpustConfig" placeholder="<?php echo 'Documento: '. $fila['id_documento'] ;?>" type="Number">

                        <select name="id_rol" class="inpustConfig"  id="">
                        <option value="">Rol actual:  <?php echo $fila['nombre_rol']; ?></option>
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

                        <input name="correo" class="inpustConfig" placeholder="<?php echo "Email: " . $fila['correo'];  ?>" type="Email">
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
                            <img class="personaAzul" id="fotoUsuario"  src="<?php echo !empty($resultado['foto']) ? $mostar : $usu  ?>" alt="Foto de perfil">
                            
                            <div class="containerr">
                                <form action="../../uploads/upload.php" style="margin-top: 6px;" method="POST" enctype="multipart/form-data">
                                    <label class="custom-file-upload">
                                        <input type="file" name="foto" id="fileInput" onchange="previewFoto(event)" />
                                        Subir Foto
                                    </label>
                                    <button type="submit" class="guardar" id="botonGuardar"  name="submit">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <img  class="imgConfig" src="../../assets/img/configuracion/imgconfi.png" alt="Img configuraciones" width="400px" height="400px">
            </section>        

        </main>
            
        </div>
        
        
        
        
    <script src="pruebas/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../js/js.js"></script>


    <script>
        let documento = document.getElementById('documento');
        documento.disabled = true;

        let botonGuardar = document.getElementById("botonGuardar");


        function previewFoto(event){
            const fileInput = event.target;
            const file = fileInput.files[0];
            const fotoUsuario = document.getElementById("fotoUsuario");

            if (file){
                const reader = new FileReader()
                reader.onload = function(e){
                    fotoUsuario.src = e.target.result
                }

                botonGuardar.classList.add("botonGuardarActivo")
                reader.readAsDataURL(file);
            }else{
                fotoUsuario.src = "<?php echo $usu; ?>"
            }
        }
    </script>

    </body>
    </html>

    