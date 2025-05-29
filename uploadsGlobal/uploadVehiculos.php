<?php
session_start();
include ("../database/database.php");
include('../roles/admin/components/alert.php');
$conexion = new database;
$con = $conexion->conectar();

$direccion = $_POST['direccion'];

if (isset($_FILES['foto'])) {

    $placa = isset($_SESSION['placa']) ? $_SESSION['placa'] : '';

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
                                echo "
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                        mostrarModal('success', 'Imagen actualizada con exito','$direccion');
                                        });
                                    </script>";
                                    exit();
                           
                        }else {

                            echo "
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                    mostrarModal('mistake', 'Error al actualizar la foto en la base de datos','$direccion');
                                    });
                                </script>";
                                exit();
                        }

                    }else {
                        echo "
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                mostrarModal('mistake', 'No se encontró la placa del vehículo','$direccion');
                                });
                            </script>";
                            exit();
                    }
                }else{
                    echo "
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                            mostrarModal('mistake', 'Error al cargar el archivo, inténtalo de nuevo', '$direccion');
                            });
                        </script>";
                        exit();
                }
            } else {
                echo "
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'El archivo es demasiado grande. Máximo permitido: 3MB','.$direccion');
                        });
                    </script>";
                exit();
            }
        } else {
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Error al cargar el archivo, inténtalo de nuevo', '$direccion');
                    });
                </script>";
            exit();
        }
    } else {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Formato de archivo no permitido. Solo JPG, JPEG y PNG', '$direccion');
                });
            </script>";
        exit();
    }
} else {
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            mostrarModal('mistake', 'No se ha seleccionado ningún archivo', '$direccion');
            });
        </script>";
    exit();
}