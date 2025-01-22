<?php
session_start();

require_once('../../database/database.php');
include('../../includes/sesion_validar.php');
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
    <title>Home</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="pruebas/asidebar.css">
</head>
<body>
<div class="container_grid">
    
    <aside class="aside_barra">
        <?php  include ('pruebas/asideBar.php')?>
    </aside>

    <nav class ="barrita_iconos">
    
        <?php   include "menu_iconos.php";?>

    </nav>

   

    <script src="pruebas/script.js"></script>
    

    <main class="grid_contenido_plantilla">
            
    
                
    </main>

</div>
    




<style>

    
</style>

    


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
</body>
</html>