<?php
session_start();
require_once('../../database/database.php');
include('../../includes/sesion_validar.php');
include 'components/alert.php';
$conexion = new database;
$con = $conexion->conectar();


// Establecer la variable @documento_generador en MySQL desde la sesión
try {
    $sql = $con->prepare("SELECT nombre_completo FROM usuario WHERE id_documento = $_SESSION[documento]");
    $sql->execute();
    $nombre = $sql->fetch(PDO::FETCH_ASSOC);
    $con->exec("SET @documento_generador = '$nombre[nombre_completo]'");

    // Verificar que la variable se haya establecido correctamente
    $check = $con->query("SELECT @documento_generador as nombre");
    $result = $check->fetch(PDO::FETCH_ASSOC);
    if (empty($result['nombre'])) {
        // Si sigue vacía, establecer un valor predeterminado
        $con->exec("SET @documento_generador = 'Usuario Sistema'");
    }
} catch (Exception $e) {
    echo '<p>Error al definir el documento generador: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

$origen = isset($_GET['origen']) ? $_GET['origen'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

// Obtener el documento si viene de vehiculos usuario
$pagina_retorno = ($origen === 'usuarios') ? "vehiculos_usuarios.php?user_id=$user_id" : 'vehiculos.php';

$usuario = $_GET['user_id'] ?? null;


if (isset($_POST['buscar_usuario']) || isset($_GET['user_id'])) {
    $id_documento = isset($_POST['id_documento']) ? trim($_POST['id_documento']) : trim($_GET['user_id']);

    $consulta_usuario = $con->prepare("SELECT id_documento, nombre_completo, correo, foto FROM usuario WHERE id_documento = :id_documento");
    $consulta_usuario->bindParam(':id_documento', $id_documento);
    $consulta_usuario->execute();
    $usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Usuario no encontrado con el documento proporcionado', '$pagina_retorno');
                });
            </script>";
        exit();
    }
}





if (isset($_POST['registrar'])) {
    $placa = strtoupper(trim($_POST['placa']));
    $id_documento = trim($_POST['id_documento']);
    $caracteristicas = trim($_POST['caracteristicas']);
    $id_estado = 1; // Estado por defecto
    $id_tipoVehiculo = (int)$_POST['id_tipoVehiculo'];
    $id_marca = (int)$_POST['id_marca'];
    $id_modelo = (int)$_POST['id_modelo'];

    // Verificar si la placa ya existe
    $verificar = $con->prepare("SELECT id_placa FROM vehiculos WHERE id_placa = :placa");
    $verificar->bindParam(':placa', $placa);
    $verificar->execute();

    if ($verificar->rowCount() > 0) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'La placa ya se encuentra registrada en el sistema', '$pagina_retorno');
                });
            </script>";
        exit();
    } else {
        $insertar = $con->prepare("INSERT INTO vehiculos (id_placa, id_documento, caracteristicas, id_estado, id_tipoVehiculo, id_marca, id_modelo)
                                  VALUES (:placa, :id_documento, :caracteristicas, :id_estado, :id_tipoVehiculo, :id_marca, :id_modelo)");

        $insertar->bindParam(':placa', $placa);
        $insertar->bindParam(':id_documento', $id_documento);
        $insertar->bindParam(':caracteristicas', $caracteristicas);
        $insertar->bindParam(':id_estado', $id_estado);
        $insertar->bindParam(':id_tipoVehiculo', $id_tipoVehiculo);
        $insertar->bindParam(':id_marca', $id_marca);
        $insertar->bindParam(':id_modelo', $id_modelo);

        if ($insertar->execute()) {

            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('success', 'Vehiculo creado con exito', '$pagina_retorno');
                });
            </script>";
            exit();
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mostrarModal('mistake', 'Error al crear el vehiculo', '$pagina_retorno');
                });
            </script>";
            exit();
        }
    }
}

