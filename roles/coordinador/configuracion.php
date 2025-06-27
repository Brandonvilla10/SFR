<?php
session_start();
include('../../database/database.php');
include('components/alert.php');
include('../../includes/sesion_validar.php');


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
$sql->bindParam(':documento', $_SESSION['documento'], PDO::PARAM_INT);
$sql->execute();

$fila = $sql->fetch(PDO::FETCH_ASSOC);


if (isset($_POST['actualizarFoto'])) {
    $_SESSION['direccion'] = "../roles/coordinador/configuracion.php";    
}



if (isset($_POST['submit'])) {
    try {
        $updates = [];
        $params = [];


        if (!empty($_POST['correo'])) {
            $updates[] = 'correo = :correo';
            $params['correo'] = $_POST['correo'];
        }

        $actualizacion = $con->prepare("UPDATE usuario SET " . implode(', ', $updates) . " WHERE id_documento = :documento");
        $params[':documento'] = $codigo;
        $actualizacion->execute($params);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
if (isset($_POST['submitCambiar'])) {

    $contraActual = $_POST['contra-anti'];
    $contraNueva = $_POST['contra-nueva'];

    $sql = $con->prepare("SELECT * FROM usuario WHERE id_documento = :documento");
    $sql->bindParam(':documento', $codigo);
    $sql->execute();
    $fila = $sql->fetch();

    if (password_verify($contraActual, $fila['contraseña'])) {
        $nuevaContra = password_hash($contraNueva, PASSWORD_DEFAULT);

        $actualizar = $con->prepare("UPDATE usuario SET contraseña = :nuevaContra WHERE id_documento = :documento");
        $actualizar->bindParam(':nuevaContra', $nuevaContra);
        $actualizar->bindParam(':documento', $codigo);
        $actualizar->execute();

        echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('success', 'Contraseña cambiada con exito', 'configuracion.php');
                    });
                </script>";
        exit();
    } else {
        echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'La contraseña actual es incorrecta', 'configuracion.php');
                    });
                </script>";
        exit();
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
                        <input name="nombre_completo" type="text" class="inpustConfig" id="nombre" placeholder="<?php echo $fila['nombre_completo']; ?>" readonly>
                        <input name="id_documento" id="documento" class="inpustConfig" placeholder="<?php echo 'Documento: ' . $fila['id_documento']; ?>" type="Number" readonly>
                        <input name="id_rol" class="inpustConfig" id="rol" value ="<?php echo $fila['nombre_rol']; ?>" readonly>
                        <input name="correo" id="correo" class="inpustConfig" value="<?php echo $fila['correo'];  ?>" type="Email">
                        <h4 class="change" id="abrirCambiar">¿Deseas cambiar la contraseña?</h4>
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
                                <form action="../../uploadsGlobal/upload.php" style="margin-top: 6px;" method="POST" enctype="multipart/form-data" id="form-foto">

                                    <label class="custom-file-upload">
                                        <input type="file" name="foto" id="fileInput" onchange="document.getElementById('form-foto').submit()" />
                                        Subir Foto
                                        <input type="text" name="direccion" value="../roles/coordinador/configuracion.php" hidden>
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <img class="imgConfig" src="../../assets/img/configuracion/imgconfi.webp" alt="Img configuraciones" width="400px" height="400px">
            </section>

             <!------------------------------------------------ MODAL DE CAMBIAR CONTRA ----------------------------------->
    
             <div class="modalAgregar" id="modalAgregar">
                <div class="contenedormodalAgregar">
                    <div class="headermodalAgregar" style="background-color:#63AEE8;">
                        <h3>Cambiar Contraseña</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="" method="post" id="formCambiar">
                            <div class="campo-formulario">
                                <label for="nom_marca" >Contraseña Actual</label>
                                <input class="input-modal" type="password" id="contra-anti" name="contra-anti" placeholder="Digite su contraseña">
                            </div>
                            <div class="campo-formulario">
                                <label for="tipomarca">Contraseña Nueva</label>
                                <input class="input-modal" type="password" id="contra-nueva" name="contra-nueva" placeholder="Digite su nueva contraseña">
                            </div>
                        
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitCambiar" class="buscar-btn">Cambiar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </div>




    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    


    <script>
        const modalAgregar = document.getElementById('modalAgregar');
        const abrirBuscar = document.getElementById('abrirCambiar');
        const cerrarmodalAgregar = document.getElementById('cerrarmodalAgregar');
        const cancelarBuscar = document.getElementById('cancelarBuscar');

        function cerrarBuscar() {
            modalAgregar.style.visibility = 'hidden';
            modalAgregar.style.opacity = '0';
        }

        function abrirBuscarFunction() {
            modalAgregar.style.visibility = 'visible';
            modalAgregar.style.opacity = '1';
        }

        function cerrarBuscar(){
            modalAgregar.style.visibility = 'hidden';
            modalAgregar.style.opacity = '0';
        }

        abrirBuscar.addEventListener('click', abrirBuscarFunction);
        cerrarmodalAgregar.addEventListener('click', cerrarBuscar);
        cancelarBuscar.addEventListener('click', cerrarBuscar);

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

        const formCambiar = document.getElementById('formCambiar');
        const contraNueva= document.getElementById("contra-nueva");
        const contraActual = document.getElementById("contra-anti");
    
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const formulario = document.getElementById('formConfig');
        const contra = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@#$!%?&]{6,12}$/;

        formCambiar.addEventListener("submit", function (e) {
            if (contraActual.value.trim() === '' || contraNueva.value.trim() === '') {
                e.preventDefault();
                mostrarModal('mistake', 'Todos los campos de contraseña son obligatorios', '');
            }
            if (!contra.test(contraNueva.value)) {
                e.preventDefault();
                mostrarModal("mistake", "La contraseña debe tener mínimo 6 caracteres, una mayúscula y un número", '');
                return;
            }
        })
       
        correo.addEventListener("blur", function () {
            
            if (!regex.test(this.value)) {
                mostrarModal("mistake", "Correo no válido, letras,numeros y caracteres especiales permitidos");
          
            }
         
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