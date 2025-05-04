<?php
include('../../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();

header('Content-Type: application/json');

// esta es la forma de recibir un array de objetos en php
$datos = file_get_contents('php://input');
$myObject = json_decode($datos, true);

// aqui valido que si me este llegando un json me toco asi porque no me dejaba antes
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// aqui saco del array dos cosas importantes el documento y el array de objetos para poder trabajar por separado mas facil
$documento = $myObject['documento'];
$arrayObjetos = $myObject['array'];

try {
    foreach ($arrayObjetos as $id_serial) {
        // con esta consulta hago el condicional para saber si el objeto ya esta registrado o no
        $validarEntrada = $con->prepare('SELECT fechaEntrada, id_registroObjeto, fechaSalida 
            FROM registroentradaobjetos
            WHERE id_serial = :id_serial
            ORDER BY fechaEntrada DESC 
            LIMIT 1');

        $validarEntrada->bindParam(':id_serial', $id_serial);
        $validarEntrada->execute();
        $ultimaEntrada = $validarEntrada->fetch(PDO::FETCH_ASSOC);

        // aqui valido si el objeto ya esta registrado o no
        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada']) {

            // si el objeto ya esta registrado y no tiene fecha de salida le pongo una fecha de salida al objeto
            if (empty($ultimaEntrada['fechaSalida'])) {
                $registro = $ultimaEntrada['id_registroObjeto'];
                $salida = $con->prepare("UPDATE registroentradaobjetos SET fechaSalida = NOW() WHERE id_registroObjeto = :id_registroObjeto");
                $salida->bindParam(":id_registroObjeto", $registro);
                $salida->execute();

                // con esto actualizo el estado del objeto a 1, activo para decir que esta fuera y se puede modificar
                $actualizarEstado = $con->prepare("UPDATE objetos SET id_estado = 1  WHERE id_serial = :id_serial");
                $actualizarEstado->bindParam(":id_serial", $id_serial);
                $actualizarEstado->execute();
                // sino entonces le registro el objeto con una fecha de entrada y sin fecha de salida, es decir que esta en el registro
            } else {
                $registrar = $con->prepare("INSERT INTO registroentradaobjetos (fechaEntrada, id_serial) VALUES (NOW(), :id_serial)");
                $registrar->bindParam(':id_serial', $id_serial);
                $registrar->execute();

                // con esto actualizo el estado del objeto a 2, inactivo para decir que esta dentro y no se puede modificar
                $actualizarEstado = $con->prepare("UPDATE objetos SET id_estado = 2  WHERE id_serial = :id_serial");
                $actualizarEstado->bindParam(":id_serial", $id_serial);
                $actualizarEstado->execute();
            }
        } else {
            $registrar = $con->prepare("INSERT INTO registroentradaobjetos (fechaEntrada, id_serial) VALUES (NOW(), :id_serial)");
            $registrar->bindParam(':id_serial', $id_serial);
            $registrar->execute();

            // con esto actualizo el estado del objeto a 2, inactivo para decir que esta dentro y no se puede modificar
            $actualizarEstado = $con->prepare("UPDATE objetos SET id_estado = 2  WHERE id_serial = :id_serial");
            $actualizarEstado->bindParam(":id_serial", $id_serial);
            $actualizarEstado->execute();
        }
    }
    echo json_encode(['success' => true, 'message' => 'Registro completado']);
} catch (Exception $e) {

    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
