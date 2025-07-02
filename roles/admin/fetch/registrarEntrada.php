<?php
include('../../../database/database.php');

$conexion = new database;
$con = $conexion->conectar();
$con->exec("SET time_zone = '-05:00'");
session_start();

try {
    // acá se toma el nombre del usuario de la sesión para dejarlo como generador de acciones
    $nombre = $_SESSION['nombre'];
    $con->exec("SET @documento_generador = '$nombre'");
    
    // si por alguna razón no hay nombre en la sesión, se le pone uno por defecto
    if (empty($nombre)) {
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
    // si algo falla con el nombre, se muestra el error y se para todo
    echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// se recibe el documento desde el frontend (ya en JSON)
$id_documento = json_decode(file_get_contents('php://input', true));

// expresión regular para validar que el documento tenga mínimo 8 dígitos
$regex = '/^\d{8,}$/';

// función para actualizar el estado del usuario en la tabla "usuario"
function updateUsuario($con,$estado, $id_documento){
    $update = $con->prepare("UPDATE usuario SET id_estado = :id_estado WHERE id_documento = :id_documento");
    $update->bindParam(":id_estado", $estado);
    $update->bindParam(":id_documento", $id_documento);
    $update->execute();
}

try {
    // acá se valida si el documento existe en la base de datos
    $validarUsuario = $con->prepare("SELECT id_documento, nombre_completo FROM usuario WHERE id_documento = :id_documento");
    $validarUsuario->bindParam(":id_documento", $id_documento);
    $validarUsuario->execute();
    $filaUsuario = $validarUsuario->fetch(PDO::FETCH_ASSOC);

    // si no existe, se responde que no está registrado y se sale
    if(empty($filaUsuario)){
        header('Content-Type: application/json');
        echo json_encode(['mensaje' => 'Usuario No Registrado', 'id_documento' => $id_documento , "color" => 'red']);
        exit();
    }

    // si el documento cumple la expresión regular, se continúa
    if (preg_match($regex, $id_documento)) {

        // se busca la última entrada del usuario (ordenado por fecha de entrada)
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

        // si hay una entrada registrada
        if ($ultimaEntrada && $ultimaEntrada['fechaEntrada']) {

            // si esa entrada NO tiene salida, entonces se registra la salida
            if (empty($ultimaEntrada['fechaSalida'])) {
                $registro = $ultimaEntrada['id_registro'];
                $salida = $con->prepare("UPDATE registroentradapersona SET fechaSalida = NOW() WHERE id_registro = :registro ");
                $salida->bindParam(":registro", $registro);
                $salida->execute();

                // el estado del usuario cambia a "salió" (estado 1)
                updateUsuario($con, 1, $id_documento);

                echo json_encode(['mensaje' => 'Nos Vemos', 'id_documento' => $filaUsuario['nombre_completo'], "color" => 'green']);
            } else {
                // si ya tenía salida, entonces se crea una nueva entrada
                $registrar = $con->prepare("INSERT INTO registroentradapersona (fechaEntrada, id_documento) VALUES (NOW(), :id_documento)");
                $registrar->bindParam(':id_documento', $id_documento);
                $registrar->execute();

                // el estado cambia a "dentro" (estado 3)
                updateUsuario($con, 3, $id_documento);

                echo json_encode(['mensaje' => 'Bienvenido(a) ', 'id_documento' => $filaUsuario['nombre_completo'] , "color" => 'green']);
            }
        } else {
            // si no tenía ningún registro, se le crea uno nuevo
            $registrar = $con->prepare("INSERT INTO registroentradapersona (fechaEntrada, id_documento) VALUES (NOW(), :id_documento)");
            $registrar->bindParam(':id_documento', $id_documento);
            $registrar->execute();

            updateUsuario($con, 3, $id_documento);
            echo json_encode(['mensaje' => 'Bienvenido(a) ', 'id_documento' => $filaUsuario['nombre_completo'] , "color" => 'green']);
        }
    }
} catch (Exception $e) {
    // si algo se rompe, se manda al log pero no se imprime nada en pantalla
    error_log("Error en el registro: " . $e->getMessage());
}
?>  
