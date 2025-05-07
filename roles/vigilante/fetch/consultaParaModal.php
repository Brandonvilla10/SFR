<?php
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

$documento = json_decode(file_get_contents('php://input'));

if (!$documento) {
    echo json_encode(['error' => 'Documento no proporcionado']);
    exit;
}

    $usuario = $con->prepare("SELECT * FROM usuario 
    INNER JOIN rol on rol.id_rol = usuario.id_rol
    INNER JOIN estado on estado.id_estado = usuario.id_estado
    WHERE usuario.id_documento = :documento");
    $usuario->bindParam(":documento", $documento);
    $usuario->execute();
    $datosUsuario = $usuario->fetch(PDO::FETCH_ASSOC);

    $estadoUsuario = $datosUsuario['id_estado'];
    

    $consultaObjetos = $con->prepare("SELECT *, objetos.foto as objetoFoto,  usuario.foto AS user_foto , objetos.foto AS object_foto FROM  objetos 
                        INNER JOIN marca ON marca.id_marca  = objetos.id_marca
                        INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
                        INNER JOIN estado ON estado.id_estado = objetos.id_estado 
                        INNER JOIN tipoobjeto ON tipoobjeto.id_tipoObjeto = objetos.id_tipoObjeto
                        WHERE objetos.id_documento = :documento AND objetos.id_estado = :estadoObjeto");
                        $consultaObjetos->bindParam(':documento', $documento);

                        if($estadoUsuario == 1){ 
                            $estado = 1;
                            $consultaObjetos->bindParam(':estadoObjeto', $estado); 
                        }else if($estadoUsuario == 3){ 
                            $estado = 2;
                            $consultaObjetos->bindParam(':estadoObjeto', $estado); 
                        }
                        $consultaObjetos->execute();
                        $objetos = $consultaObjetos->fetchAll(PDO::FETCH_ASSOC);


    $consultaVehiculos = $con->prepare("SELECT  marca.nombre_marca, tipovehiculo.tipoVehiculo, estado.estado, id_placa, caracteristicas, vehiculos.foto FROM `vehiculos` 
                        INNER JOIN estado on estado.id_estado = vehiculos.id_estado
                        INNER JOIN usuario on usuario.id_documento = vehiculos.id_documento
                        INNER JOIN tipovehiculo on tipovehiculo.id_tipoVehiculo = vehiculos.id_tipoVehiculo
                        INNER JOIN marca on marca.id_marca = vehiculos.id_marca
                        INNER JOIN modelo on modelo.id_modelo = vehiculos.id_modelo
                        WHERE vehiculos.id_documento = :documento AND vehiculos.id_estado = :estadoVehiculo");
                        $consultaVehiculos->bindParam(':documento', $documento);

                        if($estadoUsuario == 1){ 
                            $estado = 1;
                            $consultaVehiculos->bindParam(':estadoVehiculo', $estado); 
                        }else if($estadoUsuario == 3){ 
                            $estado = 2;
                            $consultaVehiculos->bindParam(':estadoVehiculo', $estado); 
                        }
                        $consultaVehiculos->execute();
                        $vehiculos = $consultaVehiculos->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($objetos) && !empty($vehiculos)){
        echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => $objetos, 'vehiculos' => $vehiculos]);
        
    }else if(!empty($objetos)){
        echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => $objetos, 'vehiculos' => []]);
        
    }else if(!empty($vehiculos)){
        echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => [], 'vehiculos' => $vehiculos]);
    }
    else{
        echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => [], 'vehiculos' => $vehiculos]);

    }
    

 
?>

