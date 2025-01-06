<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctenos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
        }
        h1 {
            color: #3498db;
            text-align: left;
        }
        .section {
            margin: 40px 0;
        }
        .section h2 {
            color: #3498db;
            font-size: 24px;
            text-align: left;
        }
        .section p {
            font-size: 16px;
            line-height: 1.5;
            text-align: left;
        }
        .contact-info {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin: 20px 0;
        }
        .contact-info i {
            font-size: 24px;
            margin-right: 10px;
            color: #25d366;
        }
        .contact-info .email i {
            color: #34a853;
        }
        .contact-info span {
            font-size: 18px;
        }
        .email-section {
            text-align: center;
            margin-left: 20%;
        }
        .email-section h2 {
            text-align: center;
        }
        .email-section .contact-info {
            justify-content: center;
        }
        .compact-text {
            max-width: 600px;
        }
        footer {
            background-color: #3498db;
            color: white;
            padding: 20px;
            text-align: left;
            line-height: 1.2;
            border-radius: 25px 25px 0 0;
            position: relative;
            height: auto;
            overflow: hidden;
        }
        footer p {
            margin: 0;
            padding: 5px 0;
        }
        .footer-right {
            text-align: right;
            margin-top: 10px;
            position: absolute;
            right: 45%;
            transform: translateX(20px);
            top: 110px;
        }
        @media (max-width: 1024px) {
            .container {
                width: 90%;
            }
            .email-section {
                margin-left: 0;
            }
            .footer-right {
                right: 40%;
            }
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
            .contact-info {
                flex-direction: column;
                align-items: flex-start;
            }
            .contact-info i {
                margin-bottom: 10px;
            }
            .email-section {
                text-align: center;
                margin-left: 0;
            }
            .email-section .contact-info {
                flex-direction: column;
                align-items: center;
            }
            .compact-text {
                max-width: 100%;
            }
            footer {
                text-align: center;
            }
            .footer-right {
                text-align: center;
                position: static;
                margin-top: 10px;
                transform: none;
            }
        }
    </style>
</head>
<body>
    <?php include("../SFR/template/header.php"); ?>
    <div class="container">
        <div class="section compact-text">
            <h1>Contáctenos</h1>
            <p>¿Tienes alguna pregunta o necesitas más información sobre FastRegister? ¡Estamos aquí para ayudarte! Nuestro equipo está disponible para responder tus consultas y brindarte el soporte que necesitas.</p>
        </div>
        <div class="section">
            <h2>Chat virtual</h2>
            <div class="contact-info">
                <i class="fab fa-whatsapp"></i>
                <span>309 8768568</span>
            </div>
            <div class="contact-info">
                <i class="fab fa-whatsapp"></i>
                <span>301 7467158</span>
            </div>
        </div>
        <div class="section email-section">
            <h2>Correo Electrónico</h2>
            <div class="contact-info email">
                <i class="fas fa-envelope"></i>
                <span>senafastregister@gmail.com</span>
            </div>
        </div>
        <div class="section">
            <h2>Horarios de atención</h2>
            <p>Soporte Tecnico Y Servicio Al Cliente</p>
            <p>Implementaciones</p>
        </div>
    </div>
    <footer>
        <p>Sena FastRegister - Dirección General</p>
        <p>141-Sector, Cra. 45 No. 1255, Ibagué, Tolima</p>
        <p>Contacto: 309 8768568</p>
        <p>Atención al cliente: Lunes a Viernes 8:00 am a 5:00 pm</p>
        <p>Correo: senafastregister@gmail.com</p>
        <div class="footer-right">
            <p>Copyright © 2024 FastRegister</p>
        </div>
    </footer>
</body>
</html>
