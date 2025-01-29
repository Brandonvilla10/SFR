<?php

session_start();
require_once('../../database/database.php');
// include('../../includes/sesion_validar.php');
$conexion = new database;
$con = $conexion->conectar();

    $codigo = $_SESSION['documento'];
    $nombre = explode(" ", $_SESSION['nombre'])[0];
    $rol =$_SESSION['rol_name'];


    if(isset($_POST['objetos_submit'])){

        $usuario = $_POST['user_id'];

        $validacion = $con->prepare("SELECT * FROM  usuario WHERE id_documento = '$usuario'");
        $validacion->execute();


        $consulta_1 = $validacion->fetch();

        if($consulta_1){

            $sql = $con->prepare("SELECT * FROM  objetos 
            INNER JOIN marca ON marca.id_marca  = objetos.id_marca
            INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
            INNER JOIN estado ON estado.id_estado = objetos.id_estado 
            WHERE objetos.id_documento ='$usuario'");
            $sql->execute();

            $consulta_2 = $sql->fetchAll();

            // foreach ($objeto as $key => $value) {
            //     # code...
            // }
        
        
        }else{
            echo "<script>alert('Usuario no encontrado')</script>";
            echo "<script>window.location.href='objetos_index.php'</script>";
        }


       
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>OBJETOS </title>
    <link rel="stylesheet" href="pruebas/asidebar.css">
    <link rel="stylesheet" href="css/objetos_usuario.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container_grid">
    
    <aside class="aside_barra">
        <?php  include ('asideBar/asideBar.php')?>
    </aside>

   
    

    <main class="grid_contenido_plantilla">

        <div class="titulo_objetos_usuario"><strong>SECCION OBJETOS</strong> <p>Todos los objetos de <?php echo  $nombre?> numero de identificacion <?php echo  $codigo?></p> <hr></div>

    
        <form action="" class="formulario_tarjetas">
            <div class="objetos">
               

                    <div class="objects_cards">
                        <div class="image">

                            1234567812348
                            <img src="imgAdmin/feliz.jpg" alt="">

                        </div>   
                    
                        <div class="name_cart">GUITARRA ACUSTICA</div>
                        <div class="options_card">
                                <div class="estado_and_marca">MARCA: TAKAMINE <br>  ESTADO: Active</div>
                                <button><i class="bi bi-three-dots-vertical"></i></button>
                                
                        </div>
                    </div>
                    <div class="objects_cards">
                        <div class="image">

                            1234567812348
                            <img src="imgAdmin/feliz.jpg" alt="">

                        </div>   
                    
                        <div class="name_cart">GUITARRA ACUSTICA</div>
                        <div class="options_card">
                                <div class="estado_and_marca">MARCA: TAKAMINE <br>  ESTADO: Active</div>
                                <button><i class="bi bi-three-dots-vertical"></i></button>
                                
                        </div>
                    </div>
                    <div class="objects_cards">
                        <div class="image">

                            1234567812348
                            <img src="imgAdmin/feliz.jpg" alt="">

                        </div>   
                    
                        <div class="name_cart">GUITARRA ACUSTICA</div>
                        <div class="options_card">
                                <div class="estado_and_marca">MARCA: TAKAMINE <br>  ESTADO: Active</div>
                                <button><i class="bi bi-three-dots-vertical"></i></button>
                                
                        </div>
                    </div>
                    <div class="objects_cards">
                        <div class="image">

                            1234567812348
                            <img src="imgAdmin/feliz.jpg" alt="">

                        </div>   
                    
                        <div class="name_cart">GUITARRA ACUSTICA</div>
                        <div class="options_card">
                                <div class="estado_and_marca">MARCA: TAKAMINE <br>  ESTADO: Active</div>
                                <button><i class="bi bi-three-dots-vertical"></i></button>
                                
                        </div>
                    </div>
                    <div class="objects_cards">
                        <div class="image">

                            1234567812348
                            <img src="imgAdmin/feliz.jpg" alt="">

                        </div>   
                    
                        <div class="name_cart">GUITARRA ACUSTICA</div>
                        <div class="options_card">
                                <div class="estado_and_marca">MARCA: TAKAMINE <br>  ESTADO: Active</div>
                                <button><i class="bi bi-three-dots-vertical"></i></button>
                                
                        </div>
                    </div>
                    <div class="objects_cards">
                        <div class="image">

                            1234567812348
                            <img src="imgAdmin/feliz.jpg" alt="">

                        </div>   
                    
                        <div class="name_cart">GUITARRA ACUSTICA</div>
                        <div class="options_card">
                                <div class="estado_and_marca">MARCA: TAKAMINE <br>  ESTADO: Active</div>
                                <button><i class="bi bi-three-dots-vertical"></i></button>
                                
                        </div>
                    </div>
              
            </div>
        </form>
    </main>

    <script src="pruebas/script.js"></script>
</body>


</html>   
