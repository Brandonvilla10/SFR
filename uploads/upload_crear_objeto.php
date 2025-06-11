<?php
session_start();    
include ("../database/database.php");
include('../roles/admin/components/alert.php');
$conexion = new database;
$con = $conexion->conectar();

// Establecer la variable @documento_generador en MySQL desde la sesión
try {
    $nombre = $_SESSION['nombre'];
    $con->exec("SET @documento_generador = '$nombre'");
    
    if (empty($nombre)) {
        // Si sigue vacía, establecer un valor predeterminado
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
exit;
}

if(isset($_FILES['foto'])){

    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['foto']['name']);
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileError = $_FILES['foto']['error'];
    $fileType = $_FILES['foto']['type'];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed) && $fileError === 0 && $fileSize < 2 * 1024 * 1024) {

    
        $newFileName = uniqid('', true) . "." . $ext;;
        $dest = $uploadDir . $newFileName;
    
        if (move_uploaded_file($fileTmpName, $dest)){
    
            $serial = $_POST['serial'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $caracteristica = $_POST['caracteristica'] ?? '';
            $marca = $_POST['marca'] ?? '';
            $estado = 1;
            $tipoObjeto = $_POST['tipoObjeto'] ?? '';
            $user_id = $_POST['user_id'] ?? '';
    
            $insert = $con->prepare("INSERT INTO objetos (id_serial, nombre, caracteristica, id_marca, id_estado, id_tipoObjeto, id_documento, foto) 
                                        VALUES (:serial, :nombre, :caracteristica, :marca, :estado, :tipo, :documento, :foto)");
        
            $insert->execute([
                ':serial' => $serial,
                ':nombre' => $nombre,
                ':caracteristica' => $caracteristica,
                ':marca' => $marca,
                ':estado' => $estado,
                ':tipo' => $tipoObjeto,
                ':documento' => $user_id,
                ':foto' => $newFileName
            ]);
    
            echo "
   
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('success', 'Objeto creado correctamente', '../roles/admin/objetos_usuario.php'); 
                        });
                    
                    </script>";
                    exit();
        }
    
    }else{
        echo "

        <script>
                document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Imagen muy pesada o extensiones no permitidas, solo jpg, jpeg, png y gif','../roles/admin/objetos_usuario.php');
                });

            </script>";
        
    }
}else{
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            mostrarModal('mistake', 'imagen no encontrada, intenta de nuevo', '../roles/admin/objetos_usuario.php'); 
            });
        
        </script>";
        exit();
}