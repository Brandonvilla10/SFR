<?php

include ("../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

if (isset($_FILES['foto'])) {

    $placa = isset($_GET['placa']) ? $_GET['placa'] : '';

    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['foto']['name']);
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileError = $_FILES['foto']['error'];
    $fileType = $_FILES['foto']['type'];
    $tipo_foto = $_POST['tipo_foto'];

    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedExtensions)) {
        if ($fileError === 0) {
            if ($fileSize < 3 * 1024 * 1024) {
                $newFileName = $tipo_foto . '_' . uniqid('', true) . "." . $fileExt;
                $fileDestination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {

                    if (!empty($placa)) {

                        // Filtrar si es vehiculo o tarjeta de propiedad
                        $campo_foto = ($tipo_foto == 'vehiculo') ? 'foto' : 'foto_tarjeta_propiedad';

                        $query = $con->prepare("UPDATE vehiculos SET $campo_foto = :foto WHERE id_placa = :placa");
                        $query->bindParam(':foto', $newFileName, PDO::PARAM_STR);
                        $query->bindParam(':placa', $placa, PDO::PARAM_STR);

                        if ($query->execute()) {
                            header("Location: ../roles/admin/editar_vehiculo.php?placa=$placa");
                            exit();
                        } else {
                            echo "<script>alert('Error al actualizar la foto en la base de datos');
                            window.location.href = '../roles/admin/editar_vehiculo.php?placa=$placa';</script>";
                            exit();
                        }
                    } else {
                        echo "<script>alert('No se encontro la placa del vehiculo');
                        window.location.href = '../roles/admin/vehiculos.php';</script>";
                        exit();
                    }
                } else {
                    echo "<script>alert('Error al cargar el archivo, intentalo de nuevo');
                    window.location.href = '../roles/admin/vehiculos.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('El archivo es demasiado grande. El tamaño maximo permitido es 3MB');
                window.location.href = '../roles/admin/editar_vehiculo.php?placa=$placa';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Error al cargar el archivo, intentalo de nuevo');
            window.location.href = '../roles/admin/editar_vehiculo.php?placa=$placa';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Formato de archivo no permitido. Solo se permiten archivos JPG, JPEG Y PNG');
        window.location.href = '../roles/admin/editar_vehiculo.php?placa=$placa';</script>";
        exit();
    }
} else {
    echo "<script>alert('No se ha seleccionado ningun archivo');
    window.location.href = '../roles/admin/vehiculos.php';</script>";
    exit();
}