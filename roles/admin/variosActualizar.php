<?php
include("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();


$json = file_get_contents("./json/usuariosSeleccionados.json");
$datos = json_decode($json, true); 


if(isset($_POST['submit'])){
    $listaParaAgregar = [];

    $id_rol = $_POST['id_rol'];
    $estado = $_POST['estado'];
    $ficha = $_POST['ficha'];


    if(!empty($id_rol)){
        $listaParaAgregar[] = 'id_rol  = ' . $id_rol ;
    }
    
    if(!empty($estado)){
        $listaParaAgregar[] = 'id_estado  = ' . $estado ;
    }

    if(!empty($ficha)){
        $listaParaAgregar[] = 'ficha  = ' . $ficha ;
    }

    if(!empty($listaParaAgregar)){

        foreach($datos as $dato){
            $actualizar = $con->prepare("UPDATE usuario SET ". implode(', ', $listaParaAgregar) . " WHERE id_documento = $dato");
            $actualizar->execute();
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <link rel="stylesheet" href="css/variosActualizar.css">
    <title>Actualizar</title>
</head>
<body>
    
<div class="container_grid">
        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>

        <nav class="menu estado-menu" style="margin-top: 40px;">
                <div class="divVolver" style="display:flex;align-items: end;margin-right: 10%;justify-content: flex-end;">
                    <a class="volver" href="buscarUsuario.php"><img  width="15px" height="15px" src="../../assets/img/volver.png" alt="">Regresar</a>
                </div>
            </nav>
            
            
            <main class="grid_contenido_plantilla">
                
                <section class="menuDeArriba">
                    
                    <h1>Editar Usuarios</h1>
                <div class="botonesEdicion">

                <form action="" class="formulario" method="post">

                    

                    <select class="selects" name="ficha" id="">
                        <option value="">Cambiar Ficha</option>
                        <?php
                        
                        $consultaFicha = $con->prepare("SELECT * FROM fichas");
                        $consultaFicha->execute();
                        $fila2 = $consultaFicha->fetchAll(PDO::FETCH_ASSOC);

                        foreach($fila2 as $resu){
                        ?>
                        <option value="<?php echo $resu['n_ficha'] ?>"><?php echo $resu['n_ficha'] ?> </option>
                        <?php 
                        } 
                        ?>
                    </select>

                    <select class="selects" name="estado" id="">
                        <option value="">Cambiar Estado</option>
                        <?php

                        $consultaFicha = $con->prepare("SELECT * FROM estado");
                        $consultaFicha->execute();
                        $fila2 = $consultaFicha->fetchAll(PDO::FETCH_ASSOC);

                        foreach($fila2 as $resu){
                        ?>
                        <option class="option" value="<?php echo $resu['id_estado'] ?>"><?php echo $resu['estado'] ?> </option>
                        <?php 
                        } 
                        ?>
                    </select>

                    <select class="selects" name="id_rol" id="">
                        <option value="">Cambiar Rol</option>
                        <?php

                        $consultaFicha = $con->prepare("SELECT * FROM rol");
                        $consultaFicha->execute();
                        $fila2 = $consultaFicha->fetchAll(PDO::FETCH_ASSOC);

                        foreach($fila2 as $resu){
                        ?>
                        <option value="<?php echo $resu['id_rol'] ?>"><?php echo $resu['nombre_rol'] ?> </option>
                        <?php 
                        } 
                        ?>
                    </select>

                        <input type="submit" class="submit" name="submit" value="Confirmar Cambios">
                </form>


                </div>
            </section>


        <section class="mainSection">

        <?php
        

        foreach($datos as $dato){
            
        $consulta = $con->prepare("SELECT usuario.*, fichas.*, programa.*, nivelFormacion.*, jornada.*, estado.*, rol.*
        FROM usuario 
        LEFT JOIN fichas on fichas.n_ficha = usuario.ficha 
        LEFT JOIN programa on programa.id_programaFormacion = fichas.id_programaFormacion  
        LEFT JOIN nivelformacion on nivelformacion.id_nivelFormacion = fichas.id_nivelFormacion
        LEFT JOIN jornada on jornada.id_jornada = fichas.id_jornada
        LEFT JOIN estado on estado.id_estado = usuario.id_estado
        LEFT JOIN rol on rol.id_rol = usuario.id_rol
        WHERE id_documento = $dato");

        $consulta->execute();
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        $sinFoto = "../../uploads/usu.png";
        $conFoto = "../../uploads/" . $fila['foto'];

        ?>
            <div class="card">
                <div class="img">
                    <img style="border-radius: 300px; border:solid" width="80px" height="80px" src=<?php echo empty($fila['foto']) ? $sinFoto : $conFoto ?> alt="">
                    <div class="divInformacion">
                        <p class="nombreCompleto"><?php echo $fila['nombre_completo'] ?></p>
                        <p class="correo"><?php echo $fila['correo'] ?></p>
                        <p class="documento"><?php echo $fila['id_documento'] ?></p>
                        <p class="documento"><?php echo $fila['nombre_rol'] ?></p>
                    </div>
                </div>

                <div class="informacion">
                    <div class="CamposMain">
                        <p class="camposDeInfo">Ficha</p>
                    </div>
 
                    <div>
                    <p class="infoFicha"><?php echo $fila['nombrePrograma']  ?></p>
                    <p class="infoFicha"><?php echo $fila['nivelDeformacion']  ?></p>
                    <p class="infoFicha"><?php echo $fila['jornada']  ?></p>
                    <p class="infoFicha">Ficha Actual: <?php echo $fila['ficha']  ?></p>
                    </div>

                    <div class="CamposMain">
                        <p class="camposDeInfo">Estado</p>
                    </div>

                    <div>
                    <p class="infoFicha"><?php echo $fila['estado']  ?></p>
                    </div>
                </div>
            </div>

        <?php 
        }
        
        ?>
        </section>

    </main>
</div>
</body>

<script >

const no = document.getElementById("no")
let nombres = document.getElementById("nombres")


</script>
</html>