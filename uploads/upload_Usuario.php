<?php
session_start();
include ("../database/database.php");
include('../roles/admin/components/alert.php');
$conexion = new database;
$con = $conexion->conectar();

if (isset($_FILES['foto'])) {
    
    $documentoVentana = $_POST['documento'] ;

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

                  echo "

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('success', 'imagen registrado con exito', '../roles/admin/actualizar.php?documento=$documentoVentana'); 
                        });
                    
                    </script>";
                    exit();
                } else {
                    echo "

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'Error al mover la imagen, intentalo de nuevo', '../roles/admin/actualizar.php?documento=$documentoVentana'); 
                        });
                    </script>";
                    exit();
                    
                }
            } else {
                
              echo "

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'tamaño de imagen muy grande, el tamaño maximo es 2MB', '../roles/admin/actualizar.php?documento=$documentoVentana'); 
                    });
                
                </script>";
                exit();
            }
        } else {
            echo "

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Error al subir la imagen, intentalo de nuevo', '../roles/admin/actualizar.php?documento=$documentoVentana'); 
                });
            
            </script>";
            exit();
        }
    } else {
       echo "

        <script>
            document.addEventListener('DOMContentLoaded', function () {
            mostrarModal('mistake', 'extensión no permitida, solamente jpg, jpeg, png y gif', '../roles/admin/actualizar.php?documento=$documentoVentana'); 
            });
        
        </script>";
        exit();
        
    }
} else {
     echo "

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        mostrarModal('mistake', 'imagen no registrada', '../roles/admin/usuarios.php'); 
        });
    
    </script>";
    exit();
   
    
}


?>
