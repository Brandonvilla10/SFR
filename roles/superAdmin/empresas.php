<?php
// Validaciones-------------------------------------------
session_start();

require_once('../../database/database.php');
require_once('../../includes/sesion_validar.php');

// ---------------------------------------------

$conexion = new database;
$con = $conexion->conectar();

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';
$nombre = isset($_SESSION['nombre']) ? explode(" ", $_SESSION['nombre'])[0] : '';
$rol = isset($_SESSION['rol_name']) ? $_SESSION['rol_name'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

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
            padding-top: 10px;


        }

        .header {
            background: #63AEE8;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(30, 144, 255, 0.3);
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .user-info span {
            display: block;
            margin-bottom: 5px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #1E90FF;
        }

        .card h2 {
            color: #1E90FF;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1E90FF;
            box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
        }

        .btn {
            background: #1E90FF;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #187bcd;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 144, 255, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            margin-right: 5px;
        }

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

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            background-color: #4CAF50;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
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

        <!-- EMPRESAS CONTENIDO -->
        <main class="grid_contenido_plantilla">
            <div class="header">
                <h1>🏢 Gestión de Empresas</h1>
                <p>Panel de administración para empresas registradas</p>
            </div>

            <!-- Agrega este botón en tu HTML (puedes colocarlo cerca del título o encima de la tabla) -->
            <button class="btn" onclick="abrirModalAgregar()" style="margin-bottom: 15px;">
                <span style="font-size: 18px;">+</span> Agregar Empresa
            </button>

            <!-- Modal para agregar nueva empresa (similar al de editar pero vacío) -->
            <div id="agregarModal" class="modal">
                <div class="modal-content" style="max-width: 500px;">
                    <span class="close" onclick="closeModal('agregarModal')">&times;</span>
                    <h3 style="margin-bottom: 15px;">Agregar Nueva Empresa</h3>
                    <form id="agregarForm">
                        <div class="form-group">
                            <label for="newNombre">Nombre de la Empresa</label>
                            <input type="text" id="newNombre" required style="width: 100%; padding: 8px;">
                        </div>
                        <div class="form-group">
                            <label for="newNit">NIT</label>
                            <input type="text" id="newNit" required style="width: 100%; padding: 8px;">
                        </div>
                        <div class="form-group">
                            <label for="newRepresentante">Representante Legal</label>
                            <input type="text" id="newRepresentante" required style="width: 100%; padding: 8px;">
                        </div>
                        <div class="form-group">
                            <label for="newTelefono">Teléfono</label>
                            <input type="tel" id="newTelefono" required style="width: 100%; padding: 8px;">
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button type="submit" class="btn" style="flex: 1; padding: 10px;">Guardar</button>
                            <button type="button" class="btn" onclick="closeModal('agregarModal')"
                                style="flex: 1; padding: 10px; background-color: #6c757d;">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-container">
                <h2>📋 Empresas Registradas</h2>
                <table id="empresasTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>NIT</th>
                            <th>Representante</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SENA</td>
                            <td>15978941236</td>
                            <td>Jhon Vaquiro</td>
                            <td>3142822521</td>
                            <td><button class="btn" onclick="abrirModalEditar(0)">Editar</button></td>
                        </tr>
                        <tr>
                            <td>SENA CENTRO DE COMERCIO Y SERVICIOS</td>
                            <td>800987654-3</td>
                            <td>María González</td>
                            <td>3212706350</td>
                            <td><button class="btn" onclick="abrirModalEditar(1)">Editar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>

    </div>

    <!-- Modal para editar simplificado -->
    <div id="editarModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <span class="close" onclick="closeModal('editarModal')">&times;</span>
            <h3 style="margin-bottom: 15px;">Editar Empresa</h3>
            <form id="editarForm">
                <div class="form-group">
                    <label for="editNombre">Nombre de la Empresa</label>
                    <input type="text" id="editNombre" required style="width: 100%; padding: 8px;">
                </div>
                <div class="form-group">
                    <label for="editNit">NIT</label>
                    <input type="text" id="editNit" required style="width: 100%; padding: 8px;">
                </div>
                <div class="form-group">
                    <label for="editRepresentante">Representante Legal</label>
                    <input type="text" id="editRepresentante" required style="width: 100%; padding: 8px;">
                </div>
                <div class="form-group">
                    <label for="editTelefono">Teléfono</label>
                    <input type="tel" id="editTelefono" required style="width: 100%; padding: 8px;">
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn" style="flex: 1; padding: 10px;">Guardar</button>
                    <button type="button" class="btn" onclick="closeModal('editarModal')"
                        style="flex: 1; padding: 10px; background-color: #6c757d;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Variables globales
        let empresas = [];
        let editingIndex = -1;

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar datos de ejemplo
            loadSampleData();
            renderEmpresasTable();

            // Event listeners
            document.getElementById('editarForm').addEventListener('submit', handleEditarSubmit);
        });

        function loadSampleData() {
            empresas = [{
                    id: 1,
                    nombre: 'SENA CENTRO DE INDUSTRIA Y CONSTRUCCION',
                    nit: '900123456-7',
                    representante: 'Jhon Vaquiro',
                    telefono: '3142822521'
                },
                {
                    id: 2,
                    nombre: 'SENA CENTRO DE COMERCIO Y SERVICIOS',
                    nit: '800987654-3',
                    representante: 'María González López',
                    telefono: '3212706350'
                }
            ];
        }

        function renderEmpresasTable() {
            const tbody = document.querySelector('#empresasTable tbody');
            tbody.innerHTML = '';

            empresas.forEach((empresa, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${empresa.nombre}</td>
                <td>${empresa.nit}</td>
                <td>${empresa.representante}</td>
                <td>${empresa.telefono}</td>
                <td><button class="btn" onclick="abrirModalEditar(${index})">Editar</button></td>
            `;
                tbody.appendChild(row);
            });
        }

        function abrirModalEditar(index) {
            editingIndex = index;
            const empresa = empresas[index];

            document.getElementById('editNombre').value = empresa.nombre;
            document.getElementById('editNit').value = empresa.nit;
            document.getElementById('editRepresentante').value = empresa.representante;
            document.getElementById('editTelefono').value = empresa.telefono;

            document.getElementById('editarModal').style.display = 'block';
        }

        function handleEditarSubmit(e) {
            e.preventDefault();

            if (editingIndex === -1) return;

            const formData = {
                ...empresas[editingIndex],
                nombre: document.getElementById('editNombre').value,
                nit: document.getElementById('editNit').value,
                representante: document.getElementById('editRepresentante').value,
                telefono: document.getElementById('editTelefono').value
            };

            empresas[editingIndex] = formData;
            renderEmpresasTable();
            closeModal('editarModal');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            if (modalId === 'editarModal') {
                editingIndex = -1;
            }
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }


        // ... (mantén tus variables globales y funciones existentes)

        // Función para abrir el modal de agregar
        function abrirModalAgregar() {
            // Limpiar los campos del formulario
            document.getElementById('newNombre').value = '';
            document.getElementById('newNit').value = '';
            document.getElementById('newRepresentante').value = '';
            document.getElementById('newTelefono').value = '';

            document.getElementById('agregarModal').style.display = 'block';
        }

        // Función para manejar el envío del formulario de agregar
        document.getElementById('agregarForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Crear nuevo objeto empresa
            const nuevaEmpresa = {
                id: Date.now(), // Usamos el timestamp como ID único
                nombre: document.getElementById('newNombre').value,
                nit: document.getElementById('newNit').value,
                representante: document.getElementById('newRepresentante').value,
                telefono: document.getElementById('newTelefono').value
            };

            // Validar que el NIT no exista
            if (empresas.some(e => e.nit === nuevaEmpresa.nit)) {
                alert('El NIT ya existe en el sistema');
                return;
            }

            // Agregar la nueva empresa al array
            empresas.push(nuevaEmpresa);

            // Actualizar la tabla
            renderEmpresasTable();

            // Cerrar el modal
            closeModal('agregarModal');
        });

        // ... (mantén tus otras funciones existentes)
    </script>
</body>

</html>