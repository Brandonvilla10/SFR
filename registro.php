<?php include('database/database.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/registroStyle.css">
</head>
<body>

<?php include('template/header.php')?>

    
    
    <section class="container">
        <!-- cree un section llamado container que contuviera ambos section los cuales son los de columna derecha y columna izquierda que tambien lo agregue ya que teniamos era un div llamado cuadrado azul y no se podia acomodar de la mejor manera  -->
        <section class="ColumnaIzquierda">
<!-- 
        elimine el div en el que estaba la imagen ya que no era necesario, asi mismo elimine el cuadrado que iba en el centro porque al minimizar el ancho no habia forma en la que se viera bien -->
         <img src="assets/img/imgRegistro/Base_White (1).png" class="fondoBlanco">
        

        <div class="sectionRegistro">

         <div >
             <h2 class="RegistroTitulo">¡Regístrate!</h2>
         </div>
        
         <form action="" method="post" onsubmit="return redirigir()"> 
        
             <div class="inputs">
                 <input type="text" class="datos" name="nombreCompleto" placeholder="Nombre Completo">
                 <input type="number" class="datos" name="documento"  placeholder="Documento"/>
                 <input type="email" class="datos" name="correo"  placeholder="Email"/>
                 <input type="password" class="datos" name="contraseña" id="password" placeholder="Contraseña"/>
                 <input type="password" class="datos" name="contraseña" id="Confirmar password" placeholder="Confirmar Contraseña"/>
             </div>
        
             <div class="BotRegistro">
                 <input type="submit" value="Registrarse" class="BotonRegistro">
             </div>
             
             <div class="-contraseña">
             <p>¿Ya Tienes Cuenta?</p>
         </div>
        
         <div class="registrarse">
             <a href="index.php" class="">¡Inicia Sesion!</a>
         </div>
         </form>
        


        </div>
        </section>
        
       

    <section class="columnaDerecha">
        <img src="assets/img/imgRegistro/Image_RG.png" height="550px" width="515px" class="imgPersonitas" alt="">
    </section>
    
    </section>

    <div class="social-bar">
        <a href="https://www.tiktok.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/tik-tok.png" width="28px" height="28px alt="">
        </a>
        <a href="https://www.facebook.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/facebook.png" width="28px" height="28px alt="">
        </a>
        <a href="https://www.instagram.com" class="social-icon">
            <img src="assets/img/iconosRedesSociales/logotipo-de-instagram.png"  width="28px" height="28px">
        </a>
    </div>
    
    
    <?php require_once("template/footer.php") ?>
    
<script src="js/js.js"></script>
</body>


</html>