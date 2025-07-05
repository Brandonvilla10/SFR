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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin | Licencias</title>
    <link rel="stylesheet" href="./css/licencias.css">
    <link rel="stylesheet" href="./css/home.css">
</head>

<body>

    <div class="container_grid">

        <!-- Barra lateral -->
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">

            <div class="container">

                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>📋 Licencias Registradas</h2>
                        <button class="btn btn-primary" onclick="openLicenseModal()">
                            ➕ Agregar Licencia
                        </button>
                    </div>
                    <table class="table" id="licensesTable">
                        <tbody id="licensesTableBody">
                        </tbody>
                    </table>
                </div>

                <div class="table-container" style="margin-top: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>💻 Software Registrado</h2>
                        
                    </div>
                    <table class="table" id="softwareTable">
                        <tbody id="softwareTableBody">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal para Licencias -->
            <div id="licenseModal" class="modal">
                <div class="modal-content" style="max-width: 600px;">
                    <span class="close" onclick="closeLicenseModal()">&times;</span>
                    <h3 id="licenseModalTitle">Agregar Nueva Licencia</h3>
                    <form id="licenseForm" style="margin-top: 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label>Software:</label>
                                <input type="text" id="softwareName" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Clave de Licencia:</label>
                                <input type="text" id="licenseKey" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Asignado a:</label>
                                <input type="text" id="assignedTo" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Fecha de Expiración:</label>
                                <input type="date" id="expirationDate" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Estado:</label>
                                <select id="status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="active">Activa</option>
                                    <option value="inactive">Inactiva</option>
                                    <option value="expired">Expirada</option>
                                </select>
                            </div>
                            <div>
                                <label>Notas:</label>
                                <textarea id="notes" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                            </div>
                        </div>
                        <div style="margin-top: 20px; text-align: right;">
                            <button type="button" class="btn btn-secondary" onclick="closeLicenseModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal para Software -->
            <div id="softwareModal" class="modal">
                <div class="modal-content" style="max-width: 500px;">
                    <span class="close" onclick="closeSoftwareModal()">&times;</span>
                    <h3>Agregar Nuevo Software</h3>
                    <form id="softwareForm" style="margin-top: 20px;">
                        <div style="display: grid; gap: 15px;">
                            <div>
                                <label>Nombre:</label>
                                <input type="text" id="swName" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Versión:</label>
                                <input type="text" id="swVersion" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Proveedor:</label>
                                <input type="text" id="swVendor" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <label>Categoría:</label>
                                <select id="swCategory" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="office">Oficina</option>
                                    <option value="design">Diseño</option>
                                    <option value="development">Desarrollo</option>
                                    <option value="security">Seguridad</option>
                                    <option value="system">Sistema</option>
                                    <option value="other">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div style="margin-top: 20px; text-align: right;">
                            <button type="button" class="btn btn-secondary" onclick="closeSoftwareModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal de confirmación -->
            <div id="confirmModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h3>Confirmar Eliminación</h3>
                    <p>¿Estás seguro de que deseas eliminar este registro?</p>
                    <div style="margin-top: 20px;">
                        <button class="btn btn-danger" id="confirmDelete">Eliminar</button>
                        <button class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    </div>
                </div>
            </div>

        </main>

    </div>

    <script>
        // Almacenamiento de datos en memoria
        let licenses = [];
        let software = [];
        let deleteIndex = -1;
        let deleteType = '';
        let editingLicense = null;

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar datos de ejemplo
            loadSampleData();
            renderLicensesTable();
            renderSoftwareTable();

            // Event listeners para formularios
            document.getElementById('licenseForm').addEventListener('submit', handleLicenseSubmit);
            document.getElementById('softwareForm').addEventListener('submit', handleSoftwareSubmit);

            // Modal
            document.getElementById('confirmDelete').addEventListener('click', executeDelete);
        });

        function loadSampleData() {
            licenses = [{
                    id: 1,
                    software: 'Licencia De Un Año',
                    key: 'XXXXX-XXXXX-XXXXX-XXXXX',
                    assignedTo: 'Jhon Vaquiro',
                    expiration: '2025-12-31',
                    status: 'active',
                    notes: 'Licencia empresarial'
                },
                {
                    id: 2,
                    software: 'Licencia De Prueba',
                    key: 'YYYYY-YYYYY-YYYYY-YYYYY',
                    assignedTo: 'María González',
                    expiration: '2025-06-15',
                    status: 'expired',
                    notes: 'Renovar pronto'
                }
            ];

            software = [{
                    id: 1,
                    name: 'SENA FAST REGISTER',
                    version: '2024',
                    vendor: 'Team Sena Fast',
                    category: 'office',
                    registeredDate: '2025-01-15'
                },

            ];
        }

        function openLicenseModal() {
            editingLicense = null;
            document.getElementById('licenseModalTitle').textContent = 'Agregar Nueva Licencia';
            document.getElementById('licenseForm').reset();
            document.getElementById('licenseModal').style.display = 'block';
        }

        function openSoftwareModal() {
            document.getElementById('softwareModal').style.display = 'block';
        }

        function closeLicenseModal() {
            document.getElementById('licenseModal').style.display = 'none';
            editingLicense = null;
        }

        function closeSoftwareModal() {
            document.getElementById('softwareModal').style.display = 'none';
        }

        function handleLicenseSubmit(e) {
            e.preventDefault();

            const formData = {
                id: editingLicense ? editingLicense.id : Date.now(),
                software: document.getElementById('softwareName').value,
                key: document.getElementById('licenseKey').value,
                assignedTo: document.getElementById('assignedTo').value,
                expiration: document.getElementById('expirationDate').value,
                status: document.getElementById('status').value,
                notes: document.getElementById('notes').value
            };

            if (editingLicense) {
                // Actualizar licencia existente
                const index = licenses.findIndex(l => l.id === editingLicense.id);
                licenses[index] = formData;
                showNotification('Licencia actualizada exitosamente');
            } else {
                // Agregar nueva licencia
                licenses.push(formData);
                showNotification('Licencia agregada exitosamente');
            }

            renderLicensesTable();
            closeLicenseModal();
        }

        function handleSoftwareSubmit(e) {
            e.preventDefault();

            const formData = {
                id: Date.now(),
                name: document.getElementById('swName').value,
                version: document.getElementById('swVersion').value,
                vendor: document.getElementById('swVendor').value,
                category: document.getElementById('swCategory').value,
                registeredDate: new Date().toISOString().split('T')[0]
            };

            software.push(formData);
            renderSoftwareTable();
            closeSoftwareModal();
            document.getElementById('softwareForm').reset();

            showNotification('Software registrado exitosamente');
        }

        function editLicense(index) {
            editingLicense = licenses[index];
            document.getElementById('licenseModalTitle').textContent = 'Editar Licencia';
            
            // Llenar el formulario con los datos existentes
            document.getElementById('softwareName').value = editingLicense.software;
            document.getElementById('licenseKey').value = editingLicense.key;
            document.getElementById('assignedTo').value = editingLicense.assignedTo;
            document.getElementById('expirationDate').value = editingLicense.expiration;
            document.getElementById('status').value = editingLicense.status;
            document.getElementById('notes').value = editingLicense.notes;
            
            document.getElementById('licenseModal').style.display = 'block';
        }

        function renderLicensesTable() {
            const tbody = document.getElementById('licensesTableBody');
            tbody.innerHTML = '';

            licenses.forEach((license, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h4 style="margin: 0 0 8px 0; color: #333; font-size: 16px;">${license.software}</h4>
                                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Clave:</strong> ${license.key}</p>
                                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Asignado a:</strong> ${license.assignedTo}</p>
                                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Expiración:</strong> ${license.expiration}</p>
                                    <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;"><strong>Estado:</strong> <span class="status ${license.status}">${getStatusText(license.status)}</span></p>
                                    ${license.notes ? `<p style="margin: 0; color: #888; font-size: 12px; font-style: italic;">${license.notes}</p>` : ''}
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <button class="btn btn-secondary" onclick="editLicense(${index})" style="padding: 6px 12px; font-size: 12px;">
                                        ✏️ Editar
                                    </button>
                                    <button class="btn btn-danger" onclick="confirmDelete(${index}, 'license')" style="padding: 6px 12px; font-size: 12px;">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function renderSoftwareTable() {
            const tbody = document.getElementById('softwareTableBody');
            tbody.innerHTML = '';

            software.forEach((sw, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h4 style="margin: 0 0 8px 0; color: #333; font-size: 16px;">${sw.name}</h4>
                                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Versión:</strong> ${sw.version}</p>
                                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Proveedor:</strong> ${sw.vendor}</p>
                                    <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;"><strong>Categoría:</strong> ${getCategoryText(sw.category)}</p>
                                    <p style="margin: 0; color: #666; font-size: 14px;"><strong>Fecha Registro:</strong> ${sw.registeredDate}</p>
                                </div>
                                <div>
                                    
                                </div>
                            </div>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function getStatusText(status) {
            const statusMap = {
                'active': 'Activa',
                'inactive': 'Inactiva',
                'expired': 'Expirada'
            };
            return statusMap[status] || status;
        }

        function getCategoryText(category) {
            const categoryMap = {
                'office': 'Oficina',
                'design': 'Diseño',
                'development': 'Desarrollo',
                'security': 'Seguridad',
                'system': 'Sistema',
                'other': 'Otro'
            };
            return categoryMap[category] || category;
        }

        function confirmDelete(index, type) {
            deleteIndex = index;
            deleteType = type;
            document.getElementById('confirmModal').style.display = 'block';
        }

        function executeDelete() {
            if (deleteType === 'license') {
                licenses.splice(deleteIndex, 1);
                renderLicensesTable();
            } else if (deleteType === 'software') {
                software.splice(deleteIndex, 1);
                renderSoftwareTable();
            }

            closeModal();
            showNotification('Registro eliminado exitosamente');
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
            deleteIndex = -1;
            deleteType = '';
        }

        function showNotification(message) {
            // Crear notificación temporal
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #1E90FF;
                color: white;
                padding: 15px 25px;
                border-radius: 6px;
                box-shadow: 0 4px 15px rgba(30, 144, 255, 0.4);
                z-index: 1001;
                font-weight: 600;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            // Eliminar después de 3 segundos
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modals = ['confirmModal', 'licenseModal', 'softwareModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>

</body>

</html>