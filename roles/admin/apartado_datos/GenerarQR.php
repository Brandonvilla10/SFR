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
    $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->AddPage();


    // azul
    $pdf->SetFillColor(0, 134, 204);
    $pdf->RoundedRect(0, -4, 216, 30, 5, '1111', 'F'); // solo redondea abajo
    

    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(255  , 255, 255);
    // Encabezado sin línea, usando MultiCell para evitar bordes visibles o efectos raros
    $pdf->SetXY(10, 10);
    $pdf->MultiCell(0, 20, 'SENA FAST REGISTER', 0, 'C', 0, 1);
    $pdf->Ln(10); // Agrega espacio debajo del título

    // Estilo del código QR
    $style = [
        'border' => 0,
        'vpadding' => 0,
        'hpadding' => 0,
        'fgcolor' => [0, 0, 0],
        'bgcolor' => false,
        'module_width' => 1,
        'module_height' => 1
    ];

    // Generar QR (centrado en la página aproximadamente)
    $qrSize = 70; // tamaño en mm
    $x = ($pdf->getPageWidth() - $qrSize) / 2;
    $y = 80;
    $pdf->write2DBarcode((string)$documento, 'QRCODE,H', $x, $y, $qrSize, $qrSize, $style, 'N');;

    // Texto debajo del QR
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetY($y + $qrSize + 10);
    $pdf->Cell(0, 10, $nombre, 0, 1, 'C');
    $pdf->Cell(0, 10, $documento, 0, 1, 'C');

    // Mostrar el PDF en navegador
    $pdf->Output('codigo_qr.pdf', 'I');

    exit();
}
