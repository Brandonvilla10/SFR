<?php
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
$con->exec("SET time_zone = '-05:00'");
session_start();

try {
    // Se guarda el nombre del usuario de la sesión para registrar quién hace el cambio
    $nombre = $_SESSION['nombre'];
    $con->exec("SET @documento_generador = '$nombre'");
    
    // Si el nombre no está definido, se deja como "Usuario Sistema"
    if (empty($nombre)) {
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
    // Si falla algo aquí, se muestra el error y se termina
    echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

header('Content-Type: application/json');

// Se recibe el JSON desde el frontend
$datos = file_get_contents('php://input');
$myObject = json_decode($datos, true);

// Verificamos que el JSON esté bien formado
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Función que se encarga de actualizar el estado del objeto (activo o inactivo)
function updateObjetos($con, $id_estado, $id_serial) {
    $actualizarEstado = $con->prepare("UPDATE objetos SET id_estado = :id_estado  WHERE id_serial = :id_serial");
    $actualizarEstado->bindParam(":id_estado", $id_estado);
    $actualizarEstado->bindParam(":id_serial", $id_serial);
    $actualizarEstado->execute();
}

// Aquí se separan los datos que llegaron del JSON
$documento = $myObject['documento'];
$arrayObjetos = $myObject['array'];
$arrayObservaciones = $myObject['observaciones'];

try {
    foreach ($arrayObjetos as $id_serial) {

        // Verificamos si el objeto ya tiene un registro
        $validarEntrada = $con->prepare('SELECT fechaEntrada, id_registroObjeto, fechaSalida 
            FROM registroentradaobjetos
            WHERE id_serial = :id_serial
            ORDER BY fechaEntrada DESC 
            LIMIT 1');
        $validarEntrada->bindParam(':id_serial', $id_serial);
        $validarEntrada->execute();
        $ultimaEntrada = $validarEntrada->fetch(PDO::FETCH_ASSOC);

        // Si ya existe un registro de entrada
        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada']) {

            // Si ese registro no tiene salida, le ponemos una fecha de salida
            if (empty($ultimaEntrada['fechaSalida'])) {
                $registro = $ultimaEntrada['id_registroObjeto'];
                $salida = $con->prepare("UPDATE registroentradaobjetos SET fechaSalida = NOW() WHERE id_registroObjeto = :id_registroObjeto");
                $salida->bindParam(":id_registroObjeto", $registro);
                $salida->execute();

                // Cambiamos el estado del objeto a 1 (activo)
                updateObjetos($con, 1, $id_serial);
                
                // Si hay observaciones para este objeto, las guardamos como observación de salida
                foreach ($arrayObservaciones as $observaciones) {
                    if ($observaciones['id'] == $id_serial) {
                        $observacion = $observaciones['observacion'];
                        $actualizarObservacion = $con->prepare("UPDATE registroentradaobjetos SET observacionesSalida = :observacionesSalida WHERE id_registroObjeto = :id_registro");
                        $actualizarObservacion->bindParam(":observacionesSalida", $observacion);
                        $actualizarObservacion->bindParam(":id_registro", $registro);
                        $actualizarObservacion->execute();
                        break;
                    }
                }

            } else {
                // Si ya tiene salida, se registra una nueva entrada
                $registrar = $con->prepare("INSERT INTO registroentradaobjetos (fechaEntrada, id_serial) VALUES (NOW(), :id_serial)");
                $registrar->bindParam(':id_serial', $id_serial);
                $registrar->execute();
                
                // Obtenemos el ID del nuevo registro
                $id_registro = $con->lastInsertId();
                
                // Guardamos la observación de entrada si hay
                foreach ($arrayObservaciones as $observaciones) {
                    if ($observaciones['id'] == $id_serial) {
                        $observacion = $observaciones['observacion'];
                        $actualizarObservacion = $con->prepare("UPDATE registroentradaobjetos SET observaciones = :observacion WHERE id_registroObjeto = :id_registro");
                        $actualizarObservacion->bindParam(":observacion", $observacion);
                        $actualizarObservacion->bindParam(":id_registro", $id_registro);
                        $actualizarObservacion->execute();
                        break;
                    }
                }

                // El objeto se marca como inactivo (3) porque está dentro
                updateObjetos($con, 3, $id_serial);
            }

        } else {
            // Si no hay ningún registro anterior, se crea uno nuevo
            $registrar = $con->prepare("INSERT INTO registroentradaobjetos (fechaEntrada, id_serial) VALUES (NOW(), :id_serial)");
            $registrar->bindParam(':id_serial', $id_serial);
            $registrar->execute();
            
            $id_registro = $con->lastInsertId();
            
            // Se guarda la observación de entrada
            foreach ($arrayObservaciones as $observaciones) {
                if ($observaciones['id'] == $id_serial) {
                    $observacion = $observaciones['observacion'];
                    $actualizarObservacion = $con->prepare("UPDATE registroentradaobjetos SET observaciones = :observacion WHERE id_registroObjeto = :id_registro");
                    $actualizarObservacion->bindParam(":observacion", $observacion);
                    $actualizarObservacion->bindParam(":id_registro", $id_registro);
                    $actualizarObservacion->execute();
                    break;
                }
            }

            // Se marca como inactivo (3) porque quedó dentro
            updateObjetos($con, 3, $id_serial);
        }
    }

    // si todo sale bien, se responde con éxito
    echo json_encode(['success' => true, 'message' => 'Registro completado con observaciones']);

} catch (Exception $e) {
    // si se rompe algo, se responde con error y el mensaje
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
