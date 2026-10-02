<?php
// Iniciar buffer de salida para capturar errores
ob_start();

// Header JSON
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
    
    // Obtener datos del formulario
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    // Validar que se recibieron datos
    if (!$data) {
        $data = $_POST;
    }
    
    if (empty($data) || !is_array($data)) {
        throw new Exception('No se recibieron datos válidos');
    }
    
    // Validar campos requeridos
    if (empty($data['nombre']) || empty($data['peso']) || empty($data['estatura'])) {
        throw new Exception('Faltan datos requeridos (nombre, peso, estatura)');
    }
    
    // Sanitizar datos
    $nombre = htmlspecialchars(trim($data['nombre']), ENT_QUOTES, 'UTF-8');
    $peso = floatval($data['peso']);
    $estatura = floatval($data['estatura']);
    
    // Validar datos numéricos
    if ($peso <= 0 || $estatura <= 0) {
        throw new Exception('El peso y estatura deben ser mayores a 0');
    }
    
    if ($peso > 500 || $estatura > 3) {
        throw new Exception('Los valores parecen inválidos. Verifica el peso y estatura');
    }
    
    // Calcular IMC
    $imc = round($peso / ($estatura ** 2), 2);
    
    // Determinar clasificación del IMC
    function clasificarIMC($imc) {
        if ($imc < 18.5) {
            return array('clasificacion' => 'Bajo peso', 'alerta' => true);
        } elseif ($imc >= 18.5 && $imc < 25) {
            return array('clasificacion' => 'Peso normal', 'alerta' => false);
        } elseif ($imc >= 25 && $imc < 30) {
            return array('clasificacion' => 'Sobrepeso', 'alerta' => true);
        } else {
            return array('clasificacion' => 'Obesidad', 'alerta' => true);
        }
    }
    
    $resultado = clasificarIMC($imc);
    $clasificacion = $resultado['clasificacion'];
    $alerta = $resultado['alerta'];
    
    // Verificar que la tabla existe
    $table_check = $conn->query("SHOW TABLES LIKE 'estudiantes'");
    if ($table_check->num_rows == 0) {
        throw new Exception('Tabla "estudiantes" no existe. Ejecuta database/crear_tabla.php');
    }
    
    // Insertar en base de datos usando prepared statement
    $sql = "INSERT INTO estudiantes (nombre, peso, estatura, imc, clasificacion, fecha_registro) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
    }
    
    $stmt->bind_param("sddss", $nombre, $peso, $estatura, $imc, $clasificacion);
    
    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }
    
    $stmt->close();
    
    // Limpiar buffer y enviar respuesta exitosa
    ob_end_clean();
    
    echo json_encode(array(
        'success' => true,
        'message' => 'Datos guardados exitosamente',
        'data' => array(
            'nombre' => $nombre,
            'imc' => $imc,
            'clasificacion' => $clasificacion,
            'alerta' => $alerta
        )
    ), JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Limpiar buffer
    ob_end_clean();
    
    // Registrar error
    error_log($e->getMessage());
    
    // Enviar respuesta de error
    http_response_code(400);
    echo json_encode(array(
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ), JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>


