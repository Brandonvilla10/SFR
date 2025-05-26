<?php
include('../../../database/database.php');
session_start();

$conexion = new database;
$con = $conexion->conectar();

$filtro = json_decode(file_get_contents('php://input'));

$busqueda = "%" . $filtro ."%";

$sqlficha = $con->prepare('SELECT n_ficha FROM fichas WHERE id_estado != 4 AND n_ficha LIKE :filtro ORDER BY n_ficha DESC LIMIT 10');
$sqlficha->bindParam(':filtro', $busqueda, PDO::PARAM_STR);
$sqlficha->execute();
$resultado = $sqlficha->fetchAll(PDO::FETCH_ASSOC);
 
echo json_encode($resultado);
