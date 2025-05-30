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

if (!isset($correo)) {
    header("Location: template/destruirSesion.php");
}
?>



<!-- SEGUNDO ARCHIVO  -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codigo de verificacion </title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cam_contrasena.css">

</head>

<body>

    <?php include('template/header.php') ?>
    <main class="division">

        <section class="form_place">

            <section class="env_correo">

                <button class="botonVolverInicial botonVolver" id="botonVolver" onclick="window.location='recuperar_contraseña.php'">

                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flecha">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                    <p class="volverTexto">Volver</p>
                </button>

                <form action="" class="datos_form" method="post">
                    <h1 style="padding-bottom: 50px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>
                    <label for="" class="texto">Ingrese el codigo de verificacion </label>
                    <input type="hidden" id="datos" class="datos" name="codigo">

                    <!-- Contenedor para los 5 dígitos individuales -->
                    <div class="codigo-container">
                        <input type="text" maxlength="1" class="digito-input" name="digito1" id="digito1" inputmode="numeric">
                        <input type="text" maxlength="1" class="digito-input" name="digito2" id="digito2" inputmode="numeric">
                        <input type="text" maxlength="1" class="digito-input" name="digito3" id="digito3" inputmode="numeric">
                        <input type="text" maxlength="1" class="digito-input" name="digito4" id="digito4" inputmode="numeric">
                        <input type="text" maxlength="1" class="digito-input" name="digito5" id="digito5" inputmode="numeric">
                    </div>
                    <label for="" id="error" class="error">prueba</label>
                    <input type="submit" id="boton-submit" class="boton-submit" name="submit" value="Siguiente">

                </form>
            </section>

        </section>

        <img src="assets/img/recuperarContraseña/Image_RC.webp" class="imgPersonitas" alt="Imagen de recuperación de contraseña">



    </main>

    <?php include("template/footer.html") ?>


    <script>
        let errorDiv = document.getElementById('error');
        let submit = document.getElementById("boton-submit");
        let campoOculto = document.getElementById('datos');
        let digitoInputs = document.querySelectorAll('.digito-input');

        // Función para validar que solo se ingresen números
        function soloNumeros(e) {
            let key = e.key;
            let keyCode = e.keyCode || e.which;

            // Permitir solo números y teclas de control (backspace, delete, etc.)
            if (!/^[0-9]$/.test(key) && keyCode !== 8 && keyCode !== 46 && keyCode !== 9) {
                e.preventDefault();
                return false;
            }
            return true;
        }

        // Función para mover al siguiente campo cuando se ingresa un dígito
        function moverAlSiguiente(e) {
            if (e.target.value.length === parseInt(e.target.getAttribute('maxlength'))) {
                let nextInput = e.target.nextElementSibling;
                if (nextInput && nextInput.classList.contains('digito-input')) {
                    nextInput.focus();
                }
            }

            // Actualizar el campo oculto con todos los dígitos
            actualizarCampoOculto();
        }

        // Función para mover al campo anterior cuando se presiona backspace en un campo vacío
        function moverAlAnterior(e) {
            if (e.key === 'Backspace' && e.target.value.length === 0) {
                let prevInput = e.target.previousElementSibling;
                if (prevInput && prevInput.classList.contains('digito-input')) {
                    prevInput.focus();
                }
            }
        }

        // Función para actualizar el campo oculto con todos los dígitos
        function actualizarCampoOculto() {
            let codigo = '';
            digitoInputs.forEach(input => {
                codigo += input.value;
            });
            campoOculto.value = codigo;
        }

        // Agregar eventos a cada campo de dígito
        digitoInputs.forEach(input => {
            input.addEventListener('keypress', soloNumeros);
            input.addEventListener('input', moverAlSiguiente);
            input.addEventListener('keydown', moverAlAnterior);
        });

        // Validar antes de enviar el formulario
        submit.addEventListener("click", (e) => {
            let codigoCompleto = true;
            let codigo = '';

            digitoInputs.forEach(input => {
                if (input.value.trim() === "") {
                    codigoCompleto = false;
                }
                codigo += input.value;
            });

            if (!codigoCompleto) {
                e.preventDefault();
                errorDiv.style.visibility = "visible";
                errorDiv.innerHTML = "Debe completar todos los dígitos del código";
                setTimeout(() => {
                    errorDiv.style.visibility = "hidden";
                }, 2000);
            } else {
                campoOculto.value = codigo;
            }
        });

        // Enfocar el primer campo al cargar la página
        window.onload = function() {
            document.getElementById('digito1').focus();
        };
    </script>

</body>

</html>