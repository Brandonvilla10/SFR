<?php
require_once('../../database/database.php');
$conex = new dataBase;
$con = $conex->conectar();



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/plantilla_iconos.css">
</head>
<body>

<div class="bignavs">

    <nav class="menu2">
        <button class="menu_iconos_burguer"  id="abrir"><i class="bi bi-list"></i></button>
        <div class="nombre"><a href=""><span id ="name"><?php echo isset($nombre) ? $nombre : 'Usuario' ?><br><?php echo isset($rol) ? $rol : "uknown"?></span> </a></div>
    </nav>

    <nav class ="menu estado-menu" id="barrita_iconos">

                <div class="imagenes"><a href="../../includes/sesion_destroy.php"> <img src="imgAdmin/ex.png" alt=" imagen de salida"> <span class="estado"> Cerrar Sesion</span></a></div>
                <div class="imagenes"><a href="#"><img src="imgAdmin/confi.png" alt="imagen de configuracion"> <span class="estado"> Configuracion</span></a></div>
                <div class="imagenes"><a href="#"><img src="imgAdmin/notf.png" alt="imagen de notificaciones"><span class="estado">Notificaciones</span></a></div>
                <div class="imagenes"><a href="#"><img src="imgAdmin/usu.png" alt="imagen del usuario"><span class="estado">Perfil</span></a></div>
                <div class="nombre"><a href=""><span class="estado nombre" id ="name"><?php echo isset($nombre) ? $nombre : 'usuario' ?></span><span class="nombre"><?php echo isset($rol) ? $rol : "uknown"?></span> </a></div>      <!-- ESTE ES EL TEXTO -->
                <button class="menu_cerrar_button estado" id="close"><i class="bi bi-x-lg"></i></button>      <!-- ESTE ES EL TEXTO -->
            
                
    </nav>

</div>


<script>

    const hamburguesa = document.getElementById('abrir');
    const cerrar = document.getElementById('close');
    const nav = document.getElementById('barrita_iconos');
    const estado = document.querySelector('.menu .estado-menu')
    
    hamburguesa.addEventListener("click", () =>{
        
        nav.classList.remove("estado-menu")
        
        
    })

    cerrar.addEventListener("click", () =>{
        nav.classList.add("estado-menu")
      
    })


</script>


</body>
</html>




