<html>
<head>
    <title>Estado del Computador</title>
    <link rel="stylesheet" href="css/estado.css">
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
<?php include('<temple/barraLateral.html'); ?>

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
</body>
</html>