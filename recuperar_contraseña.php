<?php
require_once("database/database.php");

$conexion = new database;
$con = $conexion->conectar();
session_start();
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

    <?php include('template/header.php') ?>

    <main class="division">

        <section class="form_place">
            <div class="card">
                <section class="env_correo">

                    <button class="botonVolverInicial botonVolver" id="botonVolver" onclick="window.location='index.php'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flecha">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                        <p class="volverTexto">Volver</p>
                    </button>

                    <form action="config\env_mailer.php" class="datos_form" method="post">
                <form action="config\env_mailer.php" class="datos_form" method="post">



                    <h1 style="padding-bottom: 50px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>
                    <label for="correo" class="texto">Ingresar correo electrónico</label>
                    <input type="text" id="correo" class="datos" name="correo" placeholder="ej.@soy.sena.edu.co">
                    <div id="correo-error" class="error"></div>
                    <input type="submit" class="boton-submit" id="submit" name="submit" value="Enviar correo">
                </form>

            </section>

        </section>

        <img src="assets/img/recuperarContraseña/Image_RC.webp" class="imgPersonitas" alt="Imagen de recuperación de contraseña">
    </main>

    <?php include("template/footer.html") ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const botonVolver = document.getElementById("botonVolver");

            if (botonVolver) {

                botonVolver.addEventListener("mouseover", function(e) {

                    window.location.href = "index.php";
                });
            }
        });




        let errorDiv = document.getElementById('correo-error');
        let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let submit = document.getElementById("submit");


        submit.addEventListener('click', function(e) {
            let correo = document.getElementById('correo').value;
            if (correo === "") {
                e.preventDefault();
                errorDiv.innerHTML = "El campo no puede estar vacio";
                errorDiv.style.display = "block";
                setTimeout(() => {
                    errorDiv.style.display = "none";
                }, 3000);
                errorDiv.style.visibility = "visible";

            } else if (!regexCorreo.test(correo)) {
                e.preventDefault();
                errorDiv.innerHTML = "El correo no es valido";
                errorDiv.style.display = "block";
                setTimeout(() => {
                    errorDiv.style.display = "none";
                }, 3000);

            } else {
                errorDiv.style.display = "none";
            }
        });

        function noexiste() {
            errorDiv.innerHTML = "Correo no encontrado";
            errorDiv.style.visibility = "visible"
            setTimeout(() => {
                errorDiv.style.display = "none";
            }, 3000);
        }



        <?php if ($correoNoExiste): ?>
            noexiste();
        <?php endif; ?>
    </script>




</body>

</html>