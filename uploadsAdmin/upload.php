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
                    $documentoUpload = $_SESSION['documentoUpload'];
                    
                    $query = $con->prepare("UPDATE usuario SET foto = :foto WHERE id_documento = :documento");
                    $query->bindParam(':foto', $newFileName, PDO::PARAM_STR);
                    $query->bindParam(':documento', $documentoUpload, PDO::PARAM_INT);
                    $query->execute();

                    header("Location: ../roles/admin/buscarUsuario.php");
                    exit();
                } else {
                    exit();
                    
                }
            } else {
                
                header("Location: ../roles/admin/buscarUsuario.php?mensaje=error_formato&mensaje2=error al mover el archivo");
            }
        } else {
            header("Location: ../roles/admin/buscarUsuario.php?mensaje=error_formato&mensaje2=Hubo un error al subir el archivo");
        }
    } else {
        header("Location: ../roles/admin/buscarUsuario.php?mensaje=error_formato&mensaje2=Formato de archivo no permitido");
        
    }
} else {
    header("Location: ../roles/admin/buscarUsuario.php?mensaje=error_formato&mensaje2=Formato de archivo no permitido");
   
    
}


?>
