<?php

include ("../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

if (isset($_POST['submit']) && isset($_FILES['foto'])) {

    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['foto']['name']);
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileError = $_FILES['foto']['error'];
    $fileType = $_FILES['foto']['type'];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedExtensions)) {
        if ($fileError === 0) {
            if ($fileSize < 2 * 1024 * 1024) { 
                $newFileName = uniqid('', true) . "." . $fileExt; 
                $fileDestination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $documento = $_SESSION['objetos_usu'];
                    $serial = $_POST['serial'];
                    $query = $con->prepare("UPDATE objetos SET foto = :foto WHERE id_documento = :documento and id_serial = :serial_usu ");
                    $query->bindParam(':foto', $newFileName, PDO::PARAM_STR);
                    $query->bindParam(':serial_usu', $serial, PDO::PARAM_STR);
                    $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Asegura que el tipo de dato coincide
                    $query->execute();

                 
                    // Redirige y asegura que la página se recarga
                    
                    echo "<script>window.close() </script>";
                    exit();
                } else {
        
                    echo "<script>window.close() </script>";
                    exit();
                }
            } else {
           
                echo "<script>window.close() </script>";
                exit();
            }
        } else {
       
            echo "<script>window.close() </script>";
            exit();
        }
    } else {

        echo "<script>window.close() </script>";
        exit();
    }
} else {
  
    echo "<script>window.close()</script>";
    exit();
}