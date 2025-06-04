<?php
session_start();
require_once('../../../database/database.php');
require_once('../../../js/TCPDF/tcpdf.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../../includes/sesion_validar_datos.php');

if (isset($_POST['documento'])) {
    $documento = $_POST['documento'];
    $sql = $con->prepare("SELECT id_documento, nombre_completo FROM usuario WHERE id_documento = ?");
    $sql->execute([$documento]);

    $resultado = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        // Redirige con error si no existe el usuario
        header("Location: codigoQR.php?error");
        exit();
    }

    $nombre = $resultado['nombre_completo'];


    // Crear PDF
    ob_clean();

    // Configurar PDF en tamaño carta (216 x 279 mm)
    $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);

    // Eliminar márgenes por defecto
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);

    $pdf->AddPage();

    // Imagen de fondo - ajustada a toda la página carta
    $pdf->Image(
        '../imgAdmin/Reporte_plantilla.png',
        0,      // x = 0 (borde izquierdo)
        0,      // y = 0 (borde superior)
        216,    // ancho = ancho de página carta (216mm)
        279,    // alto = alto de página carta (279mm)
        'PNG',
        '',
        '',
        false,  // resize = false para estirar la imagen
        300,
        'C',    // align = centro
        false,
        false,
        0
    );

    // Estilo para QR con color #132370 (azul oscuro)
    $style = [
        'border' => 0,
        'vpadding' => 0,
        'hpadding' => 0,
        'fgcolor' => [19, 35, 112],  // RGB para #132370
        'bgcolor' => false,           // Fondo transparente
        'module_width' => 1,
        'module_height' => 1
    ];

    // Tamaño del QR (un poco más grande para tamaño carta)
    $qrSize = 80;

    // Posición horizontal centrada
    $x = ($pdf->getPageWidth() - $qrSize) / 2;

    // Altura total del contenido (QR + texto)
    $contenidoAltura = $qrSize + 10; // QR + espacio + texto

    // Posición vertical centrada
    $y = ($pdf->getPageHeight() - $contenidoAltura) / 2;

    // Dibujar QR centrado
    $pdf->write2DBarcode((string)$documento, 'QRCODE,H', $x, $y, $qrSize, $qrSize, $style, 'N');

    // Configuración de texto
    $pdf->SetTextColor(0, 0, 0);  // Texto en negro
    $pdf->SetFont('helvetica', '', 16);  // Tamaño de fuente un poco mayor

    // Posición Y para el texto (debajo del QR)
    $textY = $y + $qrSize + 14;

    // Texto centrado: nombre
    $pdf->SetXY(0, $textY);
    $pdf->Cell(0, 10, $nombre, 0, 1, 'C');



    // Mostrar PDF
    $pdf->Output('QR' . $nombre . '.pdf','D');
    exit();
}
