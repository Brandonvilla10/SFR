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
    <title>Home</title>
    <link rel="stylesheet" href="../../css/plantilla_admin.css">

</head>
<body>

    <?php 
    
    $contenido =''; 
    include "../../pruebas/plantilla.php";
    ?>


<style>

    
</style>

    


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
</body>
</html>