$usuario_foto_path = !empty($usuario['foto']) ? '../../uploads/' . $usuario['foto'] : '../../uploads/usu.png';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Vehiculo</title>
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
                <h1>Agregar Vehiculo</h1>

                <?php if (!$usuario): ?>

                    <!-- form usuario -->
                    <div class="search-user-container" style="width: 100%; max-width: 500px; margin: 70px auto; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); background-color: #5DADE2;">
                        <h2 style="color: white; text-align: center; margin-bottom: 20px;">Buscar Usuario</h2>

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="id_documento" class="form-label">Documento del Usuario</label>
                                <input type="text" class="form-input" id="id_documento" name="id_documento" required placeholder="Ingrese el documento del usuario">
                            </div>
                            <input type="submit" class="btn-submit" name="buscar_usuario" value="BUSCAR USUARIO">
                        </form>
                    </div>
                <?php else: ?>

                    <div class="content-container">
                        <div class="user-info">
                            <div class="user-avatar">
                                <img class="personaAzul" id="fotoUsuario" src="<?php echo $usuario_foto_path; ?>" alt="Foto de perfil" onerror="this.src='../../uploads/usu.png'">
                            </div>
                            <div class="user-details">
                                <p><strong>Datos del propietario</strong></p><br>
                                <p><strong>Nombre:</strong> <?php echo $usuario['nombre_completo']; ?></p>
                                <p><strong>Correo:</strong> <?php echo $usuario['correo']; ?></p>
                                <p><strong>Documento:</strong> <?php echo $usuario['id_documento']; ?></p>
                            </div>
                        </div>

                        <div class="form-container">
                            <div class="image-preview">
                                <div class="image-column">
                                    <div class="image-upload">
                                        <img src="../../uploads/default_vehiculos.png" alt="Primero debe registrar el vehiculo">
                                        <div class="overlay">
                                            <i class="bi bi-upload"></i>
                                            <span>Primero registre el vehiculo</span>
                                        </div>
                                    </div>
                                    <div class="image-title">Foto del vehiculo</div>
                                </div>

                                <div class="image-column">
                                    <div class="image-upload">
                                        <img src="../../uploads/default_tarjeta.png" alt="Primero debe registrar el vehiculo">
                                        <div class="overlay">
                                            <i class="bi bi-upload"></i>
                                            <span>Primero registre el vehiculo</span>
                                        </div>
                                    </div>
                                    <div class="image-title">Tarjeta de propiedad</div>
                                </div>
                            </div>

                            <div class="form-inputs">
                                <form action="" method="POST" class="grid-form">
                                    <input type="hidden" name="id_documento" value="<?php echo $usuario['id_documento']; ?>">

                                    <div class="form-group">
                                        <label for="id_tipoVehiculo" class="form-label">Tipo de Vehiculo</label>
                                        <select class="form-input" id="id_tipoVehiculo" name="id_tipoVehiculo" required>
                                            <option value="">Seleccione Tipo</option>
                                            <?php
                                            $consulta_tipos = $con->prepare("SELECT * FROM tipovehiculo ORDER BY tipoVehiculo");
                                            $consulta_tipos->execute();
                                            $tipos = $consulta_tipos->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($tipos as $tipo): ?>
                                                <option value="<?php echo $tipo['id_tipoVehiculo']; ?>">
                                                    <?php echo $tipo['tipoVehiculo']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="placa" class="form-label">Placa</label>
                                        <input type="text" class="form-input" id="placa" name="placa" required placeholder="Ingrese la placa" maxlength="7">
                                        <button type="button" class="edit-icon"><i class="bi bi-pencil-square"></i></button>
                                    </div>

                                    <div class="form-group">
                                        <label for="caracteristicas" class="form-label">Caracteristicas</label>
                                        <input type="text" class="form-input" id="caracteristicas" name="caracteristicas"
                                            placeholder="Caracteristicas del vehículo">
                                        <button type="button" class="edit-icon"><i class="bi bi-pencil-square"></i></button>
                                    </div>



                                    <div class="form-group">
                                        <label for="id_marca" class="form-label">Marca</label>
                                        <select class="form-input" id="id_marca" name="id_marca" required>
                                            <option value="">Seleccione Marca</option>
                                            <?php
                                            $consulta_marcas = $con->prepare("SELECT * FROM marca ORDER BY nombre_marca");
                                            $consulta_marcas->execute();
                                            $marcas = $consulta_marcas->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($marcas as $marca): ?>
                                                <option value="<?php echo $marca['id_marca']; ?>">
                                                    <?php echo $marca['nombre_marca']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="id_modelo" class="form-label">Modelo</label>
                                        <select class="form-input" id="id_modelo" name="id_modelo" required>
                                            <option value="">Seleccione Modelo</option>
                                            <?php
                                            $consulta_modelos = $con->prepare("SELECT * FROM modelo ORDER BY fecha_modelo DESC");
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
                                        <input type="submit" class="btn-submit" name="registrar" value="REGISTRAR VEHICULO">
                                        <a href="<?php echo ($origen === 'usuarios') ? "vehiculos_usuarios.php?user_id=$user_id" : 'vehiculos.php'; ?>" class="btn-return">← Volver</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
        function limitar(elemento, limite) {
            elemento.addEventListener("input", () => {
                if (elemento.value.length > limite) {
                    elemento.value = elemento.value.substring(0, limite)
                }

            })
        }

        const id_documento = document.getElementById("id_documento")
        const placa = document.getElementById("placa")
        const caracteristicas = document.getElementById("caracteristicas")

        if (id_documento) limitar(id_documento, 10);
        if (placa) limitar(placa, 6);
        if (caracteristicas) limitar(caracteristicas, 50);

        async function buscarUsuario(documento) {
            try {
                const response = await fetch('buscar_usu_vehiculo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `documento=${documento}`
                });

                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }

                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error:', error);
                return {
                    success: false,
                    message: error.message
                };
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const inputDocumento = document.getElementById('id_documento');
            if (inputDocumento) {
                inputDocumento.addEventListener('keypress', async function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const documento = this.value.trim();
                        if (documento) {
                            const resultado = await buscarUsuario(documento);
                            if (resultado.success) {
                                document.querySelector('form').submit();
                            } else {
                                alert(resultado.message || 'Usuario no encontrado');
                            }
                        }
                    }
                });
            }
        });

        const placas = document.getElementById('placa');
        var correct = true;
        if (placas) {
             
            placas.addEventListener('input', function() {
                let value = this.value.toUpperCase();

                // Elimina todo lo que no sea letra o número
                value = value.replace(/[^A-Z0-9]/g, '');

                // Si empieza con número, limpiar el campo
                if (value.length > 0 && /^[0-9]/.test(value)) {
                    this.value = '';
                    return;
                }

                // Extraer letras y números (sin importar el orden)
                const letras = value.replace(/[^A-Z]/g, '').slice(0, 3);
                const numeros = value.replace(/[^0-9]/g, '').slice(0, 3);

                // Construir el valor final sin guion
                this.value = letras + numeros;
            });
        }
        
    </script>
</body>

</html>