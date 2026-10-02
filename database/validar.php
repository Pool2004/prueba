<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación del Sistema</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 35%, #f8fafc 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(29, 78, 216, 0.15);
        }
        h1 {
            color: #1d4ed8;
            text-align: center;
            margin-bottom: 30px;
        }
        .check {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #ccc;
            border-radius: 5px;
            background: #f8fafc;
        }
        .check.ok {
            border-left-color: #4CAF50;
            background: #f1f5f4;
        }
        .check.error {
            border-left-color: #D32F2F;
            background: #fce4ec;
        }
        .check.warning {
            border-left-color: #FFA500;
            background: #fff3e0;
        }
        .check-icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }
        .check-text {
            flex: 1;
        }
        .check-text strong {
            display: block;
            margin-bottom: 5px;
        }
        .check-text small {
            color: #666;
        }
        .section {
            margin: 30px 0;
        }
        .section h2 {
            color: #1d4ed8;
            border-bottom: 2px solid #e0f2fe;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        a {
            display: inline-block;
            margin: 0 10px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #1d4ed8 0%, #153ea8 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        a:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Validación del Sistema</h1>

        <div class="section">
            <h2>1. Verificación del Servidor</h2>
            <?php
            // Verificar PHP
            echo '<div class="check ok">';
            echo '<div class="check-icon">✅</div>';
            echo '<div class="check-text">';
            echo '<strong>PHP ' . phpversion() . ' - Correcto</strong>';
            echo '<small>Versión recomendada: 7.4 o superior</small>';
            echo '</div></div>';

            // Verificar extensiones
            $extensiones = array('mysqli' => 'MySQLi', 'json' => 'JSON', 'curl' => 'cURL');
            foreach ($extensiones as $ext => $nombre) {
                if (extension_loaded($ext)) {
                    echo '<div class="check ok">';
                    echo '<div class="check-icon">✅</div>';
                    echo '<div class="check-text">';
                    echo '<strong>Extensión ' . $nombre . ' - Instalada</strong>';
                    echo '</div></div>';
                } else {
                    echo '<div class="check error">';
                    echo '<div class="check-icon">❌</div>';
                    echo '<div class="check-text">';
                    echo '<strong>Extensión ' . $nombre . ' - NO ENCONTRADA</strong>';
                    echo '<small>Es necesaria para el funcionamiento</small>';
                    echo '</div></div>';
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>2. Verificación de Archivos</h2>
            <?php
            $archivos = array(
                '../index.html' => 'Página Principal',
                '../styles/style.css' => 'Estilos CSS',
                '../utils/util.js' => 'Lógica JavaScript',
                '../registros.html' => 'Panel de Registros',
                'config.php' => 'Configuración de BD',
                'conexion.php' => 'API Guardar IMC',
                'obtener_estudiantes.php' => 'API Obtener Registros',
                'exportar.php' => 'Exportar Datos',
                'crear_tabla.php' => 'Crear Tabla BD'
            );

            foreach ($archivos as $ruta => $nombre) {
                $existe = file_exists(__DIR__ . '/' . $ruta);
                if ($existe) {
                    echo '<div class="check ok">';
                    echo '<div class="check-icon">✅</div>';
                    echo '<div class="check-text">';
                    echo '<strong>' . $nombre . '</strong>';
                    echo '<small>' . $ruta . '</small>';
                    echo '</div></div>';
                } else {
                    echo '<div class="check error">';
                    echo '<div class="check-icon">❌</div>';
                    echo '<div class="check-text">';
                    echo '<strong>' . $nombre . ' - NO ENCONTRADO</strong>';
                    echo '<small>' . $ruta . ' (falta copiar el archivo)</small>';
                    echo '</div></div>';
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>3. Verificación de Base de Datos</h2>
            <?php
            require_once 'config.php';

            // Intentar conectar
            if ($conn->connect_error) {
                echo '<div class="check error">';
                echo '<div class="check-icon">❌</div>';
                echo '<div class="check-text">';
                echo '<strong>Conexión a BD - FALLIDA</strong>';
                echo '<small>Error: ' . $conn->connect_error . '</small>';
                echo '<small style="display: block; margin-top: 5px;">Solución: Verifica los datos en config.php</small>';
                echo '</div></div>';
            } else {
                echo '<div class="check ok">';
                echo '<div class="check-icon">✅</div>';
                echo '<div class="check-text">';
                echo '<strong>Conexión a Base de Datos - OK</strong>';
                echo '<small>Host: ' . DB_HOST . ' | Base de datos: ' . DB_NAME . '</small>';
                echo '</div></div>';

                // Verificar tabla
                $sql_check = "SHOW TABLES LIKE 'estudiantes'";
                $result = $conn->query($sql_check);

                if ($result->num_rows > 0) {
                    echo '<div class="check ok">';
                    echo '<div class="check-icon">✅</div>';
                    echo '<div class="check-text">';
                    echo '<strong>Tabla "estudiantes" - EXISTE</strong>';
                    echo '<small>La base de datos está lista para guardar datos</small>';
                    echo '</div></div>';
                } else {
                    echo '<div class="check warning">';
                    echo '<div class="check-icon">⚠️</div>';
                    echo '<div class="check-text">';
                    echo '<strong>Tabla "estudiantes" - NO EXISTE</strong>';
                    echo '<small>Necesitas ejecutar crear_tabla.php para crearla</small>';
                    echo '</div></div>';
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>✅ Conclusión</h2>
            <p style="color: #666; line-height: 1.6;">
                Si ves todos los ✅ en verde, tu sistema está listo para usar. 
                Si hay ❌ en rojo, revisa las soluciones sugeridas.
            </p>
        </div>

        <div class="action-buttons">
            <a href="../index.html">Ir al Formulario</a>
            <a href="../registros.html">Ver Registros</a>
            <a href="crear_tabla.php">Crear Tabla BD</a>
        </div>

        <div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px;">
            <p>Sistema de Nutrición Estudiantil v1.0 | Comfandi 2026</p>
        </div>
    </div>
</body>
</html>
