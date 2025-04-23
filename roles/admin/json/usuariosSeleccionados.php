<?php



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$datos = json_decode($json, true);


if ($datos) {
    
    $rutaArchivo = 'usuariosSeleccionados.json';
    file_put_contents($rutaArchivo, json_encode($datos, JSON_PRETTY_PRINT));
    
    
    echo json_encode([
        "success" => true,
        "redirect" => "variosActualizar.php"
    ]);
    
} 
?>
