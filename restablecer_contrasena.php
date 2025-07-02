<?php 
session_start();
require_once("database/database.php");
$conexion = new database;
$con = $conexion->conectar();
include('roles/admin/components/alert.php');

if ($_SESSION['correo']){
    $correo = $_SESSION['correo'];
}else{
    echo "  
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'Correo no encontrado', 'index.php');
            });
        </script>";
        exit();
}

if(isset($_POST['submit'])){

    $contraseña = $_POST['contraseña'];
    $confirmarContraseña = $_POST['confirmarContraseña'];  

    if($contraseña === $confirmarContraseña){

        $contraseñaHash = password_hash($contraseña,PASSWORD_DEFAULT);

        $sql = $con->prepare("UPDATE usuario SET `contraseña` = :contrasena WHERE correo = :correo");
        $sql->bindParam(':correo', $correo, PDO::PARAM_STR);
        $sql->bindParam(':contrasena', $contraseñaHash, PDO::PARAM_STR);
        $sql->execute();
        unset($_SESSION['correo']);
        echo "  
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('success', 'Cambio hecho con exito', 'index.php');
            });
        </script>";
        exit();
    }
}

if(!isset($correo)){
    header("Location: template/destruirSesion.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="css/cambiar_contra.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

   <?php include('template/header.php')?>
    <main class= "division">

    <section class="form_place">

        <section class ="env_correo">

            

            <form action="" method="post" class="datos_form">
            <h1 style="padding-bottom: 20px; color: rgba(58, 170, 53, 255);">Recuperar contraseña</h1>  
                
                <input type="text" name="contraseña" id="contraseña" class="datos" placeholder="Nueva Contraseña">
                <label for="" id="errorContraseña" class="error"></label>
                <input type="text" name="confirmarContraseña" id="confirmarContraseña" class="datos" placeholder="Confirmar Contraseña">
                <label for="" id="errorConfirmarContraseña" class="error"></label>
                <input type="submit" id="submit" class="boton-submit botonIniciarSesion" name="submit" value="Siguiente" >
            
            </form>
        </section>

    </section>

     <img src="assets/img/recuperarContraseña/Image_RC.webp" class="imgPersonitas" alt="Imagen de recuperación de contraseña">



    </main>

    <?php include("template/footer.html") ?>



    <script>
    

const passRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@#$!%?&]{6,100}$/


    const contraseña = document.getElementById("contraseña");
    const confirmarContraseña = document.getElementById("confirmarContraseña")

    const errorContraseña = document.getElementById("errorContraseña");
    const errorConfirmarContraseña = document.getElementById("errorConfirmarContraseña");

    
    const submit = document.getElementById("submit")
    submit.classList.add("botonSinHover")
    submit.disabled = true;


    function incorrecto(regex,dato,error,mensaje,validacion){
        if(!regex.test(dato.value)){
            error.textContent = mensaje
            error.style.visibility = "visible"
            setTimeout(() => {
                error.textContent = ""

            }, 4000);
            dato.style.borderColor = "red"
            return false
        }else{
            dato.style.borderColor = "blue"
            
            return validacion = true;
        }
    }
// ---------------------------------------------------------------------------
let validarContraseña = false
let validarConfirmarContraseña = false

function actualizarBoton(){

    if(validarConfirmarContraseña && validarContraseña){
            document.getElementById("submit").disabled = false;
            submit.classList.remove("botonSinHover")
    }

}


    contraseña.addEventListener("blur",() =>{
        validarContraseña =  incorrecto(passRegex,contraseña,errorContraseña,"La contraseña debe tener minimo 6 digitos, una mayuscula, una minuscula y un simbolo especial!",validarContraseña)
        actualizarBoton()    
    })

    confirmarContraseña.addEventListener("blur",() =>{
       validarConfirmarContraseña = incorrecto(passRegex,confirmarContraseña,errorConfirmarContraseña,"Las contraseñas no coinciden",validarConfirmarContraseña)   
       actualizarBoton()
    })

</script>


</body>
</html>
