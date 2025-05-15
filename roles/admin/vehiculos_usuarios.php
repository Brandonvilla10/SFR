<?php
session_start();
include('../../database/database.php');
include('./components/alert.php');
$conexion = new database;
$con = $conexion->conectar();
$codigo = $_SESSION['documento'];

// Delete vehicle functionality
if (isset($_POST['eliminar'])) {

    try {

        $id_placa = $_POST['eliminar'];

        $eliminar = $con->prepare("UPDATE vehiculos SET id_estado = 4 WHERE id_placa = :id_placa");
        $eliminar->bindParam(':id_placa', $id_placa);
        $eliminar->execute();

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Vehiculo eliminado correctamente', 'vehiculos_usuarios.php');
            });
        </script>";
    } catch (Exception $e) {

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', '  " . $e . "', 'vehiculos_usuarios.php');
            });
        </script>";
    }
}



$id_documento = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : '');

if (empty($id_documento)) {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            mostrarModal('mistake', 'No se ha especificado un usuario', 'vehiculos.php');
        });
    </script>";
    exit();
}


$consulta_usuario = $con->prepare("SELECT nombre_completo FROM usuario WHERE id_documento = :id_documento");
$consulta_usuario->bindParam(':id_documento', $id_documento);
$consulta_usuario->execute();
$usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Usuario no encontrado', 'vehiculos.php');
                });
            </script>";
    exit();
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
            v.id_estado,
            e.estado
        FROM vehiculos v
        INNER JOIN usuario u ON u.id_documento = v.id_documento
        LEFT JOIN tipovehiculo tv ON tv.id_tipoVehiculo = v.id_tipoVehiculo
        LEFT JOIN marca m ON m.id_marca = v.id_marca
        LEFT JOIN modelo mo ON mo.id_modelo = v.id_modelo
        LEFT JOIN estado e ON e.id_estado = v.id_estado
        WHERE v.id_documento = :id_documento and v.id_estado = 1 or v.id_estado = 2
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
    <link rel="stylesheet" href="./components/modalConfirmacion.css">
</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="container">
                <div class="header">
                    <h2>SECCION VEHICULOS<br>
                        Todos los vehiculos de <?php echo $usuario['nombre_completo']; ?></h2>
                    <button class="add-button" onclick="window.location.href='aggVehiculo.php?origen=usuarios&user_id=<?php echo $id_documento; ?>'">+</button>
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
                                <button class="action-button delete-button" value="<?php echo $vehiculo['id_placa'] ?>" onclick="mostrarConfirmacion(this.value,'Segur@ Que Quieres Elimnar Este Vehiculo')">
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



            <!-- modal de confirmacion de eliminacion -->

            <div class="modalConfirmacion" id="modalConfirmacion">
                <div class="contenedorModalConfirmacion">
                    <div class="headerModalConfirmacion">
                        <h3>Confirmar Acción</h3>
                        <button class="cerrarModalConfirmacion" id="cerrarModalConfirmacion">&times;</button>
                    </div>
                    <div class="cuerpoModalConfirmacion">
                        <div class="mensaje-confirmacion">
                            <i class="bi bi-exclamation-triangle-fill icono-alerta"></i>
                            <p id="mensajeConfirmacion">¿Estás seguro que deseas eliminar este elemento?</p>
                        </div>
                        <div class="botonesModalConfirmacion">
                            <button type="button" class="cancelar-btn" id="cancelarConfirmacion">Cancelar</button>
                            <form method="post">

                                <button type="submit" class="confirmar-btn" name="eliminar" id="confirmarAccion">Confirmar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



        </main>
    </div>


    <script>
        const modalConfirmacion = document.getElementById('modalConfirmacion');
        const cerrarModalConfirmacion = document.getElementById('cerrarModalConfirmacion');
        const cancelarConfirmacion = document.getElementById('cancelarConfirmacion');
        const confirmarAccion = document.getElementById('confirmarAccion');
        const mensajeConfirmacion = document.getElementById('mensajeConfirmacion');

        let callbackConfirmacion = null;

        const modalConfirmacionButton = document.querySelectorAll(".modalConfirmacionButton")

        // modalConfirmacionButton.forEach((element) => {
        //     element.addEventListener("click", () => {
        //         mostrarConfirmacion("Estas Seguro De Eliminar Este Objeto")
        //     })
        // })

        function mostrarConfirmacion(valorBoton, mensaje, callback) {
            mensajeConfirmacion.textContent = mensaje;
            callbackConfirmacion = callback;
            modalConfirmacion.style.visibility = 'visible';
            modalConfirmacion.style.opacity = '1';
            confirmarAccion.value = valorBoton;

        }

        function cerrarConfirmacion() {
            modalConfirmacion.style.visibility = 'hidden';
            modalConfirmacion.style.opacity = '0';
        }

        cerrarModalConfirmacion.addEventListener('click', cerrarConfirmacion);
        cancelarConfirmacion.addEventListener('click', cerrarConfirmacion);

        confirmarAccion.addEventListener('click', function() {
            if (typeof callbackConfirmacion === 'function') {
                callbackConfirmacion();
            }
            cerrarConfirmacion();
        });

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