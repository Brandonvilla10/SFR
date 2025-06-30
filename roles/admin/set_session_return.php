<?php
session_start();

if (isset($_POST['volver'])) {
    $_SESSION['volver'] = urldecode($_POST['volver']); // Decodifica el valor
    echo json_encode([
        'success' => true,
    ]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'error']);
}

?>