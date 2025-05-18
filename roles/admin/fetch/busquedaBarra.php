<?php 
header("Content-Type: application/json"); // Indicar que devolveremos JSON
require_once('../../../database/database.php');


$data = json_decode(file_get_contents("php://input"), true);


if(empty($data)){
    $data['consulta'] = 
    exit();
}
if(isset($data['consulta']) || !empty($data['consulta'])){

    $conexion = new database;
    $con = $conexion->conectar();
    $sql= $con->prepare($data['consulta']);
    $sql->execute();
    $respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
    

    if($respuesta){
        
        echo json_encode($respuesta);
        exit();
        
    }else{
         echo json_encode(['error' => 'No se encontraron resultados']);
         exit();
     }

}
?>