<?php
session_start();
require_once('../../includes/sesion_validar.php');

$codigo = $_SESSION['documento'] ?? '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = $_SESSION['rol_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <style>
        /* Mismo estilo que en empresas */
        * { margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .container_grid {
            display: grid;
            grid-template-columns: auto 1fr;
            min-height: 100vh;
        }
        .grid_contenido_plantilla {
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding: 40px;

        }
        .header {
            background: #63AEE8;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(30, 144, 255, 0.3);
        }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .header p { font-size: 1.1em; opacity: 0.9; }
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        @media (max-width: 768px) {
            .container_grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container_grid" id="ocultar_contenido">

    <!-- Barra lateral -->
    <aside class="aside_barra">
        <?php include('asideBar/asideBar.php') ?>
    </aside>

    <main class="grid_contenido_plantilla">
        <div class="header">
            <h1>👥 Gestión de Usuarios</h1>
            <p>Listado general de usuarios registrados</p>
        </div>

        <div class="table-container">
            <h2>📋 Usuarios Registrados</h2>
            <table id="usuariosTable">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Empresa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1106227450</td>
                        <td>Jhon Vaquiro</td>
                        <td>JhonVaquiro@gmail.com</td>
                        <td>Sena De Industria Y Construccion</td>
                    </tr>
                    <tr>
                        <td>1002003004</td>
                        <td>María González López</td>
                        <td>María González López@sena.edu.co</td>
                        <td>Sena De Comercio Y servicio</td>
                    </tr>
                 
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>
