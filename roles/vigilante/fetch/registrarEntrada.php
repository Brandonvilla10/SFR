<?php
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_documento = json_decode(file_get_contents('php://input', true));
$regex = '/^\d{8,}$/';

function updateUsuario($con,$estado, $id_documento){
    $update = $con->prepare("UPDATE usuario SET id_estado = :id_estado WHERE id_documento = :id_documento");
    $update->bindParam(":id_estado", $estado);
    $update->bindParam(":id_documento", $id_documento);
    $update->execute();
}

try {
    
    $validarUsuario = $con->prepare("SELECT id_documento, nombre_completo FROM usuario WHERE id_documento = :id_documento");
    $validarUsuario->bindParam(":id_documento", $id_documento);
    $validarUsuario->execute();
    $filaUsuario = $validarUsuario->fetch(PDO::FETCH_ASSOC);

    if(empty($filaUsuario)){
        header('Content-Type: application/json');
        echo json_encode(['mensaje' => 'Usuario No Registrado', 'id_documento' => $id_documento , "color" => 'red']);
        exit();
    }

    if (preg_match($regex, $id_documento)) {

        $validarEntrada = $con->prepare("
        SELECT fechaEntrada, id_registro, fechaSalida 
        FROM registroentradapersona 
        WHERE id_documento = :id_documento 
        ORDER BY fechaEntrada DESC 
        LIMIT 1
        ");
        $validarEntrada->bindParam(':id_documento', $id_documento);
        $validarEntrada->execute();
        $ultimaEntrada = $validarEntrada->fetch(PDO::FETCH_ASSOC);

        $hoy = date('Y-m-d');


        
        
        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada'] ) {

            if (empty($ultimaEntrada['fechaSalida'])) {
                
                $registro = $ultimaEntrada['id_registro'];
                $salida = $con->prepare("UPDATE registroentradapersona SET fechaSalida = NOW() WHERE id_registro = :registro ");
                $salida->bindParam(":registro", $registro);
                $salida->execute();
                
                updateUsuario($con, 1, $id_documento);

                echo json_encode(['mensaje' => 'Nos Vemos', 'id_documento' => $filaUsuario['nombre_completo'], "color" => 'green']);
            } else {
                
                $registrar = $con->prepare("INSERT INTO registroentradapersona (fechaEntrada, id_documento) VALUES (NOW(), :id_documento)");
                $registrar->bindParam(':id_documento', $id_documento);
                $registrar->execute();
                
                updateUsuario($con, 3, $id_documento);

                echo json_encode(['mensaje' => 'Bienvenido(a) ', 'id_documento' => $filaUsuario['nombre_completo'] , "color" => 'green']);
            }
        } else {
            
            $registrar = $con->prepare("INSERT INTO registroentradapersona (fechaEntrada, id_documento) VALUES (NOW(), :id_documento)");
            $registrar->bindParam(':id_documento', $id_documento);
            $registrar->execute();

            updateUsuario($con, 3, $id_documento);
            echo json_encode(['mensaje' => 'Bienvenido(a) ', 'id_documento' => $filaUsuario['nombre_completo'] , "color" => 'green']);


        }
    }
} catch (Exception $e) {
   
    error_log("Error en el registro: " . $e->getMessage());
}
?>  