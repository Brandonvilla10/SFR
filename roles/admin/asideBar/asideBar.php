
<?php


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
    <link rel="stylesheet" href="/asideBar/asideBar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    
    
    
</head>
<body>
    
    <button class="menu_iconos_burguer"  id="abrir"><i class="bi bi-list"></i></button>

    <aside id="barraLateral" class="fuera">
        <div class="Logo">
            <img src="../../assets/img/LogoFR.png" class="logoSenaFast" alt="Logo">
            <h3 style="color: white;" >SenaFast</h3>
        </div>

        <hr>

        <a href="./home.php"> <div class="seccion" id="seccion">
         <img class="icono" src="asidebar/icons/home.png" alt="home">
            <p class="seccionTexto">Inicio</p>
        </div></a>

        <a href="objetos_index.php"> <div class="seccion">
            <img class="icono" src="asidebar/icons/objetos.png" alt="">
            <p class="seccionTexto">Objetos</p>
        </div></a>

        <a href=""> <div class="seccion">
            <img class="icono" src="asidebar/icons/carroBlanco.png" alt="">
            <p class="seccionTexto">Vehiculos</p>
        </div></a>

        <a href="./buscarUsuario.php"> <div class="seccion">
            <img class="icono" src="asidebar/icons/usuarioBlanco.png" alt="">
            <p class="seccionTexto">Usuarios</p>
        </div></a>

        <a href=""> <div class="seccion">
            <img class="icono" src="asidebar/icons/asistencia.png" alt="">
            <p class="seccionTexto">Asistencia</p>
        </div></a>

        <a href=""> <div class="seccion">
            <img class="icono" src="asidebar/icons/escanearBlanco.png" alt="">
            <p class="seccionTexto">Escanear</p>
        </div></a>

        <hr>

        <a href=""> <div class="seccion">
            <img class="icono" src="asidebar/icons/notificaciones.png" alt="">
            <p class="seccionTexto">Notificaciones</p>
        </div></a>

        <a href="./configuracion.php"> <div class="seccion">
            <img class="icono" src="asidebar/icons/configuraciones.png" alt="">
            <p class="seccionTexto">Configuraciones</p>
        </div></a>

        <a href="../../includes/sesion_destroy.php"> <div class="seccion">
            <img class="icono" src="asidebar/icons/salir.png" alt="">
            <p class="seccionTexto">Salir</p>
        </div></a>

        <?php 
                 $sql = $con->prepare("SELECT foto FROM usuario WHERE id_documento = $codigo");
                 $sql->execute();

                 $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                 $foto = $resultado['foto'];
                 $fotoActual = "../../uploads/" . $foto;
                 $usu = "../../uploads/usu.png"

                  ?>

        <a class="contenedorUsuario" href=""> <div class="seccionUsuario">
            <img id="iconoPersona" class="iconoPersona" src=<?php echo !empty($foto) ?$fotoActual : $usu  ?>  alt="imagen del usuario" >
            <p class="seccionTexto"><span id ="name"><?php echo isset($nombre) ? $nombre : 'Usuario' ?><br><?php echo isset($rol) ? $rol : "uknown"?></span></p>
        </div></a>
    

    



    </aside>


    <script>


    

</script>
    <script src="./asidebar/script.js"></script>
</body>
</html>