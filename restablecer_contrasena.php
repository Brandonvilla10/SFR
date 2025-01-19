


                <!-- TECER ARCHIVO -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="css/cambiar_contra.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

   <?php include('template/header.php')?>
    <main class= "division">

    <section class="form_place">

        <section class ="env_correo">
            <form action="index.php" class="datos_form">
            <h1 style="padding-bottom: 20px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>  
                
                <input type="text" class="datos" placeholder="Nueva Contraseña">
                <label for="" class="error">prueba</label>
                <input type="text" class="datos" placeholder="Confirmar Contraseña">
                <input type="submit" class="boton-submit" value="Siguiente" >
                <label for="" class="error">prueba</label>
            
            </form>
        </section>

    </section>

     <img src="assets/img/recuperarContraseña/Image_RC.png" class="imgPersonitas" alt="Imagen de recuperación de contraseña">



    </main>

    <?php include("template/footer.php") ?>





</body>
</html>
