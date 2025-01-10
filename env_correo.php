<?php include('database/database.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFR - Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/env_correo.css">
    <link rel="stylesheet" href="css/env_correo.css">
</head>
<body>

   <?php include('template/header.php')?>

    <section class="container">
        <!-- Sección izquierda, para el formulario de recuperación -->
        <section class="ColumnaIzquierda">
            <img src="assets/img/imgRegistro/Base_White (1).png" class="fondoBlanco">
            
            <div class="sectionContraseña">
                <div>
                    <h2 class="Tcontraseña">Recuperar Contraseña</h2>
                </div>

                <!-- Formulario para ingresar el código de verificación -->
                <form action="cam_contraseña.php" method="post" onsubmit="return validarFormulario()"> 

                    <div class="codigo">
                        <div class="digite-cod">
                            <label for="Codigo">Digite el código de verificación</label>
                        </div>
                        <input type="number" class="datos" name="Codigo" id="Codigo" placeholder="****" >
                    </div>

                    <div class="Enviar">
                        <input type="submit" name="verificar" class="BotonEnviar" value="Aceptar">
                    </div>
                    
                </form>

            </div>
        </section>

        <!-- Sección derecha, con la imagen -->
        <section class="columnaDerecha">
            <img src="assets/img/recuperarContraseña/Image_RC.png" height="500px" width="679px" class="imgPersonitas" alt="Imagen de recuperación de contraseña">
        </section>

    </section>

    <!-- Barra de redes sociales -->
    <div class="social-bar">
        <a href="https://www.tiktok.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/tik-tok.png" width="28px" height="28px" alt="TikTok">
        </a>
        <a href="https://www.facebook.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/facebook.png" width="28px" height="28px" alt="Facebook">
        </a>
        <a href="https://www.instagram.com" class="social-icon">
            <img src="assets/img/iconosRedesSociales/logotipo-de-instagram.png" width="28px" height="28px" alt="Instagram">
        </a>
    </div>

    <?php include("template/footer.php") ?>


</body>
</html>
