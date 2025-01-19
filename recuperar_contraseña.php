



                        <!-- PRIMER ARCHIVO -->



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

   <main class= "division">

        <section class="form_place">

            <section class ="env_correo">
                <form action="cam_contraseña.php" class="datos_form">
                <h1 style="padding-bottom: 50px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>  
                    <label for="" class="texto">ingresar correo electronico</label>
                    <input type="text" class="datos" placeholder="ej.@soy.sena.edu.co">
                    <label for="" class="error">prueba</label>
                    <input type="submit" class="boton-submit" value="Enviar correo" >

                </form>
            </section>

                

        </section>

        <img src="assets/img/recuperarContraseña/Image_RC.png" class="imgPersonitas" alt="Imagen de recuperación de contraseña">



   </main>
            
        

        
              
 
       

   
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

