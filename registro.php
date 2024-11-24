<?php include('database/database.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/registroStyle.css">
</head>
<body>

<?php include('template/header.php')?>


<div class="center_square">
        <img src="assets/img/indeximg/Center_Square.png" class="img_center_square" alt="">
    </div>

    
    <section class="section">

        <div class="CuadradoAzul">

        <div class="fondoBlanco">
            <img src="assets/img/imgRegistro/Base_White (1).png" class="imgFondoBlanco" height="100px" width="100px">
        </div>

        <section class="sectionLogin">

    <div class="labelRegistro">
        <label for="">¡Regístrate!</label>
    </div>

    <form action="" method="post" onsubmit="return redirigir()"> 

        <div class="documento-contraseña">
            <input type="text" class="input-documento-contraseña" name="nombreCompleto" placeholder="Nombre Completo">
            <input type="number" class="input-documento-contraseña" name="documento"  placeholder="Documento"/>
            <input type="email" class="input-documento-contraseña" name="correo"  placeholder="Email"/>
            <input type="text" class="input-documento-contraseña" name="rol"  placeholder="Rol"/>
            <input type="number" class="input-documento-contraseña" name="ficha"  placeholder="Ficha"/>
            <input type="password" class="input-documento-contraseña" name="contraseña" id="password" placeholder="Contraseña"/>
            <input type="password" class="input-documento-contraseña" name="contraseña" id="Confirmar password" placeholder="Confirmar Contraseña"/>
        </div>

        <div class="BotonRegistro">
            <input type="submit" value="Registrarse" class="boton-submit">
        </div>
        
    </form>
</section>


          
        </div>

    

    </section>

    <section class="columnaDerecha">
        <img src="assets/img/imgRegistro/Image_RG.png" height="597px" width="515px" class="imgPersonitas" alt="">
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

    
    <footer>
        <img src="assets/img/indeximg/Info_Pag.png" alt="" class="info" height="110px" width="400px">
        <img src="assets/img/indeximg/Copyright.png"  class="copyrigh" height="15px" width="200px">
    </footer>

    
<script src="js/js.js"></script>
</body>

</body>
</html>