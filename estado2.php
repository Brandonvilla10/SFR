<html>
<head>
    <title>Estado del Computador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/estado.2.css">
</head>
<body>
<?php include('<temple/barraLateral.html'); ?>

    <div class="container">
        <h1>Estado del computador</h1>
        <form id="estado-form">
            <label for="estado-computador">Estado del computador:</label>
            <select id="estado-computador" required>
                <option value="">Seleccione una opcion</option>
                <option value="bueno">Bueno</option>
                <option value="regular">Regular</option>
                <option value="malo">Malo</option>
            </select>
            <label for="estado-cargador">Estado del cargador:</label>
            <select id="estado-cargador" required>
                <option value="">Seleccione una opcion</option>
                <option value="bueno">Bueno</option>
                <option value="regular">Regular</option>
                <option value="malo">Malo</option>
            </select>
            <label for="estado-mouse">Estado del mouse:</label>
            <select id="estado-mouse" required>
                <option value="">Seleccione una opcion</option>
                <option value="bueno">Bueno</option>
                <option value="regular">Regular</option>
                <option value="malo">Malo</option>
            </select>
            <label for="fecha-uso">Fecha de uso:</label>
            <input type="text" id="fecha-uso" readonly>
            <label for="comentarios">Comentarios:</label>
            <textarea id="comentarios"></textarea>
            <div class="button-container">
                <input type="submit" value="Cancelar" formaction="index.php">
                <input type="submit" value="Continuar" formaction="index.php">
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var fechaUsoInput = document.getElementById('fecha-uso');
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var year = today.getFullYear();
            fechaUsoInput.value = day + '/' + month + '/' + year;
        });
    </script>
</body>
</html>