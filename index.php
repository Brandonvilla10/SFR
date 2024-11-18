<?php require_once('../SFR/database/database.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFR</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


   <?php include('template/header.php')?>
   
    
   
    <div class="center_square">
        <img src="assets/img/indeximg/Center_Square.png" class="img_center_square" alt="">
    </div>

    
    <section class="section">

        <div class="CuadradoAzul">

        <div class="fondoBlanco">
            <img src="assets/img/Base_White.png" class="imgFondoBlanco" height="100px" width="100px">
        </div>

        <section class="sectionLogin">

    <div class="labelLogin">
        <label for="">Login</label>
    </div>

    <form action="" method="post"> 

        <div class="documento-contraseña">
            <input type="number" class="input-documento-contraseña" name="documento" placeholder="Documento">
            <input type="password" class="input-documento-contraseña" name="contraseña" id="password" placeholder="Contraseña"/>
        </div>

        <div class="inicioDeSesion">
            <input type="submit" value="Iniciar Sesión" class="boton-submit">
        </div>
        
    </form>

    <div class="-contraseña">
        <a href="recuperar_contraseña.php" class="">¿Olvidaste tu contraseña?</a>
    </div>
    <div class="registrarse">
        <a href="../SFR/registro.php" class="">¡Registrate!</a>
    </div>
</section>


          
        </div>

    

    </section>

    <section class="columnaDerecha">
        <img src="assets/img/indeximg/Image_Index.png" height="500px" width="679px" class="imgPersonitas" alt="">
    </section>
    

    <div class="social-bar">
        <a href="https://www.facebook.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/tik-tok.png" width="28px" height="28px alt="">
        </a>
        <a href="https://www.instagram.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/facebook.png" width="28px" height="28px alt="">
        </a>
        <a href="https://www.tiktok.com" class="social-icon">
            <img src="assets/img/iconosRedesSociales/logotipo-de-instagram.png"  width="28px" height="28px">
        </a>
    </div>

    


    <?php include("template/footer.php") ?>
<script src="js/js.js"></script>
</body>

</html>