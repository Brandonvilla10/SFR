<?php 

function crearCSV($columns, $informacion, $filename = 'export.csv') {
    // Limpiar cualquier salida previa que pueda corromper el archivo
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Configurar headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');
    
    // Crear el archivo en memoria
    $output = fopen('php://output', 'w');
    
    // Agregar BOM para UTF-8 (ayuda con caracteres especiales en Excel)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Escribir encabezados
    fputcsv($output, $columns, ',', '"');
    
    // Escribir datos
    foreach ($informacion as $row) {
        $rowData = [];
        foreach ($columns as $col) {
            // Manejar diferentes tipos de datos y valores nulos
            if (isset($row[$col])) {
                $value = $row[$col];
                // Convertir a string y limpiar caracteres problemáticos
                $value = is_array($value) ? json_encode($value) : (string)$value;
                $rowData[] = $value;
            } else {
                $rowData[] = '';
            }
        }
        fputcsv($output, $rowData, ',', '"');
    }
    
    fclose($output);
    exit();
}

// Función alternativa que guarda el archivo en el servidor
function crearCSVArchivo($columns, $informacion, $filename = 'export.csv', $path = './') {
    $filepath = $path . $filename;
    
    $file = fopen($filepath, 'w');
    
    // Agregar BOM para UTF-8
    fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Escribir encabezados
    fputcsv($file, $columns, ',', '"');
    
    // Escribir datos
    foreach ($informacion as $row) {
        $rowData = [];
        foreach ($columns as $col) {
            if (isset($row[$col])) {
                $value = $row[$col];
                $value = is_array($value) ? json_encode($value) : (string)$value;
                $rowData[] = $value;
            } else {
                $rowData[] = '';
            }
        }
        fputcsv($file, $rowData, ',', '"');
    }
    
    fclose($file);
    return $filepath;
}
