<?php
include('../../../database/database.php');
session_start();

$conexion = new database;
$con = $conexion->conectar();

$documento = $_SESSION['documento'];

$stmt = $con->prepare("SELECT COUNT(*) as total FROM notificaciones WHERE id_afectado = :doc AND leida = 0");
$stmt->bindValue(':doc', $documento);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['total' => $row['total']]);
?>