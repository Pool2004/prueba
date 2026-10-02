# 📐 DIAGRAMAS UML - Sistema de Nutrición Estudiantil

## 1. DIAGRAMA DE CASOS DE USO

```
┌─────────────────────────────────────────────────────────────────┐
│                      SISTEMA DE NUTRICIÓN                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────┐                                                │
│  │  Estudiante  │                                                │
│  └──────┬───────┘                                                │
│         │                                                         │
│         ├──────────────────────┐                                 │
│         │                      │                                 │
│    ┌────▼─────────┐    ┌──────▼──────────┐                      │
│    │ Registrarse  │    │ Calcular IMC    │                      │
│    └──────────────┘    └──────┬──────────┘                      │
│                               │                                  │
│                    ┌──────────┴──────────┐                      │
│                    │                     │                       │
│              ┌─────▼─────────┐   ┌──────▼──────────┐            │
│              │Ver Resultado  │   │Recibir Alerta  │            │
│              │   Educativo   │   │   Educativa    │            │
│              └────────────────┘   └─────────────────┘            │
│                                                                   │
│  ┌──────────────┐                                                │
│  │   Docente    │                                                │
│  └──────┬───────┘                                                │
│         │                                                         │
│         ├────────────────────────┐                               │
│         │                        │                               │
│    ┌────▼─────────────┐    ┌────▼────────────────┐              │
│    │ Ver Estadísticas │    │Exportar Registros  │              │
│    │   de Estudiantes │    │  (CSV/JSON)        │              │
│    └──────────────────┘    └────────────────────┘              │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. DIAGRAMA DE CLASES

```
┌─────────────────────────────────────────────────────────────────┐
│                         ESTUDIANTE                               │
├─────────────────────────────────────────────────────────────────┤
│ - id: int                                                         │
│ - nombre: string                                                  │
│ - peso: decimal                                                   │
│ - estatura: decimal                                               │
│ - imc: decimal                                                    │
│ - clasificacion: string                                           │
│ - fecha_registro: datetime                                        │
├─────────────────────────────────────────────────────────────────┤
│ + registrar(): void                                               │
│ + calcularIMC(): decimal                                          │
│ + clasificar(): string                                            │
│ + obtenerAlerta(): Alert                                          │
└─────────────────────────────────────────────────────────────────┘
            ▲
            │
            │ hereda
            │
┌─────────────────────────────────────────────────────────────────┐
│                      PERSONA (Abstract)                          │
├─────────────────────────────────────────────────────────────────┤
│ - nombre: string                                                  │
│ - peso: decimal                                                   │
│ - estatura: decimal                                               │
├─────────────────────────────────────────────────────────────────┤
│ + obtenerDatos(): void                                            │
│ + validarDatos(): boolean                                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      CLASIFICACION_IMC                           │
├─────────────────────────────────────────────────────────────────┤
│ - id: int                                                         │
│ - nombre: string                  ("Peso normal")                │
│ - imc_minimo: decimal              (18.5)                        │
│ - imc_maximo: decimal              (24.9)                        │
│ - color: string                    ("#4CAF50")                   │
│ - descripcion: string                                             │
│ - consejos: array                                                 │
├─────────────────────────────────────────────────────────────────┤
│ + validarRango(imc: decimal): boolean                            │
│ + obtenerConsejos(): array                                        │
│ + generarAlerta(): Alert                                          │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         ALERTA                                    │
├─────────────────────────────────────────────────────────────────┤
│ - id: int                                                         │
│ - titulo: string                                                  │
│ - mensaje: string                                                 │
│ - tipo: enum (ADVERTENCIA, EXITO, ERROR)                         │
│ - consejos: array                                                 │
│ - color: string                                                   │
├─────────────────────────────────────────────────────────────────┤
│ + mostrar(): void                                                 │
│ + registrarVista(): void                                          │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         REGISTRO                                  │
├─────────────────────────────────────────────────────────────────┤
│ - id: int                                                         │
│ - estudiante: Estudiante                                          │
│ - imc: decimal                                                    │
│ - clasificacion: ClasificacionIMC                                │
│ - fecha: datetime                                                 │
├─────────────────────────────────────────────────────────────────┤
│ + guardar(): boolean                                              │
│ + obtener(id: int): Registro                                     │
│ + listar(): array<Registro>                                      │
│ + exportar(formato: string): file                                │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. DIAGRAMA DE COMPONENTES

