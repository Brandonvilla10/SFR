<?php
require_once("database/database.php");

$conexion = new database;
$con = $conexion->conectar();

if(isset($_POST['sumbit'])){
    
    $correo = $_POST['correo'];

    $sql = $con->prepare("SELECT * FROM usuario where correo = '$correo'");
    $sql->execute();
    $fila = $sql->fetch(PDO::FETCH_ASSOC);

    if(!$fila){
        $correoNoExiste = true;
    }else{

        
    require __DIR__ . '/vendor/autoload.php';

    $resend = Resend::client('re_123456789');

    $resend->emails->send([
    'from' => 'Acme <onboarding@resend.dev>',
    'to' => ['delivered@resend.dev'],
    'subject' => 'hello world',
    'html' => '<strong>it works!</strong>',
    ]);



        echo "<script>window.location.href = 'cam_contraseña.php';</script>";
    }
    


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/recuperar_contraseña.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

    <?php include('template/header.php')?>

    <main class="division">

        <section class="form_place">

            <section class="env_correo">

                <form action="" class="datos_form" method="post">
                    <h1 style="padding-bottom: 50px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>  
                    <label for="correo" class="texto">Ingresar correo electrónico</label>
                    <input type="text" id="correo" class="datos" name="correo" placeholder="ej.@soy.sena.edu.co">
                    <div id="correo-error" class="error"></div> 
                    <input type="submit" class="boton-submit" id="submit" name="sumbit" value="Enviar correo">
                </form>

            </section>

        </section>

        <img src="assets/img/recuperarContraseña/Image_RC.png" class="imgPersonitas" alt="Imagen de recuperación de contraseña">
    </main>

    <?php include("template/footer.html") ?>

    <script>
        let errorDiv = document.getElementById('correo-error');
        let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let submit = document.getElementById("submit");


        submit.addEventListener('click', function(e){
            let correo = document.getElementById('correo').value;
            if(correo === ""){
                e.preventDefault();
                errorDiv.innerHTML = "El campo no puede estar vacío";
                errorDiv.style.display = "block";
                setTimeout(() => {
                    errorDiv.style.display = "none";
                }, 3000);
                errorDiv.style.visibility = "visible";

            } else if(!regexCorreo.test(correo)){
                e.preventDefault();
                errorDiv.innerHTML = "El correo no es válido";
                errorDiv.style.display = "block";
                setTimeout(() => {
                    errorDiv.style.display = "none";
                }, 3000);
                
            } else {
                errorDiv.style.display = "none";
            }
        });

        function noexiste(){
            errorDiv.innerHTML = "Correo no encontrado";
            errorDiv.style.visibility = "visible"
            setTimeout(() => {
                errorDiv.style.display = "none";
            }, 3000);
        }



    <?php if($correoNoExiste): ?>
        noexiste();
    <?php endif; ?>    

    </script>




</body>
</html>
