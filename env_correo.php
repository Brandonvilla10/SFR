<?php 
require_once('database/database.php');
$conexion = new dabatase;
$con = $conexion->conectar();

// Validación en PHP para verificar si el código ingresado es correcto
if(isset($_POST['verificar'])) {
    $codigo = $_POST['Codigo'];

    // Verificar si el código es correcto, esto dependerá de cómo se maneja el código en tu sistema
    if($codigo == '123456'){  // Esto es solo un ejemplo. Aquí deberías hacer la lógica real.
        header('Location: cam_contraseña.php');  // Redirigir a la página para cambiar la contraseña
        exit();
    } else {
        echo "<script>alert('Código de verificación incorrecto.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFR - Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/env_correo.css">
</head>
<body>

   <?php include('template/header.php')?>

    <div class="center_square">
        <img src="assets/img/indeximg/Center_Square.png" class="img_center_square" alt="Centro">
    </div>

    <section class="section">
        <div class="CuadradoAzul">
            <div class="fondoBlanco">
                <img src="assets/img/Base_White.png" class="imgFondoBlanco" height="100px" width="100px">
            </div>

            <section class="sectionrecuperar">

                <div class="labelrecuperar">
                    <label for="Codigo">Recuperar Contraseña</label>
                </div>

                <form action="cam_contraseña.php" method="post" onsubmit="return validarFormulario()"> 

                    <div class="codigo">
                        <div class="digite-cod">
                            <label for="Codigo">Digite el código de verificación</label>
                        </div>
                        <input type="number" class="input-codigo" name="Codigo" id="Codigo" placeholder="*******" required>
                    </div>

                    <div class="Aceptar">
                        <input type="submit" name="verificar" class="boton-submit" value="Aceptar">
                    </div>
                    
                </form>

            </section>
        </div>
    </section>

    <section class="columnaDerecha">
        <img src="assets/img//recuperarContraseña/Image_RC.png" height="500px" width="679px" class="imgPersonitas" alt="Imagen de recuperación de contraseña">
    </section>

    <div class="social-bar">
        <a href="https://www.facebook.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/tik-tok.png" width="28px" height="28px" alt="TikTok">
        </a>
        <a href="https://www.instagram.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/facebook.png" width="28px" height="28px" alt="Facebook">
        </a>
        <a href="https://www.tiktok.com" class="social-icon">
            <img src="assets/img/iconosRedesSociales/logotipo-de-instagram.png" width="28px" height="28px" alt="Instagram">
        </a>
    </div>

    <?php include("template/footer.php") ?>

<script>
    // Función para validar el formulario
    function validarFormulario() {
        var codigo = document.getElementById('Codigo').value;

        // Verificar si el código no está vacío
        if (codigo == "") {
            alert('Por favor ingrese el código de verificación.');
            return false;
        }

        return true;  // Si todo es correcto, el formulario se enviará
    }
</script>

</body>
</html>

