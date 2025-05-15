<?php
include('../../../database/database.php');
session_start();

$conexion = new database;
$con = $conexion->conectar();

$documento = $_SESSION['documento'];
$stmt = $con->prepare("SELECT COUNT(*) as total FROM notificaciones WHERE id_afectado = :doc AND leida = 0");
$stmt->bindValue(':doc', $documento);
$stmt->execute();
$total = $stmt->fetchColumn();

echo json_encode(['total' => $total]);
?>