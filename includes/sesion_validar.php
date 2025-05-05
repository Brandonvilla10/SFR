
<?php



if(!isset($_SESSION['documento'])){

    unset($_SESSION['documento']);
    unset($_SESSION['tipo']);

    $_SESSION = array();
    session_destroy();
    session_write_close();

    echo "<script> alert ('SESION CERRADA' )</script>";
    echo "<script> window.location= '../../index.php'</script>";
    exit();
}
?>