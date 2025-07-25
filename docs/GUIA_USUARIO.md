# 👥 Guía de Usuario - Sistema de Evaluación de Competencias Digitales Docentes

## 📋 Índice
1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel de Administrador](#panel-de-administrador)
4. [Panel de Coordinador](#panel-de-coordinador)
5. [Panel de Docente](#panel-de-docente)
6. [Realización de Evaluaciones](#realización-de-evaluaciones)
7. [Generación de Reportes](#generación-de-reportes)
8. [Troubleshooting](#troubleshooting)

---

## 🎯 Introducción

El **Sistema de Evaluación de Competencias Digitales Docentes** es una plataforma web diseñada para evaluar y medir las competencias digitales de los docentes universitarios. El sistema permite generar reportes detallados que ayudan a identificar áreas de mejora y fortalezas en el uso de tecnologías educativas.

### 🎯 Objetivos
- Evaluar competencias digitales de docentes
- Generar reportes analíticos
- Facilitar la toma de decisiones basada en datos
- Mejorar la formación docente en TIC

### 👥 Roles del Sistema

#### 🔧 Administrador
- **Responsabilidades**: Gestión completa del sistema
- **Acceso**: Todos los módulos y funcionalidades
- **Funciones**: Usuarios, tests, reportes, configuración

#### 👨‍💼 Coordinador
- **Responsabilidades**: Gestión académica y reportes
- **Acceso**: Tests, asignaciones (solo ver), reportes
- **Funciones**: Generar reportes, gestionar evaluaciones

#### 👨‍🏫 Docente
- **Responsabilidades**: Realizar evaluaciones
- **Acceso**: Solo módulo de evaluaciones
- **Funciones**: Completar tests, ver resultados

---

## 🔐 Acceso al Sistema

### URLs de Acceso
- **Administrador**: `https://tu-dominio.com/admin`
- **Coordinador**: `https://tu-dominio.com/coordinador`
- **Docente**: `https://tu-dominio.com/docente`

### Credenciales de Prueba
```
Administrador:
- Email: jonathanc.burbano221@umariana.edu.co
- Contraseña: 12345678

Coordinador:
- Email: carlos.rodriguez@example.com
- Contraseña: 12345678

Docente:
- Email: juan.perez@example.com
- Contraseña: 12345678
```

### Proceso de Login
1. Acceder a la URL correspondiente a tu rol
2. Ingresar email y contraseña
3. Hacer clic en "Iniciar Sesión"
4. Serás redirigido al dashboard correspondiente

---

## 🔧 Panel de Administrador

### Dashboard Principal
El dashboard del administrador muestra:
- **Estadísticas generales** del sistema
- **Gráficos de progreso** de evaluaciones
- **Alertas y notificaciones**
- **Acceso rápido** a funciones principales

### Gestión de Usuarios

#### Crear Nuevo Usuario
1. Ir a **Usuarios** en el menú lateral
2. Hacer clic en **"Crear Usuario"**
3. Completar formulario:
   - **Información Personal**: Nombre, email, documento
   - **Información Académica**: Institución, facultad, programa
   - **Rol**: Seleccionar rol (Administrador, Coordinador, Docente)
4. Hacer clic en **"Crear"**

#### Editar Usuario
1. En la lista de usuarios, hacer clic en **"Editar"**
2. Modificar información necesaria
3. Hacer clic en **"Guardar"**

#### Asignar Roles
1. Seleccionar usuario
2. Ir a pestaña **"Roles y Permisos"**
3. Marcar/desmarcar roles correspondientes
4. Hacer clic en **"Guardar"**

### Gestión de Tests

#### Crear Nueva Evaluación
1. Ir a **Tests** en el menú lateral
2. Hacer clic en **"Crear Test"**
3. Completar información básica:
   - **Nombre**: Nombre descriptivo del test
   - **Descripción**: Explicación del propósito
   - **Categoría**: Tipo de evaluación
4. Hacer clic en **"Crear"**

#### Configurar Preguntas
1. Seleccionar test creado
2. Ir a pestaña **"Preguntas"**
3. Hacer clic en **"Agregar Pregunta"**
4. Configurar:
   - **Texto de la pregunta**
   - **Área de competencia**
   - **Factor asociado**
   - **Opciones de respuesta** con puntuaciones
5. Hacer clic en **"Guardar"**

#### Configurar Niveles de Competencia
1. Ir a pestaña **"Niveles de Competencia"**
2. Configurar rangos de puntuación:
   - **A1-A2**: Nivel básico
   - **B1-B2**: Nivel intermedio
   - **C1-C2**: Nivel avanzado
3. Definir descripciones para cada nivel

### Gestión de Asignaciones

#### Asignar Test a Docente
1. Ir a **Asignación de Evaluaciones**
2. Hacer clic en **"Asignar Evaluación"**
3. Seleccionar:
   - **Docente**: Usuario que realizará el test
   - **Evaluación**: Test a asignar
   - **Instrucciones**: Comentarios adicionales
4. Hacer clic en **"Asignar"**

#### Ver Resultados
1. En la lista de asignaciones, buscar la asignación
2. Hacer clic en **"Ver Detalles"**
3. Revisar:
   - **Puntuación general**
   - **Resultados por área**
   - **Nivel de competencia**
   - **Comparación con otros**

---

## 👨‍💼 Panel de Coordinador

### Dashboard del Coordinador
Muestra información relevante para coordinadores:
- **Evaluaciones pendientes** en su facultad/programa
- **Estadísticas de rendimiento**
- **Reportes recientes**

### Gestión de Tests
Los coordinadores pueden:
- **Crear nuevos tests**
- **Editar tests existentes**
- **Configurar preguntas y opciones**
- **Definir niveles de competencia**

### Visualización de Asignaciones
- **Ver todas las asignaciones** de su área
- **Revisar progreso** de evaluaciones
- **Acceder a resultados** detallados
- **NO puede crear, editar o eliminar** asignaciones

### Generación de Reportes

#### Crear Reporte por Facultad
1. Ir a **Gestión de Reportes**
2. Hacer clic en **"Crear Reporte"**
3. Seleccionar **"Reporte por Facultad"**
4. Configurar parámetros:
   - **Facultad**: Seleccionar facultad
   - **Fecha desde/hasta**: Período de análisis
5. Hacer clic en **"Generar Reporte"**

#### Crear Reporte por Programa
1. Seleccionar **"Reporte por Programa"**
2. Configurar:
   - **Programa**: Seleccionar programa específico
   - **Período**: Fechas de análisis
3. Hacer clic en **"Generar Reporte"**

#### Crear Reporte por Universidad
1. Seleccionar **"Reporte por Universidad"**
2. Configurar período de análisis
3. Hacer clic en **"Generar Reporte"**

---

## 👨‍🏫 Panel de Docente

### Dashboard del Docente
Muestra información personal:
- **Tests asignados** pendientes
- **Evaluaciones completadas**
- **Resultados recientes**

### Realización de Evaluaciones

#### Acceder a Evaluación
1. En el dashboard, ver **"Tests Pendientes"**
2. Hacer clic en **"Comenzar/Continuar Test"**
3. Se abrirá el modal de evaluación

#### Navegación en el Test
- **Progreso**: Barra que muestra avance
- **Preguntas**: 5 preguntas por página
- **Navegación**: Botones "Regresar" y "Siguiente"

#### Tipos de Preguntas

##### Preguntas de Selección Única
- **Una sola opción** correcta
- **Puntuación directa** según selección
- **Ejemplo**: "¿Con qué frecuencia utiliza herramientas digitales en sus clases?"

##### Preguntas de Selección Múltiple
- **Múltiples opciones** pueden ser correctas
- **Puntuación acumulativa**
- **Ejemplo**: "¿Qué herramientas digitales utiliza? (seleccione todas las que apliquen)"

#### Guardar Progreso
- **Automático**: Al cambiar de página
- **Manual**: Botón "Guardar progreso"
- **Recuperación**: Al volver al test, continúa donde lo dejó

#### Completar Evaluación
1. **Responder todas las preguntas**
2. **Revisar respuestas** en la última página
3. **Hacer clic en "Enviar respuestas"**
4. **Confirmar envío**

### Visualización de Resultados

#### Resultados Generales
- **Puntuación total** y porcentaje
- **Nivel de competencia** alcanzado
- **Fecha de evaluación**
- **Comparación** con otros docentes

#### Resultados por Área
- **Puntuación por área** de competencia
- **Nivel específico** en cada área
- **Fortalezas y debilidades** identificadas

#### Información Comparativa
- **Percentil global**: Posición respecto a todos los evaluados
- **Percentil por facultad**: Comparación con colegas de la facultad
- **Percentil por programa**: Comparación con colegas del programa

---

## 📊 Generación de Reportes

### Tipos de Reportes Disponibles

#### 1. Reporte por Universidad
**Contenido**:
- Estadísticas generales de la institución
- Resultados por área de competencia
- Top 10 mejores evaluados
- Análisis comparativo

**Uso**: Análisis institucional general

#### 2. Reporte por Facultad
**Contenido**:
- Información específica de la facultad
- Resultados por programa
- Estadísticas por área
- Comparación con la universidad

**Uso**: Análisis por facultad

#### 3. Reporte por Programa
**Contenido**:
- Detalles del programa
- Análisis de rendimiento
- Top evaluados del programa
- Comparación con la facultad

**Uso**: Análisis específico de programa

#### 4. Reporte por Profesor
**Contenido**:
- Información personal del docente
- Rendimiento por área
- Historial de evaluaciones
- Comparación con el programa

**Uso**: Evaluación individual

### Proceso de Generación

#### Paso 1: Seleccionar Tipo
1. Ir a **"Gestión de Reportes"**
2. Hacer clic en **"Crear Reporte"**
3. Seleccionar tipo de reporte

#### Paso 2: Configurar Parámetros
- **Entidad**: Universidad, facultad, programa o profesor
- **Período**: Fechas de análisis
- **Filtros adicionales** (si aplica)

#### Paso 3: Vista Previa
- **Revisar datos** antes de generar
- **Verificar parámetros** configurados
- **Ajustar configuración** si es necesario

#### Paso 4: Generar PDF
- **Hacer clic en "Generar PDF"**
- **Esperar procesamiento**
- **Descargar archivo** automáticamente

### Características de los Reportes

#### Formato PDF
- **Profesional** y listo para impresión
- **Incluye gráficos** y tablas
- **Metadatos** completos
- **Marca institucional**

#### Contenido Estructurado
- **Resumen ejecutivo**
- **Análisis detallado**
- **Gráficos comparativos**
- **Recomendaciones**

#### Personalización
- **Logo institucional**
- **Información de contacto**
- **Fecha de generación**
- **Parámetros utilizados**

---

## 🐛 Troubleshooting

### Problemas Comunes

#### No puedo acceder al sistema
**Síntomas**: Error de login o acceso denegado
**Soluciones**:
1. Verificar credenciales correctas
2. Confirmar que la cuenta está activa
3. Contactar al administrador si persiste

#### Error al realizar evaluación
**Síntomas**: No se guardan respuestas o error al enviar
**Soluciones**:
1. Verificar conexión a internet
2. Recargar la página
3. Intentar guardar progreso manualmente
4. Contactar soporte si persiste

#### Reporte no se genera
**Síntomas**: Error al generar PDF o datos vacíos
**Soluciones**:
1. Verificar que hay datos en el período seleccionado
2. Ajustar parámetros de búsqueda
3. Intentar con período más amplio
4. Contactar al administrador

#### Problemas de navegación
**Síntomas**: Botones no funcionan o página no carga
**Soluciones**:
1. Limpiar caché del navegador
2. Usar navegador compatible (Chrome, Firefox, Safari)
3. Verificar JavaScript habilitado
4. Recargar página

### Contacto de Soporte

#### Información de Contacto
- **Email**: soporte@evaluacionprofesores.com
- **Teléfono**: +57 XXX XXX XXXX
- **Horario**: Lunes a Viernes 8:00 AM - 6:00 PM

#### Información a Proporcionar
Al contactar soporte, proporcionar:
- **Rol de usuario** (Administrador, Coordinador, Docente)
- **Descripción detallada** del problema
- **Pasos para reproducir** el error
- **Captura de pantalla** (si aplica)
- **Información del navegador** y sistema operativo

### FAQ (Preguntas Frecuentes)

#### ¿Puedo pausar una evaluación y continuar después?
**Respuesta**: Sí, el sistema guarda automáticamente tu progreso. Puedes cerrar y volver más tarde.

#### ¿Qué pasa si pierdo conexión durante la evaluación?
**Respuesta**: Tus respuestas se guardan automáticamente. Al reconectar, continúa donde lo dejaste.

#### ¿Puedo cambiar mis respuestas antes de enviar?
**Respuesta**: Sí, puedes navegar entre páginas y modificar respuestas hasta enviar.

#### ¿Cómo interpreto mis resultados?
**Respuesta**: Los resultados incluyen tu nivel de competencia y comparación con otros docentes.

#### ¿Puedo realizar la evaluación múltiples veces?
**Respuesta**: Solo si te es asignada nuevamente por un administrador o coordinador.

---

## 📞 Recursos Adicionales

### Documentación
- **Manual técnico**: `/docs/technical`
- **API documentation**: `/api/docs`
- **Videos tutoriales**: `/docs/videos`

### Capacitación
- **Sesiones de entrenamiento** disponibles
- **Material de capacitación** descargable
- **Soporte en línea** durante implementación

### Actualizaciones
- **Notificaciones** de nuevas funcionalidades
- **Changelog** disponible en el sistema
- **Comunicaciones** por email

---

*Guía de Usuario - Versión 1.0*
*Última actualización* 
