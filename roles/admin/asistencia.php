<?php 


require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¿Qué deseas hacer?</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    

    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>

        <nav class="menu estado-menu" style="margin-top: 40px;">

        </nav>
    <main class="grid_contenido_plantilla">

        <h1>¿Qué deseas hacer?</h1>
        <div class="container">
            <div class="option">
                <i class="fas fa-desktop"></i>
                <a href="./estado.php">Estado del computador</a>
                </div>
                <div class="option">
                    <i class="fas fa-clipboard-list"></i>
                    <a href="asignar.php">Asignar computador</a>
                </div>
            </div>
        </div>    
        
    </main>
</div>
</body>
</html>