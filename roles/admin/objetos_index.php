<?php
session_start();

require_once('../../database/database.php');
// include('../../includes/sesion_validar.php');
$conexion = new database;
$con = $conexion->conectar();


    $codigo = $_SESSION['documento'];
    $nombre = explode(" ", $_SESSION['nombre'])[0];
    $rol =$_SESSION['rol_name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>OBJETOS </title>

    <link rel="stylesheet" href="css/objetos_index.css">
    <link rel="stylesheet" href="pruebas/asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container_grid">
    
    <aside class="aside_barra">
        <?php  include ('asideBar/asideBar.php')?>
    </aside>

    <nav class ="barrita_iconos">
    
        

    </nav>

   

    <script src="pruebas/script.js"></script>
    

    <main class="grid_contenido_plantilla">

        <div class="titulo-objetos">¿Que deseas hacer?</div>

            <div class="bottones">
                <button class="cubos-objetos" onclick="plantilla();"> <img src="imgAdmin/laptopAzul.png" alt="">   Objetos </button>
                <button class ="cubos-objetos"> <img src="imgAdmin/laptopAzul.png" alt="">  Marcas objetos</button>
                <img src="imgAdmin/hombre_pensando.png" alt="imagen de un chico pensando" width="300px" height= "300px" class="imagen_flotante-objetos">
            </div>


            <div class="buscar_user estadoDeobjetos" id ="estado-objetos">
            <button class="objetos_cerrar_button" id="close-objects" onclick="retirarPlantilla()"><i class="bi bi-x-lg"></i></button>      <!-- ESTE ES EL TEXTO -->
                <div class="img_fondo">

                    <form action="objetos_usuario.php" class="inside_img_fondo" method="post">
                        <h1>Identificar Usuario</h1>

                        <p>Digite el documento</p>

                        <input type="number" name ="user_id" required class="objetos_input">
                        
                        <input type="submit" value="Continuar" class="objetos_submit" name ="objetos_submit">
                    </form>
                </div>
            </div>

        
        
        </main>

</div>
    




<style>

    
</style>

    
<script>
    const plant = document.getElementById('estado-objetos')
    
    function plantilla(){
        

        plant.classList.remove('estadoDeobjetos')

    }

    function retirarPlantilla(){
        
        plant.classList.add('estadoDeobjetos')
    }   

</script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
</body>
</html>