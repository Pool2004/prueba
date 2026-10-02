<?php
// Configuración de la Base de Datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'nutricional_db');

// Crear conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error)));
}

// Establecer charset a UTF-8
$conn->set_charset("utf8");
?>
