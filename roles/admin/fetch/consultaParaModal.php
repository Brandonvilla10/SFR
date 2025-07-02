<?php
// Conectamos a la base de datos
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

// Recibimos el documento desde el frontend (JSON plano)
$documento = json_decode(file_get_contents('php://input'));

// Si no vino el documento, salimos y mostramos un mensaje
if (!$documento) {
    echo json_encode(['error' => 'Documento no proporcionado']);
    exit;
}

// Sacamos los datos del usuario, con info del rol y estado
$usuario = $con->prepare("SELECT * FROM usuario 
    INNER JOIN rol on rol.id_rol = usuario.id_rol
    INNER JOIN estado on estado.id_estado = usuario.id_estado
    WHERE usuario.id_documento = :documento");
$usuario->bindParam(":documento", $documento);
$usuario->execute();
$datosUsuario = $usuario->fetch(PDO::FETCH_ASSOC);

// Si no se encontró el usuario, respondemos eso
if (!$datosUsuario) {
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit;
}

// Si el usuario está inactivo (estado 2), se manda esta respuesta
if ($datosUsuario['id_estado'] == 2) {
    echo json_encode(['innactivo' => true,]);
    exit;
}

// Guardamos el estado del usuario para usarlo después
$estadoUsuario = $datosUsuario['id_estado'];

// Hacemos la consulta de los objetos que tiene ese usuario
$consultaObjetos = $con->prepare("SELECT *, objetos.foto as objetoFoto,  usuario.foto AS user_foto , objetos.foto AS object_foto FROM  objetos 
                        INNER JOIN marca ON marca.id_marca  = objetos.id_marca
                        INNER JOIN usuario ON usuario.id_documento = objetos.id_documento
                        INNER JOIN estado ON estado.id_estado = objetos.id_estado 
                        INNER JOIN tipoobjeto ON tipoobjeto.id_tipoObjeto = objetos.id_tipoObjeto
                        WHERE objetos.id_documento = :documento AND objetos.id_estado = :estadoObjeto");
$consultaObjetos->bindParam(':documento', $documento);

// Dependiendo del estado del usuario, se buscan objetos que estén dentro (3) o fuera (1)
if ($estadoUsuario == 1) {
    $estado = 1;
    $consultaObjetos->bindParam(':estadoObjeto', $estado);
} else if ($estadoUsuario == 3) {
    $estado = 3;
    $consultaObjetos->bindParam(':estadoObjeto', $estado);
}
$consultaObjetos->execute();
$objetos = $consultaObjetos->fetchAll(PDO::FETCH_ASSOC);

// Ahora se hace lo mismo pero con los vehículos que tenga ese usuario
$consultaVehiculos = $con->prepare("SELECT  marca.nombre_marca, tipovehiculo.tipoVehiculo, estado.estado, id_placa, caracteristicas, vehiculos.foto FROM `vehiculos` 
                        INNER JOIN estado on estado.id_estado = vehiculos.id_estado
                        INNER JOIN usuario on usuario.id_documento = vehiculos.id_documento
                        INNER JOIN tipovehiculo on tipovehiculo.id_tipoVehiculo = vehiculos.id_tipoVehiculo
                        INNER JOIN marca on marca.id_marca = vehiculos.id_marca
                        INNER JOIN modelo on modelo.id_modelo = vehiculos.id_modelo
                        WHERE vehiculos.id_documento = :documento AND vehiculos.id_estado = :estadoVehiculo");
$consultaVehiculos->bindParam(':documento', $documento);

// Igual que con objetos, se filtra según el estado del usuario
if ($estadoUsuario == 1) {
    $estado = 1;
    $consultaVehiculos->bindParam(':estadoVehiculo', $estado);
} else if ($estadoUsuario == 3) {
    $estado = 3;
    $consultaVehiculos->bindParam(':estadoVehiculo', $estado);
}
$consultaVehiculos->execute();
$vehiculos = $consultaVehiculos->fetchAll(PDO::FETCH_ASSOC);

// Dependiendo de lo que haya, se arma la respuesta con lo que toque
if (!empty($objetos) && !empty($vehiculos)) {
    echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => $objetos, 'vehiculos' => $vehiculos]);
} else if (!empty($objetos)) {
    echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => $objetos, 'vehiculos' => []]);
} else if (!empty($vehiculos)) {
    echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => [], 'vehiculos' => $vehiculos]);
} else {
    echo json_encode(['datosPersonales' => $datosUsuario, 'objetos' => [], 'vehiculos' => $vehiculos]);
}
