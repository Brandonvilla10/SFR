<?php
session_start();
require_once('../database/database.php');
include('../roles/admin/components/alert.php');
$conex = new Database;
$con = $conex->conectar();

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['submit'])){

    
    $correo = $_POST['correo'];

    $sql = $con->prepare("SELECT * FROM usuario WHERE correo = '$correo' AND id_estado = 1");
    $sql->execute();
    $documento = $sql->fetch(PDO::FETCH_ASSOC);
    $pagina_retorno_mistake = '../recuperar_contraseña.php';

    if (!$documento){
        echo "  
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarModal('mistake', 'No se encuentra el usuario insertado', '$pagina_retorno_mistake');
            });
        </script>";
        exit();
    }else{

        $pagina_retorno = '../cam_contrasena.php';
        $ramdon = random_int(10000, 99999);
        $_SESSION['ramdon'] = $ramdon;
        $_SESSION['correo'] = $correo;

        $mail= new PHPMailer(true);

        try {
            //Server settings

            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;;
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'senafastregister@gmail.com';                     //SMTP username
            $mail->Password   = 'fdvygyyqzuhgcehy';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = 587;                                       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';
        
            //Recipients
            $mail->setFrom('senafastregister@gmail.com', 'SENA Fast Register');
            $mail->addAddress($correo);     //Add a recipient
            
            // Generar el HTML para los cuadros de dígitos
            $digitosHTML = '';
            $digitos = str_split($ramdon);
            foreach($digitos as $digito) {
                $digitosHTML .= "<div style='display: inline-block; width: 50px; height: 50px; line-height: 50px; text-align: center; border: 2px solid #39A900; margin: 0 3px; font-size: 24px; font-weight: bold; max-width: 100%; box-sizing: border-box; color: #333;'>" . $digito . "</div>";
            }
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "Correo de verificación para cambiar contraseña";
            $mail->Body    = "
            <div style=' color: #000000 ; font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                <div style='text-align: center; border-radius: 5px 5px 0 0;'>
                    <h1 style='margin: 3; font-size: 24px;'>Sena Fast Register</h1>
                </div>
                
                <div style='padding: 20px;'>
                    <p style='color: #000000; font-size: 16px; '>Buen dia,</p>
                    
                    <p style='color: #000000; font-size: 16px;'>Realizaste una solicitud para cambiar tu contraseña en la plataforma de SENAFastRegister. Si no fuiste tu, ignora este correo.</p>
                    
                    <div style='color: #000000; padding: 15px; border-left: 4px solid #ff0000; margin: 20px 0;'>
                        <p style='fcolor: #000000; font-weight: bold; margin: 0;'>NO COMPARTAS ESTE CODIGO CON NADIE</p>
                    </div>
                    
                    <div style=' text-align: center; margin: 20px 0;'>
                        <p style='color: #000000; font-size: 18px; margin-bottom: 15px;'>Codigo de verificación:</p>
                        <div style='display: inline-block;'>
                            " . $digitosHTML . "
                        </div>
                    </div>
                    
                    <p style='font-size: 14px; color: #666; margin-top: 30px;'>Este correo fue enviado automaticamente, por favor no responda a este mensaje.</p>
                </div>
                
                <div style=' padding: 15px; text-align: center; font-size: 12px; '>
                    &copy; " . date('Y') . " SENA Fast Register. Todos los derechos reservados.
                </div>
            </div>";
        
            $mail->send();
            echo "  
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('success', 'Correo enviado con exito', '$pagina_retorno');
                    });
                </script>";
                exit();
            } catch (Exception $e) {
                error_log("Error al enviar el correo: " . $mail->ErrorInfo);
                echo "  
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        mostrarModal('mistake', 'Error al enviar el correo, intente de nuevo', '$pagina_retorno_mistake');
                    });
                </script>";
                exit();
            }

    }
    
}

?>