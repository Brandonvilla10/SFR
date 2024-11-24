<?php

require_once('../../database/database.php');
$conexion = new dabatase;
$con = $conexion->conectar();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/homePage.css">
    <title>Home</title>
</head>
<body>

    <div class="barraLateral ">
        <div class="nombreIcono">
            <img src="../../assets/img/imgAdmin/home.png" id="iconoHome" width="50px" height="50px" alt="">
            <span>Inicio</span>
        </div>
    </div>
    </div>





<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../../js/js.js"></script>
</body>
</html>