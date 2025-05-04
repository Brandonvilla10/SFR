<?php
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

header('Content-Type: application/json');

$datos = file_get_contents('php://input');
$myObject = json_decode($datos, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

$documento = $myObject['documento'];
$arrayVehiculos = $myObject['arrayVehiculos'];


function updateVehiculo($con, $id_estado, $id_placa){
    $actualizarEstado = $con->prepare("UPDATE vehiculos SET id_estado = :id_estado WHERE id_placa = :id_placa");
    $actualizarEstado->bindParam(":id_estado", $id_estado);
    $actualizarEstado->bindParam(":id_placa", $id_placa);
    $actualizarEstado->execute();
}

try {
    foreach ($arrayVehiculos as $id_placa) {
        $validarEntrada = $con->prepare('SELECT fechaEntrada, id_registroVehiculo, fechaSalida 
            FROM registroentradavehiculos
            WHERE id_placa = :id_placa
            ORDER BY fechaEntrada DESC 
            LIMIT 1');
        
        $validarEntrada->bindParam(':id_placa', $id_placa);
        $validarEntrada->execute();
        $ultimaEntrada = $validarEntrada->fetch(PDO::FETCH_ASSOC);

        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada']) {
            if (empty($ultimaEntrada['fechaSalida'])) {
                $registro = $ultimaEntrada['id_registroVehiculo'];
                $salida = $con->prepare("UPDATE registroentradavehiculos SET fechaSalida = NOW() WHERE id_registroVehiculo = :id_registroVehiculo");
                $salida->bindParam(":id_registroVehiculo", $registro);
                $salida->execute();

                updateVehiculo($con, 1, $id_placa);

            } else {
                $registrar = $con->prepare("INSERT INTO registroentradavehiculos (fechaEntrada, id_placa) VALUES (NOW(), :id_placa)");
                $registrar->bindParam(':id_placa', $id_placa);
                $registrar->execute();

                updateVehiculo($con, 2, $id_placa);
            }
        } else {
            $registrar = $con->prepare("INSERT INTO registroentradavehiculos (fechaEntrada, id_placa) VALUES (NOW(), :id_placa)");
            $registrar->bindParam(':id_placa', $id_placa);
            $registrar->execute();

            updateVehiculo($con, 2, $id_placa);
        }
    }
    echo json_encode(['success' => true, 'message' => 'Registro completado']);
} catch (Exception $e) {
    
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    
    
}
?>
