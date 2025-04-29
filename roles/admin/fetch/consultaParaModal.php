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


    $consultaObjetos = $con->prepare("SELECT *, objetos.foto as objetoFoto,  usuario.foto AS user_foto , objetos.foto AS object_foto FROM  objetos 
                        INNER JOIN marca ON marca.id_marca  = objetos.id_marca
                        INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
                        INNER JOIN estado ON estado.id_estado = objetos.id_estado 
                        INNER JOIN tipoobjeto ON tipoobjeto.id_tipoObjeto = objetos.id_tipoObjeto
                        WHERE objetos.id_documento = :documento ");
                        $consultaObjetos->bindParam(':documento', $documento);
                        $consultaObjetos->execute();
                        $objetos = $consultaObjetos->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($objetos)){
        echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => $objetos]);
        
    }else{
        echo json_encode(['datosPersonales' => $datosUsuario]);

    }
    

 
?>