```
┌──────────────────────────────────────────────────────────────────────┐
│                     CAPA DE PRESENTACION                              │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    HTML5 (index.html)                         │   │
│  │  ┌─────────────┐  ┌──────────────┐  ┌──────────────────┐    │   │
│  │  │  Formulario │  │   Campos     │  │  Botón Calcular  │    │   │
│  │  │   de Datos  │  │  Validados   │  │  (JavaScript)    │    │   │
│  │  └─────────────┘  └──────────────┘  └──────────────────┘    │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
                                 ▲
                                 │ Fetch API (JSON)
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│                      CAPA DE ESTILOS (CSS3)                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────────┐  │
│  │   Variables  │  │   Flexbox    │  │   Gradientes y Sombras   │  │
│  │     CSS      │  │     Grid     │  │   (Responsive Design)    │  │
│  └──────────────┘  └──────────────┘  └──────────────────────────┘  │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                   CAPA DE LOGICA (JavaScript)                         │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │ util.js (ES6+)                                               │   │
│  │ ┌──────────────┐  ┌──────────────┐  ┌─────────────────────┐ │   │
│  │ │  Validación  │  │   Cálculo    │  │  Manejo de Eventos  │ │   │
│  │ │  de Entrada  │  │    de IMC    │  │  (listeners)        │ │   │
│  │ └──────────────┘  └──────────────┘  └─────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │ SweetAlert2 (Notificaciones)                                 │   │
│  │ ┌──────────────┐  ┌──────────────┐  ┌─────────────────────┐ │   │
│  │ │   Alertas    │  │   Mensajes   │  │  Iconos y Colores   │ │   │
│  │ │ Educativas   │  │ Personalizados│  │   Interactivos      │ │   │
│  │ └──────────────┘  └──────────────┘  └─────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
                                 ▲
                                 │ HTTP/JSON
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│                      CAPA DE APLICACION (PHP)                         │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │ conexion.php                                                 │   │
│  │ ┌─────────────────┐  ┌──────────────┐  ┌────────────────┐  │   │
│  │ │  Recibir JSON   │  │  Validación  │  │  Calcular IMC  │  │   │
│  │ │  del Cliente    │  │  en Servidor │  │  y Clasificar  │  │   │
│  │ └─────────────────┘  └──────────────┘  └────────────────┘  │   │
│  └──────────────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │ obtener_estudiantes.php                                      │   │
│  │ ┌─────────────────────────────────────────────────────────┐ │   │
│  │ │ Consultar BD y Devolver JSON                           │ │   │
│  │ └─────────────────────────────────────────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │ exportar.php                                                 │   │
│  │ ┌──────────────┐  ┌──────────────┐  ┌─────────────────────┐ │   │
│  │ │ Formato CSV  │  │ Formato JSON │  │  Descarga Archivo   │ │   │
│  │ └──────────────┘  └──────────────┘  └─────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
                                 ▲
                                 │ SQL (Prepared Statements)
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│                      CAPA DE DATOS (MySQL)                            │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │ Base de Datos: nutricional_db                                │   │
│  │ ┌──────────────────────────────────────────────────────────┐ │   │
│  │ │  Tabla: estudiantes                                     │ │   │
│  │ │  • id (INT, PRIMARY KEY)                                │ │   │
│  │ │  • nombre (VARCHAR 100)                                 │ │   │
│  │ │  • peso (DECIMAL 5,2)                                   │ │   │
│  │ │  • estatura (DECIMAL 3,2)                               │ │   │
│  │ │  • imc (DECIMAL 5,2)                                    │ │   │
│  │ │  • clasificacion (VARCHAR 50)                           │ │   │
│  │ │  • fecha_registro (TIMESTAMP)                           │ │   │
│  │ └──────────────────────────────────────────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 4. DIAGRAMA DE SECUENCIA - Registrar IMC

```
Estudiante          Navegador         Servidor PHP       Base de Datos
    │                   │                  │                    │
    │─ Llena Form ─────>│                  │                    │
    │                   │                  │                    │
    │ <─ Valida JS ─────│                  │                    │
    │                   │                  │                    │
    │ <─ Muestra Form ──│                  │                    │
    │                   │                  │                    │
    │─ Click Calcular ──>│                  │                    │
    │                   │─ POST JSON ─────>│                    │
    │                   │  (nombre, peso,  │                    │
    │                   │   estatura)      │                    │
    │                   │                  │                    │
    │                   │                  │─ Valida Datos      │
    │                   │                  │─ Calcula IMC       │
    │                   │                  │─ Clasifica         │
    │                   │                  │                    │
    │                   │                  │─ INSERT Query ────>│
    │                   │                  │                    │
    │                   │                  │<─ Confirmación ────│
    │                   │                  │                    │
    │                   │<─ JSON Response ─│                    │
    │                   │  (success, imc,  │                    │
    │                   │   clasificacion) │                    │
    │                   │                  │                    │
    │<─ SweetAlert ─────│                  │                    │
    │  (Mensaje        │                  │                    │
    │   Educativo)     │                  │                    │
    │                  │                  │                    │
    │─ Confirma ──────>│                  │                    │
    │<─ Limpia Form ───│                  │                    │
    │                  │                  │                    │
