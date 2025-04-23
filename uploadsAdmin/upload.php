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
                
                $documentoVentana = $_GET['documento'] ;
                                     

                
                $query = $con->prepare("SELECT foto FROM usuario WHERE id_documento = :documento");
                $query->bindParam(':documento', $documentoVentana, PDO::PARAM_INT);
                $query->execute();
                $usuario = $query->fetch(PDO::FETCH_ASSOC);

                if ($usuario && !empty($usuario['foto'])) {
                    $oldFile = $uploadDir . $usuario['foto'];

                    
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                
                $newFileName = uniqid('', true) . "." . $fileExt;
                $fileDestination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                   
                    $query = $con->prepare("UPDATE usuario SET foto = :foto WHERE id_documento = :documento");
                    $query->bindParam(':foto', $newFileName, PDO::PARAM_STR);
                    $query->bindParam(':documento', $documentoVentana, PDO::PARAM_INT);
                    $query->execute();

                    
                    echo "<script>window.opener.location.reload();window.close()</script>";
                    
                    exit();
                } else {
                    exit();
                    
                }
            } else {
                
                echo "<script>window.opener.location.reload();window.close()</script>";
            }
        } else {
            echo "<script>window.opener.location.reload();window.close()</script>";
        }
    } else {
        echo "<script>window.opener.location.reload();window.close()</script>";
        
    }
} else {
    echo "<script>window.opener.location.reload();window.close()</script>";
   
    
}


?>
