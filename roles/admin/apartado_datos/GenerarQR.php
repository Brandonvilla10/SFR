<?php
session_start();
require_once('../../../database/database.php');
require_once('../../../js/TCPDF/tcpdf.php');

$conexion = new database;
$con = $conexion->conectar();

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
    $documento = $resultado['id_documento'];

    // Crear PDF
    ob_clean();

    // Crear PDF
    $pdf = new TCPDF();
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Estilo del código QR
    $style = [
        'border' => 1,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => [0, 0, 0],
        'bgcolor' => false,
        'module_width' => 1,
        'module_height' => 1
    ];

    // Generar QR (centrado en la página aproximadamente)
    $pdf->write2DBarcode((string)$documento, 'QRCODE,H', 85, 60, 40, 40, $style, 'N');

    // Texto debajo del QR
    $pdf->SetFont('helvetica', '', 14);
    $pdf->Ln(110);
    $pdf->Cell(0, 10, "Nombre: $nombre", 0, 1, 'C');
    $pdf->Cell(0, 10, "Documento: $documento", 0, 1, 'C');

    // Mostrar el PDF en navegador
    $pdf->Output('codigo_qr.pdf', 'I');

    exit();
}

