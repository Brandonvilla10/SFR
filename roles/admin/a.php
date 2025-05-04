<?php
include('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    <div class="selecionarObjetos desaparecerModal" id="selecionarObjetos" style="visibility:visible; opacity: 1;">
        <div class="registrarObjeto">

            <div class="elementoUsuario">

                <div class="informacionUsuario" id="informacionUsuario">

                    <!-- aqui va algo de codigo html solo que se pone con javascript, esta en el archivo logicascaner -->


                </div>


                <div class=" contenedorObjetosVehiculos">
                    <!-- inicia la partida los objetos -->

                    <div class="objetos" id="contenedorObjetos">
                        <h2>Objetos</h2>
                        <div class="tarjetaObjeto" data-id="1">
                            <div class="divFoto">
                                <p>${objeto.id_serial}</p>
                                <img src="../../uploads/${objeto.objetoFoto}" width="80px" height="80px" alt="objeto">
                            </div>

                            <div class="divCaracteristicas">
                                <h4>${objeto.nombre}</h4>
                                <p style="font-size: 0.8rem">${objeto.caracteristica}</p>
                            </div>

                            <div class="divcategorias">
                                <b class="tituloCategoria">Marca:</b>
                                <p class="tipoObjeto">${objeto.nombre_marca}</p>
                                <b class="tituloCategoria">Estado:</b>
                                <p class="tipoObjeto">${objeto.estado}</p>
                                <b class="tituloCategoria">Tipo:</b>
                                <p class="tipoObjeto">${objeto.tipoObjeto}</p>
                            </div>

                        
                        </div>
                        <div class="tarjetaObjeto" data-id="2">
                            <div class="divFoto">
                                <p>${objeto.id_serial}</p>
                                <img src="../../uploads/${objeto.objetoFoto}" width="80px" height="80px" alt="objeto">
                            </div>

                            <div class="divCaracteristicas">
                                <h4>${objeto.nombre}</h4>
                                <p style="font-size: 0.8rem">${objeto.caracteristica}</p>
                            </div>

                            <div class="divcategorias">
                                <b class="tituloCategoria">Marca:</b>
                                <p class="tipoObjeto">${objeto.nombre_marca}</p>
                                <b class="tituloCategoria">Estado:</b>
                                <p class="tipoObjeto">${objeto.estado}</p>
                                <b class="tituloCategoria">Tipo:</b>
                                <p class="tipoObjeto">${objeto.tipoObjeto}</p>
                            </div>

                        
                        </div>

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
        <script src="./js/logicaScaner.js"> </script>
        <script src="./js/modalObservacion.js"></script>
</body>

</html>