<?php
session_start();
include('components/alert.php');
require_once('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../includes/sesion_validar.php');

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/LogoFR.png">
    <title>DATOS</title>
    <link rel="stylesheet" href="css/datos.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container_grid">
    <aside class="aside_barra">
        <?php include ('asideBar/asideBar.php') ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="titulo">
            <div class="tituloDatos">
                <h1>Datos</h1>
                <p>Administra los datos segun tu necesidad</p>
            </div>
        </div>

        <hr class="separador">
        <div class="contenido_tabla">
        <div class="objects-grid">
            <div class="object-card" onclick="window.location.href='apartado_datos/fichas.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Fichas</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear fichas</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/programas.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Programas de Formacion</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear programas de formacion</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/nivel.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Niveles de Formacion</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear niveles de formacion</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/marcas.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Marcas</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear marcas</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/modelo.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Modelos de Vehiculos</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear modelos de vehiculos</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/tipoVeh.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Tipos de Vehiculos</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear tipos de vehiculos</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/tipoObj.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Tipos de Objetos</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear tipos de objetos</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/estado.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Estados</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras visualizar, editar, eliminar y crear estados</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
            <div class="object-card" onclick="window.location.href='apartado_datos/codigoQR.php'">
                <div class="object-header">
                    <div class="object-icon">
                        <img src="../../assets/img/datos/datos.png">
                    </div>
                    <div class="object-title">
                        <div class="object-name">Generar  QR</div>
                    </div>
                </div>
                <div class="object-details">
                    <div class="detail-item">
                        <span class="detail-label">Podras crear documentos PDF con el QR de la persona</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </main>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script>
    // Animaciones para hacer visible las tarjetas
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.object-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('visible');
            }, 100 * index);
        });
    });
</script>
</body>
</html>
