<?php
// Validaciones e includes--------------------------
session_start();
require_once('../../database/database.php');
include 'components/alert.php';
include('../../includes/sesion_validar.php');
// --------------------------------------------------

$conexion = new database;
$con = $conexion->conectar();

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


$placa = isset($_SESSION['placa']) ? trim($_SESSION['placa']) : '';
$origen = isset($_SESSION['redireccion']) ? $_SESSION['redireccion'] : 'vehiculos';

$user_id = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';



$consulta = $con->prepare("SELECT v.*, u.nombre_completo, u.id_documento, u.correo,
                         tv.tipoVehiculo, m.nombre_marca, mo.fecha_modelo
                         FROM vehiculos v
                         INNER JOIN usuario u ON v.id_documento = u.id_documento
                         LEFT JOIN detallematricula dm ON u.id_documento = dm.id_documento
                         LEFT JOIN fichas f ON f.n_ficha = dm.id_ficha
                         LEFT JOIN tipovehiculo tv ON v.id_tipoVehiculo = tv.id_tipoVehiculo
                         RIGHT JOIN marca m ON v.id_marca = m.id_marca
                         RIGHT JOIN modelo mo ON mo.id_modelo =v.id_modelo
                         WHERE v.id_placa = :placa;");
$consulta->bindParam(':placa', $placa);
$consulta->execute();
$vehiculo = $consulta->fetch(PDO::FETCH_ASSOC);



if (!$vehiculo) {

    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            mostrarModal('mistake', 'Vehiculo no encontrado', '$origen');
        });
    </script>";
    exit();
}


if (isset($_POST['actualizar'])) {
    $nueva_placa = strtoupper(trim($_POST['placa']));
    $caracteristicas = trim($_POST['caracteristicas']);
    $marca = (int)$_POST['id_marca'];
    $modelo= (int)$_POST['id_modelo'];

    $actualizar = $con->prepare("UPDATE vehiculos SET
                               caracteristicas = :caracteristicas,
                               id_marca = :id_marca,
                               id_modelo = :id_modelo
                               WHERE id_placa = :placa_actual");

    $actualizar->bindParam(':caracteristicas', $caracteristicas);
    $actualizar->bindParam(':id_marca', $marca);
    $actualizar->bindParam(':id_modelo', $modelo);
    $actualizar->bindParam(':placa_actual', $placa);

    if ($actualizar->execute()) {

        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Vehiculo Actualizado con exito', '$origen');
                });
            </script>";
        exit();
    } else {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Error al actualizar el vehiculo', '$origen');
                });
            </script>";
        exit();
    }
}


$foto_vehiculo = !empty($vehiculo['foto']) ? '../../uploads/' . $vehiculo['foto'] : '../../assets/img/vehicle-default.png';
$foto_tarjeta = !empty($vehiculo['foto_tarjeta_propiedad']) ? '../../uploads/' . $vehiculo['foto_tarjeta_propiedad'] : '../../assets/img/card-default.png';


