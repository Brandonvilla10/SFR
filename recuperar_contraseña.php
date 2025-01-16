<?php include('database/database.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/recuperar_contraseña.css">
</head>
<body>

<?php include('template/header.php')?>

<section class="container">
    <!-- Contenedor principal con dos secciones: una para el formulario y otra para la imagen -->
    <section class="ColumnaIzquierda">
        <img src="assets/img/imgRegistro/Base_White (1).png" class="fondoBlanco">
        
        <div class="sectionContraseña">
            <div>
                <h2 class="Tcontraseña">Recuperar Contraseña</h2>
            </div>

            <!-- Formulario para recuperar contraseña -->
            <form action="" method="post" onsubmit="return redirigir()"> 
                <div class="inputs">
                    <input type="email" class="datos" name="correo" placeholder="@soy.sena.edu.co" required>
                </div>
        
                <div class="Enviar">
                    <input type="submit" value="Enviar Correo" class="BotonEnviar">
                </div>
                
                <div class="registrarse">
                    <a href="index.php" class="">¡Inicia Sesión!</a>
                </div>
            </form>
        </div>
    </section>

    <section class="columnaDerecha">
        <img src="assets/img/recuperarContraseña/Image_RC.png" height="500px" width="679px" class="imgPersonitas" alt="Imagen de recuperación de contraseña">
    </section>

</section>

<div class="social-bar">
    <a href="https://www.tiktok.com" target="_blank" class="social-icon">
        <img src="assets/img/iconosRedesSociales/tik-tok.png" width="28px" height="28px" alt="">
    </a>
    <a href="https://www.facebook.com" target="_blank" class="social-icon">
        <img src="assets/img/iconosRedesSociales/facebook.png" width="28px" height="28px" alt="">
    </a>
    <a href="https://www.instagram.com" class="social-icon">
        <img src="assets/img/iconosRedesSociales/logotipo-de-instagram.png" width="28px" height="28px">
    </a>
</div>

<?php require_once("template/footer.php") ?>

<script src="js/js.js"></script>
</body>
</html>