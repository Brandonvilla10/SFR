<?php
include('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lector de QR</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="/css/output.css">
    <link rel="stylesheet" href="./css/scanner.css">

</head>

<body>

    <div class="container_grid">

        <div class="alerta" id="alerta">
            <p class="alertaBienvenida" id="alertaBienvenida"></p>
        </div>

        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>



        <div class="instructions">

            <div class="container">
                <h1>Lector de Código QR</h1>



                <button id="startButton">Iniciar Cámara</button>
                <button id="stopButton" disabled>Detener Cámara</button>
            </div>
        </div>


        <main class="grid_contenido_plantilla">

            <div class="container">

                <div id="videoContainer">
                    <video id="video" playsinline></video>
                    <canvas id="canvas"></canvas>
                </div>

                <div id="result">El resultado del código QR aparecerá aquí...</div>
            </div>

            <!-- Toda esta parte sera destinada para el modal que hablamos el 04/21/2025 acerca de la parte del registro que tiene el celador  -->
         
            <div class="selecionarObjetos" id="selecionarObjetos">
                <div class="registrarObjeto">

                    <div class="elementoUsuario">

                        <div class="informacionUsuario" id="informacionUsuario">
                            
                        <!-- aqui va algo de codigo html solo que se pone con javascript, esta en el archivo logicascaner -->

                            
                        </div>
                     

                        <div class=" contenedorObjetosVehiculos">
                            <!-- inicia la partida los objetos -->

                            <div class="objetos" id="contenedorObjetos">
                                <h2>Objetos</h2>
                               
                                   
                                <!-- exactamente pasa lo mismo aqui, va codigo html con consultaphp pero la logica esta en el archivo js, es el que se encarga -->
                                <!-- de renderizar toda la parte del modal -->
                                

                            </div>
                            <!-- terminar los objetos -->
                            <!-- apartados para los vehiculos -->
                            <div class="tarjetaVehiculo">
                                <div class="vehiculos">
                                    <h2>Vehiculos</h2>
                                    <?php

                                    $consultaVehiculo = $con->prepare("SELECT  marca.nombre_marca, tipovehiculo.tipoVehiculo, estado.estado, id_placa, caracteristicas, vehiculos.foto FROM `vehiculos` 
                                        INNER JOIN estado on estado.id_estado = vehiculos.id_estado
                                        INNER JOIN usuario on usuario.id_documento = vehiculos.id_documento
                                        INNER JOIN tipovehiculo on tipovehiculo.id_tipoVehiculo = vehiculos.id_tipoVehiculo
                                        INNER JOIN marca on marca.id_marca = vehiculos.id_marca
                                        INNER JOIN modelo on modelo.id_modelo = vehiculos.id_modelo
                                        WHERE vehiculos.id_documento = 1106227450");
                                    $consultaVehiculo->execute();
                                    $vehiculos = $consultaVehiculo->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($vehiculos as $vehiculo) {


                                    ?>
                                        <div class="tarjetaObjeto" data-id="<?php echo $vehiculo['id_placa'] ?>" onclick="toggleSelectionVehiculo(this)">
                                            <div class="divFoto">
                                                <p><?php echo $vehiculo['id_placa'] ?></p>
                                                <img src="../../uploads/<?php echo $vehiculo['foto'] ?>" width="80px" height="80px" alt="objeto">
                                            </div>

                                            <div style="display: flex; flex-direction:column; justify-content:center; width:150px; margin-left:20px;">
                                                <p>Carateristica Del Vehiculo:</p>
                                                <h4><?php echo $vehiculo['caracteristicas'] ?></h4>

                                            </div>

                                            <div>
                                                <b class="tituloCategoria">Marca:</b>
                                                <p class="tipoObjeto"><?php echo $vehiculo['nombre_marca'] ?></p>
                                                <b class="tituloCategoria">Estado:</b>
                                                <p class="tipoObjeto"><?php echo $vehiculo['estado'] ?></p>
                                                <b class="tituloCategoria">Tipo:</b>
                                                <p class="tipoObjeto"><?php echo $vehiculo['tipoVehiculo'] ?></p>
                                            </div>
                                        </div>
                                    <?php

                                    }

                                    ?>
                                </div>
                                <!-- terminar paras los vehiculos -->

                            </div>

                        </div>



                        </form>
                    </div>
                </div>



                <!--  ////////////////////////////Hasta aqui va el modal///////////////////////////////////7 -->
        </main>
    </div>

    <script src="./js/logicaScaner.js">
    </script>
</body>

</html>