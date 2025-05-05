<?php
    include ('../../database/database.php');
    $conexion = new database;
    $con = $conexion->conectar();
    session_start();
    
    ?>
<html>
<head>
    <title>Estado del Computador</title>
    <link rel="stylesheet" href="css/estado.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <script>
        function validateForm() {
            var serial = document.getElementById("serial").value;
            var documento = document.getElementById("documento").value;
            if (serial == "" || documento == "") {
                alert("Todos los campos deben ser llenados");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="container_grid">
        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>

        <nav class="menu estado-menu" style="margin-top: 40px;">

        </nav>

 <main class="grid_contenido_plantilla">
    <div class="container">
        <div class="title">Estado del computador</div>
        <div class="form-container">
            <h2>Prestamo de computadores</h2>
            <form onsubmit="return validateForm()">
                <label for="serial">Serial:</label>
                <input type="text" id="serial" name="serial">
                <label for="documento">Documento:</label>
                <input type="text" id="documento" name="documento">
                <input type="submit" value="Continuar" formaction="estado.2.php">
            </form>
        </div>
    </div>
 </main>
</div>
</body>
</html>