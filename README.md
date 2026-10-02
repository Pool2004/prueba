# Sistema de Nutrición Estudiantil - Guía de Instalación

## Requisitos
- PHP 7.4 o superior
- MySQL o MariaDB
- Servidor web local (XAMPP, WAMP, LAMP, etc.)
- Navegador web moderno

---

## Pasos de Instalación

### 1. Configurar la Base de Datos

#### Opción A: Crear la base de datos manualmente
```sql
-- Ejecuta estas sentencias SQL en phpMyAdmin o tu cliente MySQL

CREATE DATABASE nutricional_db;

USE nutricional_db;

CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    estatura DECIMAL(3,2) NOT NULL,
    imc DECIMAL(5,2) NOT NULL,
    clasificacion VARCHAR(50) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Opción B: Usar el script automático
1. Asegúrate que el servidor web está corriendo
2. Ve a: `http://localhost/prueba/database/crear_tabla.php`
3. Si ves el mensaje verde, la tabla fue creada exitosamente

### 2. Configurar conexión a Base de Datos

Edita el archivo `database/config.php` y actualiza los siguientes valores:

```php
define('DB_HOST', 'localhost');      // Host de MySQL
define('DB_USER', 'root');           // Usuario de MySQL
define('DB_PASS', '');               // Contraseña de MySQL (vacía si no tiene)
define('DB_NAME', 'nutricional_db'); // Nombre de la base de datos
```

### 3. Colocar archivos en el servidor web

1. Copia la carpeta `prueba/` en tu directorio raíz del servidor web:
   - **XAMPP**: `C:\xampp\htdocs\prueba`
   - **WAMP**: `C:\wamp\www\prueba`
   - **En Linux**: `/var/www/html/prueba`

### 4. Verificar instalación

1. Inicia tu servidor web (XAMPP, WAMP, etc.)
2. Abre tu navegador y ve a: `http://localhost/prueba/`
3. Deberías ver el formulario del calculador de IMC

---

## Estructura del Proyecto

```
prueba/
├── index.html                    # Página principal
├── styles/
│   └── style.css                # Estilos CSS
├── utils/
│   └── util.js                  # Lógica JavaScript
├── database/
│   ├── config.php               # Configuración de BD
│   ├── conexion.php             # Guardar datos del IMC
│   ├── obtener_estudiantes.php  # Obtener registros
│   └── crear_tabla.php          # Script para crear tabla
├── back/
│   └── main.py                  # Backend Python (opcional)
└── README.md                     # Este archivo
```

---

## Funcionalidades

✅ **Cálculo automático del IMC**
- Ingresa nombre, peso (kg) y estatura (m)
- El sistema calcula el IMC automáticamente

✅ **Alertas educativas personalizadas**
- Bajo peso
- Peso normal
- Sobrepeso
- Obesidad

✅ **Almacenamiento en Base de Datos**
- Guarda automáticamente cada registro
- Incluye fecha y hora del registro
- Posibilidad de consultar historiales

✅ **Interfaz educativa**
- Mensajes personalizados según resultado
- Recomendaciones de hábitos saludables
- Diseño responsivo y amigable

---

## Solución de Problemas

### Error: "No se puede conectar con el servidor"
- Verifica que PHP esté instalado: `php -v`
- Asegúrate que el servidor web esté corriendo
- Revisa la configuración en `database/config.php`

### Error: "Conexión fallida a la base de datos"
- Verifica el usuario y contraseña en `config.php`
- Asegúrate que MySQL esté corriendo
- Verifica que la base de datos `nutricional_db` existe

### Error: "Tabla 'estudiantes' no existe"
- Ve a `database/crear_tabla.php` en tu navegador
- O ejecuta el SQL manualmente desde phpMyAdmin

### Los datos no se guardan
- Verifica los permisos de escritura en la carpeta `database/`
- Revisa la consola del navegador para mensajes de error (F12)
- Verifica la carpeta Logs de PHP para errores

---

## Variables de Clasificación de IMC

| IMC | Clasificación | Color |
|-----|--------------|-------|
| < 18.5 | Bajo peso | Naranja |
| 18.5 - 24.9 | Peso normal | Verde |
| 25 - 29.9 | Sobrepeso | Rojo |
| ≥ 30 | Obesidad | Rojo oscuro |

---

## Notas Importantes

⚠️ **Esta es una herramienta educativa**, NO reemplaza la consulta con un profesional de salud.

⚠️ **Privacidad**: Los datos se almacenan en tu servidor local. Asegúrate de cumplir con leyes de protección de datos.

⚠️ **Acceso**: Considera agregar autenticación si se usa en un entorno productivo.

---

## Mejoras Futuras Sugeridas

- [ ] Agregar autenticación de usuarios
- [ ] Crear panel administrativo para ver registros
- [ ] Generar reportes PDF
- [ ] Integración con gráficas de seguimiento
- [ ] Exportar datos a Excel
- [ ] Validación de datos más robusta
- [ ] API REST completa

---

## Contacto y Soporte

Para la entrega del proyecto, envía a:
- alejandroescobar@comfandi.com.co
- alejandroescobar@comfandi.edu.co

---

**Última actualización:** 2026-10-02  
**Versión:** 1.0
