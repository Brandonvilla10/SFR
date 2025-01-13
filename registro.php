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

<?php
$conex = new database;
$con = $conex->conectar();

if(isset($_POST["botoncito"])){

    $nombre = $_POST['nombreCompleto'];
    $documento = $_POST['documento'];
    $correo  = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $ficha = 1;
    $codigo = $_POST['documento'];
    $estado = 1;
    $rol = 2;

    
    $encriptacion = password_hash($contrasena,PASSWORD_DEFAULT,array("pass"=>12));
    
    $validacion = $con->prepare("SELECT * FROM usuario where id_documento = '$documento'");
    $validacion -> execute();

    $fila = $validacion->fetchAll(PDO::FETCH_ASSOC);

    if($fila){
        echo "<script>alert('El usuario ya existente')</script>";
    }


    if($nombre == "" ||$documento == "" ||$correo == "" || $contrasena == "" || $codigo == "" || $estado == "" || $rol == ""){
        echo "<script>alert('Elementos vacios, Error de sistema')</script>";
    }else{
            $query = "INSERT INTO usuario(id_documento,nombre_completo,correo,contraseña,ficha,codigo_barras,id_estado,id_rol) 
            VALUES ('$documento','$nombre','$correo','$encriptacion','$ficha','$codigo','$estado','$rol')";

            $insert = $con->prepare($query);
            $insert->execute();

            echo "<script>alert('Registro exitoso')</script>";
            echo "<script>window.location = 'index.php' </script>";

    }



};

?>










    
    
    <section class="container">
        <!-- cree un section llamado container que contuviera ambos section los cuales son los de columna derecha y columna izquierda que tambien lo agregue ya que teniamos era un div llamado cuadrado azul y no se podia acomodar de la mejor manera  -->
        <section class="ColumnaIzquierda">
<!-- 
        elimine el div en el que estaba la imagen ya que no era necesario, asi mismo elimine el cuadrado que iba en el centro porque al minimizar el ancho no habia forma en la que se viera bien -->
         
        

        <div class="sectionRegistro">

         <div class="divForm">
             <h2 class="RegistroTitulo">¡Regístrate!</h2>

             <form action="" method="post" name="formulario" id ="formulario"> 
        
                    <div class="inputs">
                        <input type="text" class="datos" name="nombreCompleto" placeholder="Nombre Completo">
                        <span class="invisible">No se permite numeros, ni caracteres especiales</span>
                        <input type="number" class="datos" name="documento"  placeholder="Documento"/>
                        <span class="invisible">No se permiten letras, ni caracteres especiales</span>
                        <input type="email" class="datos" name="correo"  placeholder="Email"/>
                        <span class="invisible">No se permiten simbolos diferentes del @</span>
                        <input type="password" class="datos" name="contrasena" id="password" placeholder="Contraseña"/>
                        <span class="invisible">Minimo 8 caracteres, 1 Mayuscula, 1 simbolo </span>
                        <input type="password" class="datos" name="verify" id="passVerify" placeholder="Confirmar Contraseña"/>
                        <span class="invisible">Las contraseñas no coinciden</span>
                    </div>
                
                    <div class="BotRegistro">
                        <input type="submit" value="Registrarse" class="BotonRegistro" id="Button_registro" name ="botoncito">
                    </div>
                    
                    <div class="-contraseña">
                        <p>¿Ya Tienes Cuenta?</p>
                    </div>
                
                    <div class="registrarse">
                        <a href="index.php" class="">¡Inicia Sesion!</a>
                    </div>

             </form>    
         </div>
        
         
        


        </div>
        </section>
        
       

    <section class="columnaDerecha">
        <img src="assets/img/imgRegistro/Image_RG.png" height="550px" width="515px" class="imgPersonitas" alt="">
    </section>
    
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
    
<script src="js/registro.js"></script>
</body>


</html>