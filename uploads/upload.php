<?php
session_start();
include ("../database/database.php");
include('../roles/admin/components/alert.php');
$conexion = new database;
$con = $conexion->conectar();


if (isset($_FILES['foto'])) {

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
            if ($fileSize < 3 * 1024 * 1024) { 
                
                $documento = $_SESSION['documento'];

                
                $query = $con->prepare("SELECT foto FROM usuario WHERE id_documento = :documento");
                $query->bindParam(':documento', $documento, PDO::PARAM_INT);
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
                    $query->bindParam(':documento', $documento, PDO::PARAM_INT);
                    $query->execute();

                    echo "
   
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('success', 'imagen actualizada correctamente', '../roles/admin/configuracion.php'); 
                        });
                    
                    </script>";
                    exit();
                } else {
                    echo "
   
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'Ruta no encontrada intentalo de nuevo', '../roles/admin/configuracion.php'); 
                        });
                    
                    </script>";
                    exit();
                }
            } else {
                echo "
   
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {

                        mostrarModal('mistake', 'Imagen muy pesada, maximo 2MB', '../roles/admin/configuracion.php'); 
                        });
                    
                    </script>";
                    exit();
            }
        } else {
            echo "
   
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Imagen Corrupta, prueba con otra', '../roles/admin/configuracion.php'); 
                    });
                
                </script>";
                exit();
        }
    } else {
       echo "

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Extension no permitida, solo jpeg,png,jpg y gif', '../roles/admin/configuracion.php'); 
                });
            
            </script>";
            exit();
    }
} else {
    echo "
   
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            mostrarModal('mistake', 'Imagen no encontrada', '../roles/admin/configuracion.php'); 
            });
        
        </script>";
        exit();  
}

?>
