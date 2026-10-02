<?php
// Iniciar buffer de salida
ob_start();

header('Content-Type: application/json; charset=utf-8');

try {
    // Incluir configuración
    if (!file_exists(__DIR__ . '/config.php')) {
        throw new Exception('Archivo config.php no encontrado');
    }
    
    require_once __DIR__ . '/config.php';
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception('Conexión a BD fallida: ' . $conn->connect_error);
    }
    
    // Consultar estudiantes
    $sql = "SELECT id, nombre, peso, estatura, imc, clasificacion, fecha_registro FROM estudiantes ORDER BY fecha_registro DESC";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception('Error en la consulta: ' . $conn->error);
    }
    
    $estudiantes = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = $row;
        }
    }
    
    ob_end_clean();
    
    echo json_encode(array(
        'success' => true,
        'data' => $estudiantes,
        'total' => count($estudiantes),
        'message' => count($estudiantes) > 0 ? 'Registros encontrados' : 'No hay registros'
    ), JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    ob_end_clean();
    error_log($e->getMessage());
    
    http_response_code(400);
    echo json_encode(array(
        'success' => false,
        'data' => array(),
        'message' => $e->getMessage(),
        'error' => true
    ), JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>
