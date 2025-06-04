<?php
session_start();
include("../database/database.php");
include('../roles/admin/components/alert.php');
$conexion = new database;
$con = $conexion->conectar();


$ruta = $_POST['direccion'];

if (isset($_POST['submit']) || isset($_FILES['foto'])) {
    
    
    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['foto']['name']);
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileError = $_FILES['foto']['error'];
    $fileType = $_FILES['foto']['type'];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $documento = $_SESSION['documento'];
    $serial = $_POST['serial'];
 
    if (in_array($fileExt, $allowedExtensions)) {
        if ($fileError === 0) {
            if ($fileSize < 2 * 1024 * 1024) {
                $newFileName = uniqid('', true) . "." . $fileExt;
                $fileDestination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $query = $con->prepare("UPDATE objetos SET foto = :foto WHERE id_documento = :documento and id_serial = :serial_usu ");
                    $query->bindParam(':foto', $newFileName, PDO::PARAM_STR);
                    $query->bindParam(':serial_usu', $serial, PDO::PARAM_STR);
                    $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Asegura que el tipo de dato coincide
                    $query->execute();

                   echo "
   
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('success', 'imagen actualizada correctamente', '$ruta'); 
                        });
                    
                    </script>";
                    exit();
                } else {

                    echo "
   
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'Error de direccion de imagen', '$ruta'); 
                        });
                    
                    </script>";
                    exit();
                }
            } else {

            echo "
   
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Imagen muy pesada, maximo 2MB','.$ruta'); 
                });
            </script>";

            exit();
            }
        } else {

           echo "
   
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'imagen Corrupta no valida','$ruta'); 
                });  
            </script>";

        
            exit();
        }
    } else {

        echo "
   
        <script>
            document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'extensiones no permitidas, solo jpg, jpeg, png y gif','$ruta'); 
            });
          
        </script>";

        exit();
    }
} else {

    echo "
   
    <script>
            document.addEventListener('DOMContentLoaded', function () {
               mostrarModal('mistake', 'Imagen no valida','$ruta'); 
            });
          
    </script>";
    exit();
}
