
<?php

session_start();

    require_once('../database/database.php');
    $conex = new database;               //  ESTO VA ENLAZADO CON EL ARCHIVO DATABASE.PHP
    $con = $conex->conectar();


    if(isset($_POST['submit'])){

        $documento = $_POST['documento'];
        $password = $_POST['password'];


        $sql = $con->prepare("SELECT * FROM usuario WHERE id_documento = '$documento'");
        $sql-> execute();

        $fila =$sql->fetch();

    

        if($fila && password_verify($password,$fila['contraseña']) && $fila['id_estado'] == 1){

            $_SESSION['documento']= $fila['id_documento'];
            $_SESSION['rol']= $fila['id_rol'];
            $_SESSION['nombre'] = $fila['nombre_completo'];
            

            // SOLO INSERTE 3 VARIABLES A LA GLOBAL SESION 

            if($_SESSION['rol'] == 1){

                            // ADMINISTRADOR
                echo "entro aqui";
                header("location: ../roles/admin/home.php");
                exit();

            }elseif($_SESSION['rol'] == 2){

                            // APRENDIZ
                echo "nooo entro aqui";
                header("location: ..roles/aprendiz/index.php");
                exit();

            }elseif($_SESSION['rol'] ==3){

                            // FUNCIONARIO
                echo "ni fruta idea";
                header("location: ..roles/funcionario/index.php");
                exit();
            }
        }
        else{
            echo "<script>alert('Error de usuario (inactivo o credenciales Invalidas)')</script>";
            echo "<script>window.location = '../index.php'</script>";
            exit();

         }



    }








?>