
<?php

session_start();
include('../roles/admin/components/alert.php');

    require_once('../database/database.php');
    $conex = new database;               //  ESTO VA ENLAZADO CON EL ARCHIVO DATABASE.PHP
    $con = $conex->conectar();


    if(isset($_POST['submit'])){

        $documento = $_POST['documento'];
        $password = $_POST['password'];

        $sql = $con->prepare("SELECT * FROM usuario WHERE id_documento = '$documento'");
        $sql-> execute();
        $fila =$sql->fetch();

        if($fila && password_verify($password,$fila['contraseña']) && $fila['id_estado'] != 2){

            $_SESSION['documento']= $fila['id_documento'];
            $_SESSION['rol']= $fila['id_rol'];
            $_SESSION['nombre'] = $fila['nombre_completo'];
                
            if($_SESSION['rol'] == 1){

                            // ADMINISTRADOR
                $_SESSION['rol_name'] ="Administrador";
                header("location: ../roles/admin/index.php");
                exit(); 

            }elseif($_SESSION['rol'] == 2){

                            // APRENDIZ
                $_SESSION['rol_name'] ="Aprendiz";
                header("location: ../roles/aprendiz/index.php");
                exit();

            }elseif($_SESSION['rol'] ==3){

                            // FUNCIONARIO
                $_SESSION['rol_name'] ="Funcionario";
                header("location: ../roles/funcionario/index.php");
                exit();
            }
        }elseif($fila && $fila['id_estado'] == 2){
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'Usuario inactivo, por favor comuniquese a senafastregister@gmail.com para ser activado nuevamente', '../index.php');
                    });
                </script>";
            exit();

        }else{
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'Credenciales incorrectas', '../index.php');
                    });
                </script>";
            exit();
        }



    }








?>