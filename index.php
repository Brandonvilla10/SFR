<?php require_once('../SFR/database/database.php');
$conexion = new database;
$con =$conexion->conectar();
session_start();

if(isset($_POST['submit'])){


    $documento = $_POST['documento'];
    $contraseña = $_POST['contraseña'];

    
    if ($documento == "" && $contraseña == ""){
        echo "<script>alert('No has ingresado todos los datos!')</script>";
        echo "<script>window.location = 'index.php'</script>";
    }else{

        $query = $con->query("SELECT * FROM usuario where id_documento = '$documento'");
        $query->execute();
        $fila= $query->fetch();




        if($fila['id_documento'] == $documento && $contraseña == $fila['contraseña'] && $fila['id_estado'] == 1 ){


            if($fila['id_rol'] == 1){

                echo "<script>window.location ='roles/admin/home.php'</script>";

            }


        }
    
    }
    


    
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

            <div class="datos">

                <input type="text" class="datosLogin" name="" id="" placeholder="Documento">
                <input type="text" class="datosLogin" name="" id="" placeholder="Contraseña">

            </div>
           

                <input class="botonIniciarSesion" type="button" value="Iniciar Sesion">
                
            
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