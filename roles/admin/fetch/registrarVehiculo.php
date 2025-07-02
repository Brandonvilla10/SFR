<?php
// Llamamos la base de datos
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();

// Le ponemos la zona horaria correcta
$con->exec("SET time_zone = '-05:00'");

// Arrancamos la sesión
session_start();

try {
    // Traemos el nombre del usuario actual
    $nombre = $_SESSION['nombre'];
    $con->exec("SET @documento_generador = '$nombre'");
    
    // Si por alguna razón el nombre viene vacío, le metemos uno genérico
    if (empty($nombre)) {
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
    // Si pasa algo, mostramos el error y cortamos
    echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// Indicamos que lo que vamos a responder es JSON
header('Content-Type: application/json');

// Recibimos los datos que vienen del JS en formato JSON
$datos = file_get_contents('php://input');
$myObject = json_decode($datos, true);

// Si el JSON no vino bien formado, devolvemos error
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Extraemos las variables principales del objeto recibido
$documento = $myObject['documento'];
$arrayVehiculos = $myObject['arrayVehiculos'];
$arrayObservaciones = $myObject['observaciones'];

// Función para actualizar el estado del vehículo (dentro/fuera)
function updateVehiculo($con, $id_estado, $id_placa)
{
    $actualizarEstado = $con->prepare("UPDATE vehiculos SET id_estado = :id_estado WHERE id_placa = :id_placa");
    $actualizarEstado->bindParam(":id_estado", $id_estado);
    $actualizarEstado->bindParam(":id_placa", $id_placa);
    $actualizarEstado->execute();
}

try {
    foreach ($arrayVehiculos as $id_placa) {
        // Buscamos la última entrada de ese vehículo
        $validarEntrada = $con->prepare('SELECT fechaEntrada, id_registroVehiculo, fechaSalida 
            FROM registroentradavehiculos
            WHERE id_placa = :id_placa
            ORDER BY fechaEntrada DESC 
            LIMIT 1');
        $validarEntrada->bindParam(':id_placa', $id_placa);
        $validarEntrada->execute();
        $ultimaEntrada = $validarEntrada->fetch(PDO::FETCH_ASSOC);

        // Si ya hay un registro de entrada
        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada']) {
            // Si no ha salido, entonces marcamos la salida
            if (empty($ultimaEntrada['fechaSalida'])) {
                $registro = $ultimaEntrada['id_registroVehiculo'];
                $salida = $con->prepare("UPDATE registroentradavehiculos SET fechaSalida = NOW() WHERE id_registroVehiculo = :id_registroVehiculo");
                $salida->bindParam(":id_registroVehiculo", $registro);
                $salida->execute();

                // Cambiamos el estado del vehículo a "fuera"
                updateVehiculo($con, 1, $id_placa);

                // Buscamos si hay observación de salida para este vehículo
                foreach ($arrayObservaciones as $observaciones) {
                    if ($observaciones['id'] == $id_placa) {
                        $observacion = $observaciones['observacion'];
                        $id_placa = $observaciones['id'];

                        if (in_array($id_placa, $arrayVehiculos)) {
                            // Actualizamos la observación de salida en el último registro
                            $registrarObservacion = $con->prepare("UPDATE registroentradavehiculos SET observacionesSalida = :observacion WHERE id_placa = :id_placa ORDER BY fechaEntrada DESC LIMIT 1");
                            $registrarObservacion->bindParam(":observacion", $observacion);
                            $registrarObservacion->bindParam(":id_placa", $id_placa);
                            $registrarObservacion->execute();
                            break;
                        }
                    }
                }
            } else {
                // Si ya había salido, hacemos un nuevo registro de entrada
                $registrar = $con->prepare("INSERT INTO registroentradavehiculos (fechaEntrada, id_placa) VALUES (NOW(), :id_placa)");
                $registrar->bindParam(':id_placa', $id_placa);
                $registrar->execute();

                // Ahora queda como "dentro"
                updateVehiculo($con, 3, $id_placa);

                // Le metemos la observación si la hay
                foreach ($arrayObservaciones as $observaciones) {
                    if ($observaciones['id'] == $id_placa) {
                        $observacion = $observaciones['observacion'];
                        $id_placa = $observaciones['id'];

                        if (in_array($id_placa, $arrayVehiculos)) {
                            $registrarObservacion = $con->prepare("UPDATE registroentradavehiculos SET observaciones = :observacion WHERE id_placa = :id_placa ORDER BY fechaEntrada DESC LIMIT 1");
                            $registrarObservacion->bindParam(":observacion", $observacion);
                            $registrarObservacion->bindParam(":id_placa", $id_placa);
                            $registrarObservacion->execute();
                            break;
                        }
                    }
                }
            }
        } else {
            // Si no tiene ninguna entrada previa, es la primera vez que se registra
            $registrar = $con->prepare("INSERT INTO registroentradavehiculos (fechaEntrada, id_placa) VALUES (NOW(), :id_placa)");
            $registrar->bindParam(':id_placa', $id_placa);
            $registrar->execute();

            // Y también queda como "dentro"
            updateVehiculo($con, 3, $id_placa);

            // Registramos la observación si hay
            foreach ($arrayObservaciones as $observaciones) {
                if ($observaciones['id'] == $id_placa) {
                    $observacion = $observaciones['observacion'];
                    $id_placa = $observaciones['id'];

                    if (in_array($id_placa, $arrayVehiculos)) {
                        $registrarObservacion = $con->prepare("UPDATE registroentradavehiculos SET observaciones = :observacion WHERE id_placa = :id_placa ORDER BY fechaEntrada DESC LIMIT 1");
                        $registrarObservacion->bindParam(":observacion", $observacion);
                        $registrarObservacion->bindParam(":id_placa", $id_placa);
                        $registrarObservacion->execute();
                        break;
                    }
                }
            }
        }
    }

    // Si todo va bien, mandamos esta respuesta
    echo json_encode(['success' => true, 'message' => 'Registro completado']);

} catch (Exception $e) {
    // Si algo se rompe, lo informamos
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
