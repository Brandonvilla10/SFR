<?php

function generarExcelDinamico($columns, $data, $filename = null, $format = 'csv') 
{
    // Validaciones
    if (empty($columns) || empty($data)) {
        throw new Exception("Columnas y datos son requeridos");
    }
    
    // Nombre archivo
    if ($filename === null) {
        $filename = 'reporte_' . date('Y-m-d_H-i-s');
    }
    $filename = pathinfo($filename, PATHINFO_FILENAME);
    
    if ($format === 'excel') {
        // === GENERAR EXCEL HTML (VERSIÓN CORREGIDA) ===
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Limpiar buffer antes de enviar
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        echo '<html>';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<style>';
        echo 'table { border-collapse: collapse; font-family: Arial, sans-serif; width: 100%; }';
        echo 'th { background-color: #4CAF50; color: white; font-weight: bold; padding: 10px; border: 1px solid #ddd; text-align: center; }';
        echo 'td { padding: 8px; border: 1px solid #ddd; text-align: left; }';
        echo 'tr:nth-child(even) { background-color: #f9f9f9; }';
        echo 'tr:hover { background-color: #f5f5f5; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<table>';
        
        // Encabezados
        echo '<tr>';
        foreach ($columns as $column) {
            $headerName = ucfirst(str_replace('_', ' ', $column));
            echo '<th>' . htmlspecialchars($headerName, ENT_QUOTES, 'UTF-8') . '</th>';
        }
        echo '</tr>';
        
        // Datos
        foreach ($data as $record) {
            echo '<tr>';
            foreach ($columns as $column) {
                $value = isset($record[$column]) ? $record[$column] : '';
                // Limpiar y escapar el valor
                $cleanValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                echo '<td>' . $cleanValue . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</body>';
        echo '</html>';
        
    } else {
        // === GENERAR CSV ===
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Cache-Control: max-age=0');
        
        // Limpiar buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        $headers = array_map(function($col) {
            return ucfirst(str_replace('_', ' ', $col));
        }, $columns);
        fputcsv($output, $headers, ';');
        
        // Datos
        foreach ($data as $record) {
            $row = [];
            foreach ($columns as $column) {
                $value = isset($record[$column]) ? $record[$column] : '';
                // Limpiar caracteres problemáticos
                $cleanValue = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
                $row[] = $cleanValue;
            }
            fputcsv($output, $row, ';');
        }
        
        fclose($output);
    }
    
    exit;
}

/**
 * Función alternativa más simple (solo HTML sin XML)
 */
function generarExcelSimple($columns, $data, $filename = null) 
{
    // Nombre archivo
    if ($filename === null) {
        $filename = 'reporte_' . date('Y-m-d_H-i-s');
    }
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');
    
    // Limpiar cualquier salida previa
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    ?>
    <table border="1" style="border-collapse: collapse; font-family: Arial;">
        <tr style="background-color: #4CAF50; color: white; font-weight: bold;">
            <?php foreach ($columns as $column): ?>
                <th style="padding: 10px; text-align: center;">
                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $column)), ENT_QUOTES, 'UTF-8'); ?>
                </th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($data as $i => $record): ?>
            <tr style="<?php echo ($i % 2 == 0) ? 'background-color: #f9f9f9;' : ''; ?>">
                <?php foreach ($columns as $column): ?>
                    <td style="padding: 8px;">
                        <?php 
                        $value = isset($record[$column]) ? $record[$column] : '';
                        echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); 
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    exit;
}
?>