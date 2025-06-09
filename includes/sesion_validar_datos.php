
<?php
function validarSesionRol($tipo, $rol) {
    if($tipo != $rol){
        echo "<script>
                alert('Rol no permitido');
                window.location.href = '../../../includes/sesion_destroy.php';
            </script>";
        exit();
    }
}
if(!isset($_SESSION['documento'])){

    unset($_SESSION['documento']);
    unset($_SESSION['tipo']);

    $_SESSION = array();
    session_destroy();
    session_write_close();

    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Sesion cerrada, por favor inicia sesion', '../../../index.php');
            });
        </script>";
    exit();
}
?>