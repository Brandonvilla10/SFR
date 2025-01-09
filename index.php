
<?php
require_once("database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

if (isset($_POST['enviar'])){
    $documento = $_POST['documento'];
    $contraseña = $_POST['contraseña'];
    echo '<script>alert("Funcionando")</script>';

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFR</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    
</head>
<body>


   <?php include('template/header.php')?>
   
    
<section class="container">

<!-- en la columna izquierda tuve que usar una propiedad de z index en el div con clase "card", ya que la imagen(El fondo blanco) no estaba poniendose de manera adecuada asi que lo resolvi usando una posicion absoluta y colocando los elementos dentro de card encima de la imagen los elementos me refiero a los inputs-->

    <section class="columnaIzquierda">
        <img src="assets/img/Base_White.png" class="fondoBlanco" alt="">


        <div class="card">
            <!-- usar grid -->
            <div>
                <h2 class="tituloLogin">Login</h2>
            </div>


            <form action="" id="formulario" method="POST" >

                <div class="datos">
                    <input type="number" class="datosLogin" name="documento" id="documento" placeholder="Documento">
                    <p class="error" id="errorDocumento"></p>

                    <input type="text" class="datosLogin" name="contraseña" id="contraseña" placeholder="Contraseña">
                    <p class="error" id="errorContraseña"></p>
                </div>
        
                    <input class="botonIniciarSesion" name="enviar" type="submit" value="Iniciar Sesion"> 
                    
            </form>



            <div class="olvidaste-registrate">

                <a class="colorVerde " href="recuperar_contraseña.php">¿Olvidaste Tu Contraseña?</a>
                <a class="colorVerde " href="registro.php">Registrarse!</a>

            </div>
        </div>
    </section>


    <!-- aqui va la imagen que esta a la derecha del index -->
    <section class="columnaDerecha">
        <img src="assets/img/indeximg/Image_Index.png" height="500px" width="679px" class="imgPersonitas" alt="">
    </section>
    
<!-- ------------------------------------------------------------------------------------------------------------------------     -->

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

    




    <?php require_once("template/footer.php") ?>



<script src="js/js.js"></script>


</body>




</html>


<script>
    // expresiones regulares para validacion de documento y contraseña
const docRegex = /^\d{6,10}$/;
const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]{8,}$/;
// ------------------------------------------------------------------------------------------------

    const documento = document.getElementById("documento");
    const errorDocumento = document.getElementById("errorDocumento")

    const contraseña = document.getElementById("contraseña");
    const errorContraseña = document.getElementById("errorContraseña")
// declaro las variables que voy a usar durante el codigo generalmnente suelo declararlas arriba

// esta function tiene como proposito manejar el flujo del codigo sin repetirlo ya que hace una comprobacion gracias a los palametros que le estoy pasando para poner de color rojo si lo que introduce el ususario no es correcto y pues valida todo, aunque realmente lo que exactamente ahce es manjear estilos 

    function incorrecto(regex,dato,error,mensaje){
        if(!regex.test(dato.value)){
            error.textContent = mensaje
            setTimeout(() => {
                error.textContent = ""
            }, 4000);
            dato.style.borderColor = "red"
            return false
        }else{
            dato.style.borderColor = "blue" 
            return true
        }
    }
// ---------------------------------------------------------------------------

// e aqui los eventos de para el tema de los inputs el blur hace que cuando pierda el foco el input pues active el evento y ahi va toda la logica :)

    documento.addEventListener("blur",() =>{
        incorrecto(docRegex,documento,errorDocumento,"Error El Documento Debe Ser De 6-10 Digitos!")
    })
    
    contraseña.addEventListener("blur",() =>{
        incorrecto(passRegex,contraseña,errorContraseña,"Contraseña Incorrecta")
        
    })
</script>