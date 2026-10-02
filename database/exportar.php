<?php
header('Content-Type: application/json; charset=utf-8');

// Incluir configuración
require_once 'config.php';

// Obtener parámetro de formato
$formato = isset($_GET['formato']) ? $_GET['formato'] : 'json';

// Consultar estudiantes
$sql = "SELECT id, nombre, peso, estatura, imc, clasificacion, DATE_FORMAT(fecha_registro, '%Y-%m-%d %H:%i:%s') as fecha_registro FROM estudiantes ORDER BY fecha_registro DESC";
$result = $conn->query($sql);

if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="registros_imc_' . date('Y-m-d_H-i-s') . '.csv"');
    
    // Crear archivo CSV
    $output = fopen('php://output', 'w');
    
    // Escribir encabezados
    fputcsv($output, array('ID', 'Nombre', 'Peso (kg)', 'Estatura (m)', 'IMC', 'Clasificación', 'Fecha'), ';');
    
    // Escribir datos
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, array(
                $row['id'],
                $row['nombre'],
                $row['peso'],
                $row['estatura'],
                $row['imc'],
                $row['clasificacion'],
                $row['fecha_registro']
            ), ';');
        }
    }
    
    fclose($output);
} else if ($formato === 'json') {
    $estudiantes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = $row;
        }
        echo json_encode(array(
            'success' => true,
            'total' => count($estudiantes),
            'data' => $estudiantes,
            'fecha_exportacion' => date('Y-m-d H:i:s')
        ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array(
            'success' => true,
            'total' => 0,
            'data' => array(),
            'fecha_exportacion' => date('Y-m-d H:i:s')
        ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Formato no válido. Use json o csv'));
}

$conn->close();
?>