$foto = $con->prepare("SELECT foto FROM usuario WHERE id_documento = :id_documento");
$foto->bindParam(':id_documento', $vehiculo['id_documento']);
$foto->execute();
$resultado = $foto->fetch(PDO::FETCH_ASSOC);
$mostrar = "../../uploads/" . $resultado['foto'];
$usu = "../../uploads/usu.png";
$usuario_foto_path = !empty($resultado['foto']) ? $mostrar : $usu;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Vehiculo</title>
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <link rel="stylesheet" href="css/vehiculos.css">
    <link rel="stylesheet" href="css/editar_vehiculo.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="container">
                <h1>Editar Vehiculo</h1>

                <div class="content-container">
                    <div class="user-info">
                        <div class="user-avatar">
                            <img class="personaAzul" id="fotoUsuario" src="<?php echo $usuario_foto_path; ?>" alt="Foto de perfil" onerror="this.src='../../uploads/usu.png'">

                        </div>
                        <div class="user-details">
                            <p><strong>Datos del propietario</strong></p><br>
                            <p><strong>Nombre:</strong> <?php echo ($vehiculo['nombre_completo']); ?></p>
                            <p><strong>Correo:</strong> <?php echo ($vehiculo['correo']); ?></p>
                            <p><strong>Documento:</strong> <?php echo ($vehiculo['id_documento']); ?></p>
                        </div>
                    </div>

                    <div class="form-container">
                        <div class="image-preview">
                            <div class="image-column">
                                <div class="image-upload">
                                    <img src="<?php echo $foto_vehiculo; ?>" alt="Foto vehiculo" onerror="this.src='../../uploads/default_vehiculos.png'">
                                    <div class="overlay">
                                        <i class="bi bi-upload"></i>
                                        <span>Subir foto</span>
                                    </div>
                                    <form action="../../uploadsGlobal/uploadVehiculos.php" method="POST" enctype="multipart/form-data" id="form-vehicle-photo">
                                        <input type="hidden" name="direccion" value="../roles/aprendiz/editar_vehiculo.php">
                                        <input type="hidden" name="tipo_foto" value="vehiculo">
                                        <input type="hidden" name="ruta" value="<?php echo $origen ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">

                                        <input type="file" name="foto" onchange="document.getElementById('form-vehicle-photo').submit()">
                                    </form>
                                </div>
                                <div class="image-title">Foto del vehiculo</div>
                            </div>

                            <div class="image-column">
                                <div class="image-upload">
                                    <img src="<?php echo $foto_tarjeta; ?>" alt="Tarjeta de propiedad" onerror="this.src='../../uploads/default_tarjeta.png'">
                                    <div class="overlay">
                                        <i class="bi bi-upload"></i>
                                        <span>Subir foto</span>
                                    </div>
                                    <form action="../../uploadsGlobal/uploadVehiculos.php" method="POST" enctype="multipart/form-data" id="form-card-photo">
                                        <input type="hidden" name="direccion" value="../roles/aprendiz/editar_vehiculo.php">
                                        <input type="hidden" name="tipo_foto" value="tarjeta">
                                        <input type="hidden" name="ruta" value="editar_vehiculo.php">
                                        <input type="file" name="foto" onchange="document.getElementById('form-card-photo').submit()">
                                        <input type="submit" name="upload_foto" value="Subir" style="display:none">
                                    </form>
                                </div>
                                <div class="image-title">Tarjeta de propiedad</div>
                            </div>
                        </div>

                        <div class="form-inputs">
                            <form action="" method="POST" class="grid-form">

                                <div class="form-group">
                                    <label for="tipo" class="form-label">Tipo de Vehiculo</label>
                                    <input type="text" class="form-input" id="tipo" name="tipo" value="<?php echo ($vehiculo['tipoVehiculo']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="placa" class="form-label">Placa</label>
                                    <input type="text" class="form-input" id="placa" name="placa" value="<?php echo ($vehiculo['id_placa']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="caracteristicas" class="form-label">Caracteristicas</label>
                                    <input type="text" class="form-input" id="caracteristicas" name="caracteristicas"
                                        value="<?php echo ($vehiculo['caracteristicas'] ?? ''); ?>" placeholder="Caracteristicas del vehículo">
                                </div>

                                <div class="form-group">
                                        <label for="id_marca" class="form-label">Marca</label>
                                        <select class="form-input" id="id_marca" name="id_marca" required>
                                            <option value="<?php echo $vehiculo['id_marca'] ?>" selected>  <?php echo $vehiculo['nombre_marca']?> </option>
                                            <?php
                                            $consulta_marcas = $con->prepare("SELECT * FROM marca WHERE id_marca != $vehiculo[id_marca]  and id_tipo_marca = 1 ORDER BY nombre_marca");
                                            $consulta_marcas->execute();
                                            $marcas = $consulta_marcas->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($marcas as $marca):?>
                                                <option value="<?php echo $marca['id_marca']; ?>">
                                                    <?php echo $marca['nombre_marca']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="id_modelo" class="form-label">Modelo</label>
                                        <select class="form-input" id="id_modelo" name="id_modelo" required>
                                            <option value="<?php echo $vehiculo['id_modelo']?>" selected><?php echo $vehiculo['fecha_modelo']?></option>
                                            <?php
                                            $consulta_modelos = $con->prepare("SELECT * FROM modelo WHERE id_modelo != $vehiculo[id_modelo] ORDER BY fecha_modelo DESC");
                                            $consulta_modelos->execute();
                                            $modelos = $consulta_modelos->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($modelos as $modelo): ?>
                                                <option value="<?php echo $modelo['id_modelo']; ?>">
                                                    <?php echo $modelo['fecha_modelo']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                <div class="form-actions">
                                    <input type="submit" class="btn-submit" name="actualizar" value="Guardar Cambios">
                                    <a href="vehiculos_usuarios.php" class="btn-return">← Volver</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

<script>
    function limitar(elemento, limite) {
        elemento.addEventListener("input", () => {
            if (elemento.value.length > limite) {
                elemento.value = elemento.value.substring(0, limite)
            }
            console.log(elemento.value.length)
        })
    }

    const caracteristicas = document.getElementById("caracteristicas")
    limitar(caracteristicas, 100)
</script>

</html>