
<?php



if(!isset($_SESSION['documento'])){

    unset($_SESSION['documento']);
    unset($_SESSION['tipo']);

    $_SESSION = array();
    session_destroy();
    session_write_close();

    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Sesion cerrada, por favor inicia sesion', '../../index.php');
            });
        </script>";
    exit();
}
?>