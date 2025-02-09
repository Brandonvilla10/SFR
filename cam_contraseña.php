<?php
require_once("database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start(); 
$correo = $_SESSION['correo'];

if (isset($_POST['submit'])) {

    $ramdon = $_SESSION['ramdon']; 
    $validar = $_POST['codigo'];

    if ($ramdon == $validar) {
        echo "<script>window.location = 'restablecer_contrasena.php'</script>";
    } 
}

if(!isset($correo)){
    header("Location: template/destruirSesion.php");
}
?>



                        <!-- SEGUNDO ARCHIVO  -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña-codigo </title>
    <link rel="stylesheet" href="css/cam_contraseña.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

   <?php include('template/header.php')?>
    <main class= "division">

    <section class="form_place">

        <section class ="env_correo">
            <form action="" class="datos_form" method="post">
            <h1 style="padding-bottom: 50px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>  
                <label for="" class="texto">ingrese el codigo </label>
                <input type="number" id="datos" class="datos" name="codigo" placeholder="*****">
                <label for="" id="error" class="error">prueba</label>
                <input type="submit" id="boton-submit" class="boton-submit" name="submit" value="Siguiente" >
            
            </form>
        </section>

    </section>

     <img src="assets/img/recuperarContraseña/Image_RC.png" class="imgPersonitas" alt="Imagen de recuperación de contraseña">



    </main>

    <?php include("template/footer.html") ?>


    <script>

        let errorDiv = document.getElementById('error');
        let submit = document.getElementById("boton-submit");
        let codigo = document.getElementById('datos');

        codigo.addEventListener("input", () =>{
            codigo.value = codigo.value.replace(/[^0-9]/g, "");
        })
        
        submit.addEventListener("click", (e) =>{
            if(codigo.value.trim() === ""){
                    e.preventDefault();
                    errorDiv.style.visibility = "visible";
                    errorDiv.innerHTML = "El campo no puede estar vacio";
                    setTimeout(() => {
                        errorDiv.style.visibility = "hidden";
                    }, 2000);

            }}); 

    
    </script>

</body>
</html>
