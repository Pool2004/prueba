<?php
// Este archivo crea la tabla necesaria para el proyecto
// Ejecuta este archivo UNA SOLA VEZ en tu navegador

header('Content-Type: text/html; charset=utf-8');

require_once 'config.php';

// SQL para crear la tabla
$sql = "CREATE TABLE IF NOT EXISTS estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    estatura DECIMAL(3,2) NOT NULL,
    imc DECIMAL(5,2) NOT NULL,
    clasificacion VARCHAR(50) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<h2 style='color: green;'>✓ Tabla 'estudiantes' creada exitosamente</h2>";
    echo "<p>La tabla está lista para recibir datos.</p>";
} else {
    echo "<h2 style='color: red;'>✗ Error al crear la tabla: " . $conn->error . "</h2>";
}

$conn->close();
?>
