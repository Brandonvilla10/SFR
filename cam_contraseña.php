

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFR - Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/cam_contraseña.css">
</head>
<body>

   <?php include('template/header.php')?>

    <div class="center_square">
        <img src="assets/img/indeximg/Center_Square.png" class="img_center_square" alt="Centro">
    </div>

    <section class="mainContainer">
        <div class="CuadradoAzul">
            <div class="fondoBlanco">
                <img src="assets/img/Base_White.png" class="imgFondoBlanco" height="100px" width="100px">
            </div>

            <section class="sectionrecuperar">

                <div class="labelrecuperar">
                    <label for="Codigo">Reestablecer Contraseña</label>
                </div>

                <form action="" method="post" onsubmit="return validarFormulario()"> 

                    <div class="codigo">
                        <div class="digite-cod">
                            <input class="input-codigo" type="text" placeholder="Nueva Contraseña">
                        </div>
                        <input type="number" class="input-codigo" name="Codigo" id="Codigo" placeholder="Confirmar Contraseña" required>
                    </div>

                    <div class="Aceptar">
                        <input type="submit" name="verificar" class="boton-submit">
                    </div>
                    
                </form>

            </section>
        </div>
    </section>

    <section class="columnaDerecha">
        <img src="assets/img/recuperarContraseña/Image_RC.png" height="500px" width="679px" class="imgPersonitas" alt="Imagen de recuperación de contraseña">
    </section>

    <div class="social-bar">
        <a href="https://www.facebook.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/tik-tok.png" width="28px" height="28px" alt="TikTok">
        </a>
        <a href="https://www.instagram.com" target="_blank" class="social-icon">
            <img src="assets/img/iconosRedesSociales/facebook.png" width="28px" height="28px" alt="Facebook">
        </a>
        <a href="https://www.tiktok.com" class="social-icon">
            <img src="assets/img/iconosRedesSociales/logotipo-de-instagram.png" width="28px" height="28px" alt="Instagram">
        </a>
    </div>

    <?php include("template/footer.php") ?>





</body>
</html>