```

---

## 5. DIAGRAMA DE SECUENCIA - Ver Registros

```
Docente            Navegador         Servidor PHP       Base de Datos
   │                   │                   │                   │
   │─ Click Registros─>│                   │                   │
   │                   │─ GET Request ────>│                   │
   │                   │ (obtener_estudiantes.php)              │
   │                   │                   │                   │
   │                   │                   │─ SELECT Query ───>│
   │                   │                   │                   │
   │                   │                   │<─ Resultados ─────│
   │                   │                   │  (Array datos)    │
   │                   │                   │                   │
   │                   │<─ JSON Array ─────│                   │
   │                   │  (estudiantes)    │                   │
   │                   │                   │                   │
   │<─ Tabla HTML ─────│                   │                   │
   │  Renderizada      │                   │                   │
   │                   │                   │                   │
   │─ Estadísticas ───>│                   │                   │
   │  Procesadas       │                   │                   │
   │                   │                   │                   │
   │<─ Gráficos ───────│                   │                   │
   │  Mostrados        │                   │                   │
   │                   │                   │                   │
```

---

## 6. DIAGRAMA DE FLUJO - Cálculo de IMC

```
                    ┌─────────────────┐
                    │     INICIO      │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │ Usuario lleña   │
                    │ formulario      │
                    └────────┬────────┘
                             │
                    ┌────────▼────────┐
                    │  Validar       │
                    │  Campos        │
                    └────┬───────┬───┘
                         │       │
                    Válido│       │No válido
                         │       └──────────────┐
                         │                      │
                    ┌────▼──────┐       ┌──────▼─────┐
                    │ Calcular  │       │  Mostrar   │
                    │ IMC       │       │  Error     │
                    └────┬──────┘       └────────────┘
                         │
                    ┌────▼──────────┐
                    │ IMC =         │
                    │ peso/(est²)   │
                    └────┬──────────┘
                         │
                    ┌────▼──────────────────┐
                    │ ¿IMC < 18.5?          │
                    └─┬──────────────────┬──┘
                  Sí │                   │ No
          ┌──────────┘                   └──────────┐
          │                                         │
      ┌───▼─────┐                         ┌────────▼────┐
      │  Bajo   │                         │¿IMC < 25?   │
      │  Peso   │                         └──┬────────┬──┘
      └─────────┘                         Sí │        │ No
                                    ┌────────┘        └─────────┐
                                    │                           │
                              ┌─────▼──────┐           ┌────────▼──┐
                              │   Peso     │           │¿IMC < 30? │
                              │  Normal    │           └──┬────┬───┘
                              └────────────┘           Sí │    │ No
                                                  ┌──────┘    └─┐
                                                  │             │
                                            ┌─────▼──┐   ┌──────▼──┐
                                            │Sobrepeso   │ Obesidad│
                                            └───────────  └─────────┘
                                                  │
                    ┌─────────────────────────────┼──────────────────────────┐
                    │                             │                          │
              ┌─────▼──────┐              ┌──────▼──────┐           ┌───────▼────┐
              │ Mostrar    │              │   Mostrar   │           │  Mostrar   │
              │ Alerta     │              │   Alerta    │           │   Alerta   │
              │ Naranja    │              │   Verde     │           │   Roja     │
              │ (Consejos) │              │ (Éxito)     │           │ (Riesgo)   │
              └─────┬──────┘              └──────┬──────┘           └───────┬────┘
                    │                            │                         │
                    │                      ┌─────▼───────────────────────┬─┘
                    └─────────────┬────────┘                             │
                                  │                                      │
                            ┌─────▼──────────────────────────────────┐   │
                            │ Guardar en Base de Datos              │   │
                            │ • Nombre                              │   │
                            │ • Peso                                │   │
                            │ • Estatura                            │   │
                            │ • IMC                                 │   │
                            │ • Clasificación                       │   │
                            │ • Fecha/Hora                          │   │
                            └─────┬──────────────────────────────────┘   │
                                  │                                      │
                            ┌─────▼──────────────────────────────────┐   │
                            │ Limpar formulario                      │   │
                            └─────┬──────────────────────────────────┘   │
                                  │                                      │
                                  └──────────────────────────────────────┘
                                             │
                                      ┌──────▼──────┐
                                      │   FIN       │
                                      └─────────────┘
