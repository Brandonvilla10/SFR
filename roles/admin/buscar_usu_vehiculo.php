<?php
header('Content-Type: application/json');
require_once('../../database/database.php');

$response = ['success' => false, 'message' => 'No se recibio el documento'];

if (isset($_POST['documento'])) {
    $documento = trim($_POST['documento']);

    $conexion = new database;
    $con = $conexion->conectar();

    $consulta = $con->prepare("SELECT id_documento, nombre_completo, correo FROM usuario WHERE id_documento = :documento");
    $consulta->bindParam(':documento', $documento);
    $consulta->execute();

    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $response = [
            'success' => true,
            'usuario' => $usuario
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Usuario no encontrado'
        ];
    }
}

echo json_encode($response);