<?php 

// el archivo csv es un formato que puede leer excel por eso se hace asi, no  me dejo instalar una libreria :c
function crearCSV($columns, $informacion, $filename = 'export.csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    
    fputcsv($output, $columns);
    // Escribir datos de los parametros

    
    foreach ($informacion as $row) {
        $rowData = [];
        foreach ($columns as $col) {
            $rowData[] = isset($row[$col]) ? $row[$col] : '';
        }
        fputcsv($output, $rowData);
    }
    fclose($output);
    exit();
}
?>   