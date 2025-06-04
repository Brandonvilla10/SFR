<?php
session_start();
include('components/alert.php');
include('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../includes/sesion_validar.php');


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

    <!-- Modal de Observaciones -->
    <div class="modalObservaciones desaparecerModal" id="modalObservaciones">
        <div class="contenedorModalObservaciones">
            <div class="headerModal">
                <h3>Observaciones</h3>
                <button class="cerrarModal" id="cerrarModalObservaciones">×</button>
            </div>
            <div class="cuerpoModal">
                <textarea id="textoObservacion" placeholder="Escribe tus observaciones aquí..."></textarea>
                <div class="botonesModal">
                    <button class="submit-btn" id="guardarObservacion">Guardar</button>
                    <button class="cancelar-btn" id="cancelarObservacion">Cancelar</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Fin Modal de Observaciones -->

    <div class="container_grid">

        <div class="alerta" id="alerta">
            <p class="alertaBienvenida" id="alertaBienvenida"></p>
        </div>

        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>






        <main class="grid_contenido_plantilla">

            <div class="container">

                <div class="instructions">

                    <div class="container">
                        <h1>Lector de Código QR</h1>



                        <button id="startButton">Iniciar Cámara</button>
                        <button id="stopButton" disabled>Detener Cámara</button>

                    </div>

                </div>

                <div id="videoContainer">
                    <video id="video" playsinline></video>
                    <canvas id="canvas"></canvas>
                </div>

                <div class="manual-search-container">
                    <input type="number" 
                           class="manual-search-input" 
                           placeholder="Ingresar Documento Manualmente"
                           id="documentoManual">

                    <button class="manual-search-button" id="buscarDocumentoManual" >Buscar</button>
                </div>

                
            </div>

            <!-- Toda esta parte sera destinada para el modal que hablamos el 04/21/2025 acerca de la parte del registro que tiene el celador  -->

            <div class="selecionarObjetos desaparecerModal" id="selecionarObjetos">
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
                                <div class="vehiculos" id="contenedorVehiculos">
                                    <h2>Vehiculos</h2>


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

    <script type="module" src="./js/modalObservacion.js"></script>
    <script type="module" src="./js/logicaScaner.js"></script>

    <script>
        const selecionarObjetos = document.getElementById("selecionarObjetos")

        function cerrar() {
            selecionarObjetos.classList.add("desaparecerModal")
            selecionarObjetos.classList.remove("aparecerModal")
        }


        const documentoManual = document.getElementById("documentoManual") 

        documentoManual.addEventListener("input", () => {
            
            if(documentoManual.value.length > 10){
                documentoManual.value = documentoManual.value.substring(0, 10)
            }
            
        })


    </script>
</body>

</html>