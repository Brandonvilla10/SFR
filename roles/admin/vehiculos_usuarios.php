<?php
include('../../database/database.php');
session_start();
$conexion = new database;
$con = $conexion->conectar();
$codigo = $_SESSION['documento'];

// Delete vehicle functionality
if (isset($_POST['eliminar'])) {
    $id_placa = $_POST['id_placa'];
    $password_admin = $_POST['password_admin'];

    $consulta = $con->prepare("SELECT contraseña FROM usuario WHERE id_documento = :id_documento");
    $consulta->bindParam(':id_documento', $codigo);
    $consulta->execute();
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password_admin, $usuario['contraseña']) || $password_admin === $usuario['contraseña']) {
        $eliminar = $con->prepare("DELETE FROM vehiculos WHERE id_placa = :id_placa");
        $eliminar->bindParam(':id_placa', $id_placa);

        if ($eliminar->execute()) {
            echo "<script>alert('Vehiculo eliminado correctamente');
            window.location.href = 'vehiculos.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el vehiculo');
            window.location.href = 'vehiculos.php';</script>";
        }
    } else {
        echo "<script>alert('Contraseña de administrador incorrecta');
        window.location.href = 'vehiculos.php';</script>";
    }
}


$id_documento = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : '');

if (empty($id_documento)) {
    echo "<script>alert('No se ha especificado un usuario'); window.location.href = 'vehiculos.php';</script>";
    exit;
}


$consulta_usuario = $con->prepare("SELECT nombre_completo FROM usuario WHERE id_documento = :id_documento");
$consulta_usuario->bindParam(':id_documento', $id_documento);
$consulta_usuario->execute();
$usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "<script>alert('Usuario no encontrado'); window.location.href = 'vehiculos.php';</script>";
    exit;
}

$registrosPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($paginaActual < 1) {
    $paginaActual = 1;
}

$offset = ($paginaActual - 1) * $registrosPorPagina;


$sql = "SELECT
            v.id_placa,
            v.caracteristicas,
            v.foto,
            v.foto_tarjeta_propiedad,
            u.nombre_completo,
            u.id_documento,
            tv.tipoVehiculo,
            m.nombre_marca,
            mo.fecha_modelo,
            e.estado
        FROM vehiculos v
        INNER JOIN usuario u ON u.id_documento = v.id_documento
        LEFT JOIN tipovehiculo tv ON tv.id_tipoVehiculo = v.id_tipoVehiculo
        LEFT JOIN marca m ON m.id_marca = v.id_marca
        LEFT JOIN modelo mo ON mo.id_modelo = v.id_modelo
        LEFT JOIN estado e ON e.id_estado = v.id_estado
        WHERE v.id_documento = :id_documento
        LIMIT :offset, :limit";

$stmt = $con->prepare($sql);
$stmt->bindParam(':id_documento', $id_documento);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $registrosPorPagina, PDO::PARAM_INT);
$stmt->execute();

$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sqlCount = "SELECT COUNT(*) FROM vehiculos WHERE id_documento = :id_documento";
$stmtCount = $con->prepare($sqlCount);
$stmtCount->bindParam(':id_documento', $id_documento);
$stmtCount->execute();
$totalVehiculos = $stmtCount->fetchColumn();
$totalPaginas = ceil($totalVehiculos / $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Vehiculos</title>
    <link rel="stylesheet" href="css/vehiculos_usuarios.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container_grid">
        <aside class="aside_barra">
            <?php include ('asideBar/asideBar.php') ?>
        </aside>

    <main class="grid_contenido_plantilla">
        <div class="container">
            <div class="header">
            <h2>SECCION VEHICULOS<br>
            Todos los vehiculos de <?php echo $usuario['nombre_completo']; ?></h2>
            <button class="add-button" onclick="window.location.href='aggVehiculo.php?id_documento=<?php echo $id_documento; ?>'">+</button>
        </div>

        <div class="objects-grid">
            <?php foreach ($vehiculos as $vehiculo): ?>
            <div class="object-card">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="<?php echo !empty($vehiculo['foto']) ? '../../uploads/' . $vehiculo['foto'] : '../../uploads/vehiculo_default.png'; ?>" alt="Vehiculo" onerror="this.src='../../uploads/default_vehiculos.png'">
                    </div>
                    <div class="object-title">
                        <div class="object-id"><?php echo ($vehiculo['id_placa']); ?></div>
                        <h3 class="object-name"><?php echo ($vehiculo['nombre_marca']); ?></h3>
                        <p class="object-description"><?php echo ($vehiculo['caracteristicas']); ?></p>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Tipo:</span>
                        <span class="detail-value"><?php echo ($vehiculo['tipoVehiculo']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Modelo:</span>
                        <span class="detail-value"><?php echo ($vehiculo['fecha_modelo']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Estado:</span>
                        <span class="detail-value"><?php echo ($vehiculo['estado']); ?></span>
                    </div>
                </div>
                <div class="object-actions">
                    <button class="action-button delete-button" onclick="mostrarEliminar('<?php echo $vehiculo['id_placa']; ?>')">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                    <button class="action-button edit-button" onclick="window.location.href='editar_vehiculo.php?placa=<?php echo $vehiculo['id_placa']; ?>&origen=usuarios&user_id=<?php echo $id_documento; ?>'">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button class="footer-button" onclick="window.location.href='vehiculos.php'">Regresar</button>
    </div>

     <!-- formulario para eliminar -->
    <div class="buscar_user estadoDevehiculos" id="modal-eliminar">
                    <button class="vehiculos_cerrar_button" onclick="cerrarEliminar()"><i class="bi bi-x-lg"></i></button>
                    <div class="img_fondo">
                        <form action="" class="inside_img_fondo" method="post">
                            <h2>¿Esta seguro de eliminar este vehiculo?</h2>

                            <p style="margin-top: 11px; color:black; font-size: bold;">Para eliminar el vehículo con placa <span id="placa-eliminar"></span> ingrese su contraseña de administrador</p>

                            <input type="hidden" name="id_placa" id="id-placa-eliminar">
                            <input type="password" name="password_admin" required class="vehiculos_input" placeholder="Contraseña de administrador">

                            <div style="justify-content: center; width: 100%; margin-top: 15px;">
                                <input type="submit" value="Eliminar" class="vehiculos_submit" style="background-color: #e74c3c" name="eliminar">
                            </div>
                        </form>
                    </div>
                </div>
        </main>
</div>


    <script>
        const modalEliminar = document.getElementById('modal-eliminar');

        function mostrarEliminar(placa) {
            document.getElementById('placa-eliminar').textContent = placa;
            document.getElementById('id-placa-eliminar').value = placa;
            modalEliminar.classList.remove('estadoDevehiculos');
        }

        function cerrarEliminar() {
            modalEliminar.classList.add('estadoDevehiculos');
        }

        // Enhanced animations
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header');
            header.classList.add('animate-header');

            const cards = document.querySelectorAll('.object-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('visible');
                }, 100 * index);
            });

            const footerButton = document.querySelector('.footer-button');
            setTimeout(() => {
                footerButton.classList.add('animate-button');
            }, 100 * cards.length + 200);
        });


    </script>




    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>