```

---

## 7. DIAGRAMA DE ENTIDAD-RELACIÓN (ER)

```
┌──────────────────────────────┐
│        ESTUDIANTES           │
├──────────────────────────────┤
│ PK│ id             INT        │
│   │ nombre         VARCHAR    │
│   │ peso           DECIMAL    │
│   │ estatura       DECIMAL    │
│   │ imc            DECIMAL    │
│ FK│ clasificacion  INT        │◄─────────────┐
│   │ fecha_registro TIMESTAMP  │              │
└──────────────────────────────┘              │
                                              │
                                    ┌─────────┴──────────────┐
                                    │ (1:N)                  │
                                    │                        │
                    ┌───────────────────────────────────────┐
                    │  CLASIFICACION_IMC                    │
                    ├───────────────────────────────────────┤
                    │ PK│ id              INT               │
                    │   │ nombre          VARCHAR           │
                    │   │ imc_minimo      DECIMAL           │
                    │   │ imc_maximo      DECIMAL           │
                    │   │ color           VARCHAR           │
                    │   │ descripcion     TEXT              │
                    └───────────────────────────────────────┘
```

---

## 8. DIAGRAMA DE ARQUITECTURA (Vista General)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         CLIENTE (NAVEGADOR)                          │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                  HTML5 + CSS3 + JavaScript                  │   │
│  │  ┌────────────────┐  ┌────────────────┐  ┌──────────────┐  │   │
│  │  │ index.html     │  │ registros.html │  │ style.css    │  │   │
│  │  └────────────────┘  └────────────────┘  └──────────────┘  │   │
│  │  ┌──────────────────────────────────────────────────────┐  │   │
│  │  │              util.js (Lógica Principal)             │  │   │
│  │  │  • Validación de datos                              │  │   │
│  │  │  • Cálculo de IMC                                   │  │   │
│  │  │  • Comunicación con servidor (Fetch API)            │  │   │
│  │  │  • Manejo de eventos                                │  │   │
│  │  │  • Integración con SweetAlert2                      │  │   │
│  │  └──────────────────────────────────────────────────────┘  │   │
│  └─────────────────────────────────────────────────────────────┘   │
└──────────────────────────────┬──────────────────────────────────────┘
                               │
                        Fetch API (JSON)
                        HTTP(S)
                               │
                               ▼
┌──────────────────────────────────────────────────────────────────────┐
│                    SERVIDOR WEB (Apache/PHP)                         │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    Middleware PHP                            │   │
│  │  ┌─────────────────────────────────────────────────────────┐│   │
│  │  │ config.php          → Configuración de conexión        ││   │
│  │  │ conexion.php        → API Guardar IMC                  ││   │
│  │  │ obtener_estudiantes → API Obtener registros            ││   │
│  │  │ exportar.php        → API Exportar datos               ││   │
│  │  │ crear_tabla.php     → Crear tabla automáticamente      ││   │
│  │  │ validar.php         → Sistema diagnóstico              ││   │
│  │  └─────────────────────────────────────────────────────────┘│   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────┬──────────────────────────────────────┘
                               │
                        SQL (Prepared Statements)
                               │
                               ▼
┌──────────────────────────────────────────────────────────────────────┐
│                       BASE DE DATOS (MySQL)                          │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  nutricional_db                                              │   │
│  │  ┌────────────────────────────────────────────────────────┐ │   │
│  │  │ Tabla: estudiantes                                     │ │   │
│  │  │ ├─ id (INT, PK, AUTO_INCREMENT)                        │ │   │
│  │  │ ├─ nombre (VARCHAR 100)                                │ │   │
│  │  │ ├─ peso (DECIMAL 5,2)                                  │ │   │
│  │  │ ├─ estatura (DECIMAL 3,2)                              │ │   │
│  │  │ ├─ imc (DECIMAL 5,2)                                   │ │   │
│  │  │ ├─ clasificacion (VARCHAR 50)                          │ │   │
│  │  │ └─ fecha_registro (TIMESTAMP DEFAULT CURRENT)          │ │   │
│  │  └────────────────────────────────────────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 9. DIAGRAMA DE SEGURIDAD

```
┌─────────────────────────────────────────────────────────────┐
│               CAPAS DE SEGURIDAD                             │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  CAPA 1: VALIDACIÓN EN CLIENTE (JavaScript)                 │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ • Verificar campos no vacíos                          │ │
│  │ • Validar tipos de datos                             │ │
│  │ • Rango válido de peso y estatura                    │ │
│  │ • Mensajes de error claros                           │ │
│  └───────────────────────────────────────────────────────┘ │
│                      ▼                                       │
│  CAPA 2: VALIDACIÓN EN SERVIDOR (PHP)                       │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ • Verificar datos recibidos                           │ │
│  │ • Validar tipos y rangos nuevamente                  │ │
│  │ • Sanitización con htmlspecialchars()                │ │
│  │ • Validar que valores sean numéricos                 │ │
│  └───────────────────────────────────────────────────────┘ │
│                      ▼                                       │
│  CAPA 3: SEGURIDAD EN BASE DE DATOS                         │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ Prepared Statements (MySQLi)                         │ │
│  │ ┌──────────────────────────────────────────────────┐│ │
│  │ │ $stmt = $conn->prepare(                         ││ │
│  │ │   "INSERT INTO estudiantes                      ││ │
│  │ │   (nombre, peso, estatura, imc, clasificacion) ││ │
│  │ │   VALUES (?, ?, ?, ?, ?)"                       ││ │
│  │ │ );                                               ││ │
│  │ │                                                  ││ │
│  │ │ $stmt->bind_param(                              ││ │
│  │ │   "sddss",                                       ││ │
│  │ │   $nombre, $peso, $estatura, $imc, $clasificacion ││ │
│  │ │ );                                               ││ │
│  │ └──────────────────────────────────────────────────┘│ │
│  │                                                      │ │
│  │ ✅ Previene SQL Injection                           │ │
│  │ ✅ Tipos de datos validados                         │ │
│  │ ✅ Queries preparadas y seguras                     │ │
│  └───────────────────────────────────────────────────────┘ │
│                      ▼                                       │
│  CAPA 4: MANEJO DE ERRORES                                  │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ • Try-Catch para excepciones                         │ │
│  │ • Mensajes de error sin exponer código               │ │
│  │ • Logging de errores (backend)                       │ │
│  │ • Respuestas JSON con status claro                  │ │
│  └───────────────────────────────────────────────────────┘ │
│                      ▼                                       │
│  CAPA 5: PRIVACIDAD DE DATOS                               │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ • Charset UTF-8 correcto                            │ │
│  │ • Headers HTTP seguros                               │ │
│  │ • No exponer errores de BD al cliente                │ │
│  │ • Credenciales en config.php (no comiteadas)        │ │
│  └───────────────────────────────────────────────────────┘ │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 10. TABLA DE ESTADOS - Estados Posibles del Sistema

```
┌─────────────────────┬─────────────────────┬──────────────────────┐
│    ESTADO           │   DESCRIPCIÓN       │   ACCIONES           │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ INICIAL             │ Usuario en home     │ Llenar formulario    │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ VALIDANDO           │ Datos en proceso    │ Esperar respuesta    │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ CALCULANDO          │ IMC en cálculo      │ Mostrar loading      │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ GUARDANDO           │ Datos en BD         │ Enviar al servidor   │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ RESULTADO_POSITIVO  │ IMC normal          │ Mostrar alerta verde │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ RESULTADO_ALERTA    │ IMC con riesgo      │ Mostrar alerta roja  │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ ERROR_VALIDACION    │ Datos inválidos     │ Mostrar error        │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ ERROR_BD            │ Problema guardando  │ Mostrar error BD     │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ REGISTROS_VISIBLE   │ Panel de registros  │ Ver tabla + gráficos │
├─────────────────────┼─────────────────────┼──────────────────────┤
│ EXPORTANDO          │ Descargando datos   │ Archivo CSV/JSON     │
└─────────────────────┴─────────────────────┴──────────────────────┘
```

---

**Creado:** 2 de octubre de 2026  
**Versión:** 1.0  
**Sistema:** Nutrición Estudiantil
