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
$arrayObjetos = $myObject['array'];

try {
    foreach ($arrayObjetos as $id_serial) {
        $validarEntrada = $con->prepare('SELECT fechaEntrada, id_registroObjeto, fechaSalida 
            FROM registroentradaobjetos
            WHERE id_serial = :id_serial
            ORDER BY fechaEntrada DESC 
            LIMIT 1');
        
        $validarEntrada->bindParam(':id_serial', $id_serial);
        $validarEntrada->execute();
        $ultimaEntrada = $validarEntrada->fetch(PDO::FETCH_ASSOC);

        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada']) {
            if (empty($ultimaEntrada['fechaSalida'])) {
                $registro = $ultimaEntrada['id_registroObjeto'];
                $salida = $con->prepare("UPDATE registroentradaobjetos SET fechaSalida = NOW() WHERE id_registroObjeto = :id_registroObjeto");
                $salida->bindParam(":id_registroObjeto", $registro);
                $salida->execute();
            } else {
                $registrar = $con->prepare("INSERT INTO registroentradaobjetos (fechaEntrada, id_serial) VALUES (NOW(), :id_serial)");
                $registrar->bindParam(':id_serial', $id_serial);
                
                $registrar->execute();
            }
        } else {
            $registrar = $con->prepare("INSERT INTO registroentradaobjetos (fechaEntrada, id_serial) VALUES (NOW(), :id_serial)");
            $registrar->bindParam(':id_serial', $id_serial);
            
            $registrar->execute();
        }
    }
    echo json_encode(['success' => true, 'message' => 'Registro completado']);
} catch (Exception $e) {
    
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    
    
}
?>
