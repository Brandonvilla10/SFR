<?php

include_once("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/asignar.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <title>Lista de Asignación</title>
</head>
<body>


<div class="container_grid">
        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>

        <nav class="menu estado-menu" style="margin-top: 40px;">

        </nav>
    <main class="grid_contenido_plantilla">

            <h1 style="color: #00a2ff;">Asignar computador</h1>
            <div class="container">
                <input type="file" id="csvFile" accept=".csv">
                <label for="csvFile">Selecciona Archivo</label>
                <button id="importBtn">Importar CSV</button>
                <button id="downloadBtn">Descargar CSV Actualizado</button>
            </div>
            <div class="table-container" style="display: none;">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Serial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se agregarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
    </main>
</div>
    <script>
        document.getElementById('importBtn').addEventListener('click', function() {
            const fileInput = document.getElementById('csvFile');
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const csvData = event.target.result;
                    const rows = csvData.trim().split('\n');
                    const tableBody = document.querySelector('#dataTable tbody');
                    tableBody.innerHTML = '';  // Limpiar la tabla antes de agregar nuevos datos

                    rows.forEach(row => {
                        const columns = row.split(',');
                        if (columns.length === 3) {
                            const newRow = document.createElement('tr');
                            newRow.innerHTML = `
                                <td>${columns[0].trim()}</td>
                                <td>${columns[1].trim()}</td>
                                <td><input type="text" value="${columns[2].trim()}" placeholder="Asignar Serial"></td>
                            `;
                            tableBody.appendChild(newRow);
                        }
                    });

                    // Mostrar la tabla después de importar el CSV
                    document.querySelector('.table-container').style.display = 'block';
                };
                reader.readAsText(file);
            } else {
                alert('Por favor, selecciona un archivo CSV.');
            }
        });

        document.getElementById('downloadBtn').addEventListener('click', function() {
            const tableBody = document.querySelector('#dataTable tbody');
            const rows = tableBody.querySelectorAll('tr');
            let csvContent = "Nombre,Documento,Serial\n";

            rows.forEach(row => {
                const columns = row.querySelectorAll('td');
                const name = columns[0].innerText;
                const document = columns[1].innerText;
                const serial = columns[2].querySelector('input').value;
                csvContent += `${name},${document},${serial}\n`;
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'datos_actualizados.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
</body>
</html>