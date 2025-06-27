<?php
session_start();
header('Content-Type: application/json');

if (isset($_POST['documento_usuario'])) {
    $_SESSION['objetos_usu'] = $_POST['documento_usuario'];
    
    header('Location: ./objetos_usuario.php');
} else {
    echo json_encode(['success' => false, 'message' => 'Documento no proporcionado.']);
}

if (isset($_POST['documentoUsuario_vehiculo'])) {
    $_SESSION['user_id'] = $_POST['documentoUsuario_vehiculo'];
    
    header('Location: ./vehiculos_usuarios.php');
} else {
    echo json_encode(['success' => false, 'message' => 'Documento no proporcionado.']);
}
?>