console.log('=== SCRIPT DE REPORTES CARGADO ===');
console.log('Fecha y hora:', new Date().toLocaleString());
console.log('URL actual:', window.location.href);

// Función de prueba simple
function testGeneratePDF() {
    console.log('=== FUNCIÓN TEST EJECUTADA ===');
    alert('¡La función funciona!');
}

// Función para generar PDF desde el modal
function generatePDFFromModal() {
    console.log('Función generatePDFFromModal ejecutada');
    try {
        // Mostrar indicador de carga
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = `
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Generando PDF...
        `;
        button.disabled = true;
        
        // Obtener token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('Token CSRF obtenido:', token ? 'SÍ' : 'NO');
        if (!token) {
            throw new Error('Token CSRF no encontrado');
        }
        
        // Obtener los datos del formulario desde el modal
        const formData = new FormData();
        
        // Obtener el tipo de reporte del modal
        const tipoReporteElement = document.querySelector('[data-tipo-reporte]');
        const tipoReporte = tipoReporteElement ? tipoReporteElement.getAttribute('data-tipo-reporte') : '';
        formData.append('tipo_reporte', tipoReporte);
        
        // Obtener el ID de la entidad del modal
        const entidadIdElement = document.querySelector('[data-entidad-id]');
        const entidadId = entidadIdElement ? entidadIdElement.getAttribute('data-entidad-id') : '';
        formData.append('entidad_id', entidadId);
        
        // Obtener fechas del modal
        const fechaDesdeElement = document.querySelector('[data-fecha-desde]');
        const fechaHastaElement = document.querySelector('[data-fecha-hasta]');
        
        if (fechaDesdeElement) {
            formData.append('date_from', fechaDesdeElement.getAttribute('data-fecha-desde'));
        }
        if (fechaHastaElement) {
            formData.append('date_to', fechaHastaElement.getAttribute('data-fecha-hasta'));
        }
        
        // Obtener filtro para profesores_completados
        if (tipoReporte === 'profesores_completados') {
            const filtroElement = document.querySelector('[data-filtro-profesores]');
            if (filtroElement) {
                formData.append('filtro', filtroElement.getAttribute('data-filtro-profesores'));
            }
        }
        
        // Log de los datos que se van a enviar
        console.log('Datos del formulario:');
        console.log('- Tipo reporte:', tipoReporte);
        console.log('- Entidad ID:', entidadId);
        if (fechaDesdeElement) {
            console.log('- Fecha desde:', fechaDesdeElement.getAttribute('data-fecha-desde'));
        }
        if (fechaHastaElement) {
            console.log('- Fecha hasta:', fechaHastaElement.getAttribute('data-fecha-hasta'));
        }
        
        // Enviar solicitud para generar PDF
        // Detectar la ruta correcta según el panel actual
        let url;
        if (window.location.pathname.includes('/coordinador/')) {
            url = '/coordinador/reports/generate-pdf';
        } else {
            url = '/admin/reports/generate-pdf';
        }
        console.log('URL de la solicitud:', url);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            console.log('Respuesta recibida:', response.status);
            if (response.ok) {
                return response.json();
            }
            // Si la respuesta no es exitosa, intentar leer el mensaje de error
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Error al generar el reporte');
            }).catch(() => {
                throw new Error('Error al generar el reporte');
            });
        })
        .then(data => {
            console.log('Datos de respuesta:', data);
            
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
            
            if (data.success) {
                // Mostrar mensaje de éxito
                if (data.message && data.message.includes('proceso')) {
                    // Si es un reporte que se está procesando en background
                    alert('Reporte en proceso de generación. Se completará en unos momentos. Serás redirigido a la lista de reportes.');
                } else {
                    // Si es un reporte que se generó inmediatamente
                    alert('Reporte generado exitosamente. Serás redirigido a la lista de reportes.');
                }
                
                // Redirigir a la lista de reportes después de un breve delay
                setTimeout(() => {
                    if (window.location.pathname.includes('/coordinador/')) {
                        window.location.href = '/coordinador/reports';
                    } else {
                        window.location.href = '/admin/reports';
                    }
                }, 1500);
            } else {
                throw new Error(data.message || 'Error desconocido');
            }
        })
        .catch(error => {
            console.error('Error al generar PDF:', error);
            
            let errorMessage = 'Error al generar el reporte. ';
            if (error.message.includes('403')) {
                errorMessage += 'No tienes permisos para generar reportes.';
            } else if (error.message.includes('500')) {
                errorMessage += 'Error interno del servidor.';
            } else {
                errorMessage += error.message || 'Por favor, inténtelo de nuevo.';
            }
            
            alert(errorMessage);
            
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
        });
    } catch (error) {
        console.error('Error en generatePDFFromModal:', error);
        alert('Error inesperado: ' + error.message);
    }
}

// Función para generar PDF - Definida en scope global (mantener compatibilidad)
window.generatePDF = function() {
    generatePDFFromModal();
}



// También hacer la función disponible globalmente
window.generatePDFFromModal = generatePDFFromModal;

// Estilos para la animación de carga
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;

document.head.appendChild(style);

// Verificar que las funciones estén disponibles
console.log('Función generatePDFFromModal disponible:', typeof generatePDFFromModal === 'function');
console.log('Función generatePDF disponible:', typeof window.generatePDF === 'function');
console.log('Script de generación de PDF cargado');

console.log('Script completado - funciones disponibles'); 