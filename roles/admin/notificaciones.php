<?php
include('../../database/database.php');
session_start();
$conexion = new database;
$con = $conexion->conectar();

// Obtener el documento desde el formulario o la sesión
$documentoBuscado = $_GET['documento'] ?? $_SESSION['documento'] ?? null;

// Consulta dinámica
if ($documentoBuscado) {
    $sql = $con->prepare("SELECT * FROM notificaciones WHERE id_afectado = :documento ORDER BY fecha DESC");
    $sql->bindParam(':documento', $documentoBuscado);
} else {
    $sql = $con->prepare("SELECT * FROM notificaciones ORDER BY fecha DESC");
}
$sql->execute();
$notificaciones = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <style>

        main.grid_contenido_plantilla {
            margin: 0;
            padding: 0;
            margin-top: -150px;
        }

        .contenedor-notificaciones {
            margin: 0px ;
            padding: 50px;
        }

        .notificacion {
            background-color: #f2f2f2;
            border-left: 5px solid #63AEE8;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .notificacion h4 {
            margin: 0 10px 0;
            color: #333;
        }

        .notificacion p {
            margin: 0;
        }

        .notificacion time {
            display: block;
            font-size: 0.8em;
            color: #777;
            margin-top: 5px;
        }

        .form-busqueda {
            margin-bottom: 20px; 
        }

        .form-busqueda input {
            padding: 8px;
            width: 200px;
            margin-right: 10px;
        }

        .form-busqueda button {
            padding: 8px 12px;
            background-color: #63AEE8;
            border: none;
            color: white;
            cursor: pointer;
        }

        .grid-notificaciones {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            padding: 70px;
        }
    </style>
</head>
<body>

<div class="container_grid">
    <aside class="aside_barra">
        <?php include('asideBar/asideBar.php'); ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="contenedor-notificaciones">
            <h1 style="color: #63AEE8;">Notificaciones</h1>

            <form class="form-busqueda" method="GET">
                <input type="text" name="documento" placeholder="Buscar por documento..." value="<?php echo htmlspecialchars($documentoBuscado); ?>">
                <button type="submit">Buscar</button>
            </form>

            <?php if (count($notificaciones) > 0): ?>
                <div class="grid-notificaciones">
                    <?php foreach ($notificaciones as $notif): ?>
                        <div class="notificacion">
                            <h4><?php echo htmlspecialchars($notif['tipo_evento'] . ' en ' . $notif['tabla_afectada']); ?></h4>
                            <p><?php echo htmlspecialchars($notif['descripcion']); ?></p>
                            <time><?php echo date("d/m/Y H:i", strtotime($notif['fecha'])); ?></time>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No hay notificaciones encontradas.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="../../js/js.js"></script>
</body>
</html>
