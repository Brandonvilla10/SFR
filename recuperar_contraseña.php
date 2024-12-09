<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFR - Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/recuperar_contraseña.css">
</head>
<body>

   <?php include('template/header.php')?>

    <div class="center_square">
        <img src="assets/img/indeximg/Center_Square.png" class="img_center_square" alt="Centro">
    </div>

    <section class="section">

        <div class="CuadradoAzul">

        <div class="fondoBlanco">
            <img src="assets/img/Base_White.png" class="imgFondoBlanco" height="100px" width="100px">
        </div>

        <section class="sectionRecuperar">

            <div class="labelRecuperar">
                <label for="">Recuperar Contraseña</label>
            </div>

            <form id="formRecuperar" action="env_correo.php" method="post" onsubmit="return validarFormulario()"> 

                <div class="ingresar-correo">
                    <div class="text-ingresar-correo">
                        <label for="correo">Ingresar correo electrónico</label>
                    </div>

                    <input type="email" class="input-ingresar-correo" name="correo" id="correo" placeholder="correo@soy.sena.edu.co" required>
                    <div id="correo-error" class="error-message"></div> <!-- Contenedor de mensaje de error -->
                </div>

                <div class="Enviar correo">
                    <input type="submit" class="boton-submit" name="enviarCorreo" value="Enviar correo">
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

<script>
    function validarFormulario() {
        var correo = document.getElementById('correo').value;
        var errorDiv = document.getElementById('correo-error');
        var regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Limpiar cualquier mensaje de error anterior
        errorDiv.textContent = '';

        // Validación de correo en JavaScript
        if (!regexCorreo.test(correo)) {
            errorDiv.textContent = 'Por favor ingrese un correo electrónico válido (ejemplo: correo@soy.sena.edu.co).';
            return false;
        }
        
        // Si todo está bien, el formulario puede enviarse
        return true;
    }
</script>

</body>
</html>

