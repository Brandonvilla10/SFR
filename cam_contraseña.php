


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
            <form action="restablecer_contrasena.php" class="datos_form">
            <h1 style="padding-bottom: 50px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>  
                <label for="" class="texto">ingrese el codigo </label>
                <input type="password" class="datos" placeholder="*****">
                <label for="" class="error">prueba</label>
                <input type="submit" class="boton-submit" value="Siguiente" >
            
            </form>
        </section>

    </section>

     <img src="assets/img/recuperarContraseña/Image_RC.png" class="imgPersonitas" alt="Imagen de recuperación de contraseña">



    </main>

    <?php include("template/footer.php") ?>





</body>
</html>
