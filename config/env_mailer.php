<?php
require_once('../database/database.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['submit'])){
    
    $email = $_POST['correo'];

    $sql = $con->prepare("SELECT * FROM usuario WHERE correo = '$email' AND id_estado = 1");
    $sql->execute();
    $documento = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$documento){
        $correoNoExiste = true;
    }else{

        $ramdon = random_int(10000, 99999);
        $_SESSION['ramdon'] = $ramdon;
        $_SESSION['correo'] = $correo;

        $mail= new PHPMailer(true);

        try {
            //Server settings

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;;
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'senafastregister@gmail.com';                     //SMTP username
            $mail->Password   = 'fdvygyyqzuhgcehy';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = 587;                                       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        
            //Recipients
            $mail->setFrom('senafastregister@gmail.com', 'Gaby');
            $mail->addAddress($email);     //Add a recipient
        
        
            //Content
        
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "Correo de verificación para cambiar contraseña";
            $mail->Body    = "
            <p>Buen dia,</p>
            <p>Realizaste una solicitud para cambiar tu contraseña en la plataforma de SENAFastRegister. Si no fuiste tú, ignora este correo.</p>
            <p>NO COMPARTAS ESTE CODIGO CON NADIE</p>
            <strong>Codigo De Verificacion '. $ramdon . ' </strong>";
        
            $mail->send();
            header("Location: ../env_correo.php");
            } catch (Exception $e) {
                error_log("Error al enviar el correo: " . $mail->ErrorInfo);
                header("Location: ../recuperar_contraseña.php");
            }

            //EL CORREO AUN NO ENVIA NI REDIRECCIONA LA PAGINA NI MUESTRA ERROR
    }
    
}

?>