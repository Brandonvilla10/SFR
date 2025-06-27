<?php 
session_start();
require_once('../../database/database.php');
include('components/alert.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../includes/sesion_validar.php');

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';



if (isset($_GET['error'])) {
   
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Usuario no registrado, no es posible crear QR', 'codigoQR.php');
            });
        </script>";
     

}?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../assets/img/LogoFR.png">
    <title>Codigo QR</title>
    <link rel="stylesheet" href="css/marcas.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="contenido_QR">

                <form action="GenerarQR.php" method="POST" class="formQR">
                    <Label> Generar Codigo QR</Label>
                    <div class="conjunto">
                        <img src="https://static.vecteezy.com/system/resources/previews/024/675/057/non_2x/modern-color-business-qr-code-icons-with-long-shadow-effect-free-png.png" width="100px" alt="">
                        <p>Descarga tu codigo QR para entrar al sena</p>
                    </div>
                    <input type="number" class="valorBotonPdf" readonly placeholder="Documento" value="<?php echo $codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : ''; ?>" name="documento">
                    <button type="submit" value="GenerateQR" name="GenerateQR">Generar PDF Descargable</button>
                </form>

            </div>


            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>