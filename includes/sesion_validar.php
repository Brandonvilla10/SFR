
<?php
// VALIDACIONES DE SESIONES---------------------------

function validarSesionRol($tipo, $rol){

    if($rol == 2){

        if(!in_array($tipo, [2,3,4,7])){
            echo "<script>
                    alert('Rol no permitido');
                    window.location.href = '../../includes/sesion_destroy.php';
                </script>";
            exit();
        }


    }else{
        if($tipo != $rol){
            echo "<script>
                    alert('Rol no permitido');
                    window.location.href = '../../includes/sesion_destroy.php';
                </script>";
            exit();
        }

    }
}
// ------------------------------------------------
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