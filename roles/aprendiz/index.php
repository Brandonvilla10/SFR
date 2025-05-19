<?php
session_start();
include('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>

<?php include('asideBar/asidebar.php'); ?>
    


</body>
</html>