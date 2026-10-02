<?php
// TEST DE CONEXIÓN PHP
header('Content-Type: application/json; charset=utf-8');

$test_results = array(
    'php_version' => phpversion(),
    'servidor' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
    'metodo' => $_SERVER['REQUEST_METHOD'],
    'ruta_actual' => __FILE__,
    'directorio' => __DIR__,
    'extensiones' => array(
        'mysqli' => extension_loaded('mysqli'),
        'json' => extension_loaded('json'),
        'pdo' => extension_loaded('pdo')
    ),
    'memoria' => ini_get('memory_limit'),
    'max_upload' => ini_get('upload_max_filesize'),
    'mensaje' => '✅ PHP funciona correctamente!'
);

// Intentar conectar a BD
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
    
    if ($conn->connect_error) {
        $test_results['bd_conexion'] = 'ERROR: ' . $conn->connect_error;
        $test_results['bd_status'] = '❌ No conectado';
    } else {
        $test_results['bd_status'] = '✅ Conectado a ' . DB_NAME;
        $test_results['bd_host'] = DB_HOST;
    }
} else {
    $test_results['bd_status'] = '⚠️ config.php no encontrado';
}

echo json_encode($test_results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
