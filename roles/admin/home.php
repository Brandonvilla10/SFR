<?php

require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <link rel="stylesheet" href="css/barraLateral.css">
    <link rel="stylesheet" href="../../css/homePage.css">
    <title>Home</title>
</head>
<body>

<main>    
    <?php include "barraLateral.html" ?>        
     
   
    <div class="contenidoAdmin">
        <header class="herramientas">
            <nav class="menu">
                <div class="imagenes"><a href="../../index.php"> <img src="imgAdmin/ex.png" alt=" imagen de salida" width="38px" height= "38px"></a></div>
                <div class="imagenes"> <img src="imgAdmin/confi.png" alt="imagen de configuracion" width="38px" height= "38px"></div>
                <div class="imagenes"> <img src="imgAdmin/notf.png" alt="imagen de notificaciones" width="38px" height= "38px"></div>
                <div class="imagenes"> <img src="imgAdmin/usu.png" alt="imagen del usuario" width="38px" height= "38px"></div>
                <div>Brandon  ADMIN</div>
            </nav>
        </header>

        <section class ="contenido_2">
            
            
            <img src="imgAdmin/Bienvenido.png" alt="Bienvenido" width="500px" height="55px">
            <div class="bigimage">
            <img src="imgAdmin/img1.png" alt="Imagensita" width="700px" height="450px">
            </div>
            
        </section>
    </div>


</main>


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
</body>
</html>