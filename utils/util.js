document.addEventListener("DOMContentLoaded", () => {
    console.log("Sistema de Nutrición Estudiantil Cargado");

    // Variables
    const buttonCalculate = document.getElementById("calculateImc") ?? null;
    const form = document.querySelector(".imc-form");

    if (!buttonCalculate) {
        Swal.fire({
            title: "Error",
            text: "Problemas con la página. Contacta al administrador.",
            icon: "error"
        });
    }

    // Obtener mensajes educativos según clasificación
    const obtenerMensajeEducativo = (clasificacion) => {
        const mensajes = {
            "Bajo peso": {
                titulo: " Bajo Peso",
                mensaje: "Se ha detectado que tu peso está por debajo de lo recomendado. Te recomendamos:",
                consejos: [
                    "- Consulta con un profesional de salud",
                    "- Aumenta el consumo de alimentos nutritivos",
                    "- Incluye proteínas en cada comida",
                    "- Realiza ejercicio regular",
                    "- Mantén un registro de tu alimentación"
                ],
                color: "#FFA500"
            },
            "Peso normal": {
                titulo: "Peso Normal",
                mensaje: "¡Excelente! Tu índice de masa corporal es saludable. Para mantenerlo:",
                consejos: [
                    "- Continúa con hábitos saludables",
                    "- Realiza actividad física regularmente",
                    "- Mantén una dieta balanceada",
                    "- Bebe suficiente agua",
                    "- Duerme 7-8 horas diarias"
                ],
                color: "#4CAF50"
            },
            "Sobrepeso": {
                titulo: "Sobrepeso",
                mensaje: "Se ha detectado sobrepeso. Te recomendamos tomar acciones:",
                consejos: [
                    "- Consulta con un profesional de salud",
                    "- Reduce el consumo de grasas y azúcares",
                    "- Aumenta la actividad física",
                    "- Come porciones más pequeñas",
                    "- Incluye más frutas y verduras"
                ],
                color: "#FF6B6B"
            },
            "Obesidad": {
                titulo: "Obesidad",
                mensaje: "Tu índice de masa corporal indica obesidad. Es importante actuar ahora:",
                consejos: [
                    "- Busca ayuda de un profesional de salud",
                    "- Comienza un programa de ejercicio moderado",
                    "- adopta una dieta balanceada y controlada",
                    "- participa en actividades recreativas",
                    "- mantén el apoyo de familia y amigos"
                ],
                color: "#D32F2F"
            }
        };

        return mensajes[clasificacion] || mensajes["Peso normal"];
    };

    // Función principal para calcular IMC
    const calcularYGuardar = (nombre, peso, estatura) => {
        // Validaciones
        if (!nombre || !peso || !estatura) {
            Swal.fire({
                title: "Campos Incompletos",
                text: "Por favor completa todos los campos",
                icon: "warning"
            });
            return;
        }

        peso = parseFloat(peso);
        estatura = parseFloat(estatura);

        if (peso <= 0 || estatura <= 0) {
            Swal.fire({
                title: "Datos Inválidos",
                text: "El peso y estatura deben ser mayores a 0",
                icon: "error"
            });
            return;
        }

        if (estatura > 3) {
            Swal.fire({
                title: "Estatura Inválida",
                text: "La estatura debe estar en metros (ej: 1.75)",
                icon: "error"
            });
            return;
        }

        // Enviar datos al servidor
        const datos = {
            nombre: nombre,
            peso: peso,
            estatura: estatura
        };

        // Mostrar loading
        Swal.fire({
            title: "Procesando...",
            text: "Calculando tu IMC",
            icon: "info",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Hacer petición al servidor
        fetch("../database/conexion.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(datos)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            if (!text) {
                throw new Error("Respuesta vacía del servidor");
            }
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("Respuesta del servidor:", text);
                throw new Error("Respuesta inválida del servidor: " + e.message);
            }
        })
        .then(result => {
            if (result.success) {
                const data = result.data;
                const imc = data.imc;
                const clasificacion = data.clasificacion;
                const mensajeEducativo = obtenerMensajeEducativo(clasificacion);

                // Mostrar resultado con alerta educativa
                Swal.fire({
                    title: mensajeEducativo.titulo,
                    html: `
                        <div style="text-align: left; font-size: 16px;">
                            <h3 style="color: ${mensajeEducativo.color}; margin-bottom: 10px;">
                                Tu IMC es: <strong>${imc}</strong>
                            </h3>
                            <p><strong>Clasificación:</strong> ${clasificacion}</p>
                            <hr>
                            <p style="font-weight: bold; margin: 15px 0;">${mensajeEducativo.mensaje}</p>
                            <ul style="list-style: none; padding: 0; text-align: left;">
                                ${mensajeEducativo.consejos.map(consejo => `<li style="margin: 8px 0;">${consejo}</li>`).join('')}
                            </ul>
                            <hr>
                            <p style="color: #666; font-size: 12px;">
                                <strong>Nota:</strong> Esta herramienta es educativa. 
                                Para un diagnóstico médico, consulta con un profesional de salud.
                            </p>
                        </div>
                    `,
                    icon: data.alerta ? "warning" : "success",
                    confirmButtonText: "Entendido",
                    confirmButtonColor: mensajeEducativo.color
                }).then(() => {
                    // Limpiar formulario
                    form.reset();
                    // Cargar registros actualizados
                    cargarRegistros();
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: result.message || "Error al guardar los datos",
                    icon: "error"
                });
            }
        })
        .catch(error => {
            console.error("Error completo:", error);
            console.error("Stack:", error.stack);
            
            let mensajeError = "No se pudo conectar con el servidor.";
            
            if (error.message.includes("HTTP error")) {
                mensajeError = "El servidor rechazó la petición. Verifica que XAMPP esté corriendo.";
            } else if (error.message.includes("Respuesta vacía")) {
                mensajeError = "El servidor no respondió. Verifica que PHP esté habilitado.";
            } else if (error.message.includes("Respuesta inválida")) {
                mensajeError = "La respuesta del servidor no es válida. Revisa la consola (F12).";
            } else if (error instanceof TypeError && error.message.includes("fetch")) {
                mensajeError = "No se puede acceder al servidor. Verifica la URL.";
            }
            
            Swal.fire({
                title: "Error de Conexión",
                text: mensajeError,
                html: `
                    <p>${mensajeError}</p>
                    <hr>
                    <p style="font-size: 12px; color: #666;">
                        <strong>Soluciones:</strong><br>
                        1. Abre la consola (F12) para ver el error<br>
                        2. Asegúrate que XAMPP está corriendo<br>
                        3. Verifica que config.php tiene credenciales correctas<br>
                        4. Ejecuta database/validar.php en navegador
                    </p>
                `,
                icon: "error"
            });
        });
    };

    // Cargar registros desde la base de datos
    const cargarRegistros = () => {
        fetch("database/obtener_estudiantes.php")
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data.length > 0) {
                    console.log("Registros cargados:", result.data);
                    // Aquí puedes mostrar los registros en una tabla si lo deseas
                }
            })
            .catch(error => console.error("Error al cargar registros:", error));
    };

    // Event listener para el botón calcular
    buttonCalculate.addEventListener("click", function() {
        const nombre = document.getElementById("nombre").value;
        const peso = document.getElementById("peso").value;
        const estatura = document.getElementById("estatura").value;

        calcularYGuardar(nombre, peso, estatura);
    });

    // Permitir enviar con Enter
    form.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            buttonCalculate.click();
        }
    });

    // Cargar registros al iniciar
    cargarRegistros();
});
