# Sistema de Reportes Profesionales - Evaluación de Profesores

## 📋 Descripción

Este sistema de reportes permite generar análisis profesionales y detallados de las evaluaciones de competencias digitales por facultad y programa. Los reportes incluyen estadísticas completas, análisis de rendimiento y distribución de niveles de competencia.

## 🚀 Características Principales

### ✅ Funcionalidades Implementadas

1. **Reportes por Facultad**
   - Estadísticas generales de la facultad
   - Análisis por área de competencia
   - Resultados desglosados por programa
   - Distribución de niveles de competencia

2. **Reportes por Programa**
   - Estadísticas específicas del programa
   - Análisis por área de competencia
   - Top 10 mejores evaluados
   - Análisis de rendimiento con métricas estadísticas

3. **Gestión de Reportes**
   - Generación asíncrona de reportes
   - Almacenamiento seguro en el servidor
   - Sistema de expiración automática (30 días)
   - Control de acceso por roles

4. **Interfaz de Usuario**
   - Panel administrativo integrado en Filament
   - Filtros por fecha y tipo de reporte
   - Descarga directa de PDFs
   - Widgets de estadísticas en dashboard

## 📦 Instalación

### 1. Ejecutar Migraciones

```bash
php artisan migrate
```

### 2. Ejecutar Seeders

```bash
php artisan db:seed --class=ReportPermissionsSeeder
```

### 3. Configurar Almacenamiento

```bash
php artisan storage:link
```

### 4. Configurar Colas (Opcional)

Para generación asíncrona de reportes:

```bash
# Configurar driver de colas en .env
QUEUE_CONNECTION=database

# Crear tabla de jobs
php artisan queue:table
php artisan migrate

# Iniciar worker de colas
php artisan queue:work
```

### 5. Configurar Tarea Programada (Opcional)

Agregar al crontab para limpieza automática:

```bash
# Editar crontab
crontab -e

# Agregar línea
0 2 * * * cd /path/to/project && php artisan reports:cleanup
```

## 🎯 Uso del Sistema

### Generar Reportes desde Filament

1. **Acceder al Panel Administrativo**
   - Ir a `/admin`
   - Navegar a "Reportes" en el menú lateral

2. **Crear Nuevo Reporte**
   - Hacer clic en "Generar Reporte"
   - Seleccionar tipo: Facultad o Programa
   - Elegir la entidad específica
   - Opcional: Aplicar filtros de fecha
   - Hacer clic en "Crear"

3. **Descargar Reporte**
   - Esperar a que el estado cambie a "Completado"
   - Hacer clic en el botón "Descargar"
   - El PDF se descargará automáticamente

### Generar Reportes via API

```bash
# Reporte por Facultad
POST /reports/facultad
{
    "facultad_id": 1,
    "date_from": "2024-01-01",
    "date_to": "2024-12-31"
}

# Reporte por Programa
POST /reports/programa
{
    "programa_id": 1,
    "date_from": "2024-01-01",
    "date_to": "2024-12-31"
}
```

## 📊 Estructura de Reportes

### Reporte de Facultad

**Secciones Incluidas:**
- Resumen general (total evaluaciones, usuarios, programas)
- Resultados por área de competencia
- Estadísticas por programa
- Distribución de niveles de competencia

**Métricas Calculadas:**
- Promedio de puntajes por área
- Máximo y mínimo de puntajes
- Distribución de niveles (A1, A2, B1, B2, C1, C2)
- Total de evaluaciones por programa

### Reporte de Programa

**Secciones Incluidas:**
- Resumen general del programa
- Resultados por área de competencia
- Top 10 mejores evaluados
- Análisis de rendimiento estadístico

**Métricas Calculadas:**
- Promedio general del programa
- Desviación estándar
- Ranking de docentes
- Distribución de niveles por área

## 🔧 Configuración Avanzada

### Personalizar Expiración de Reportes

```php
// En app/Services/ReportService.php
'expires_at' => now()->addDays(30), // Cambiar número de días
```

### Configurar Almacenamiento

```php
// En config/filesystems.php
'reports' => [
    'driver' => 'local',
    'root' => storage_path('app/reports'),
],
```

### Personalizar Templates PDF

Los templates se encuentran en:
- `resources/views/reports/facultad.blade.php`
- `resources/views/reports/programa.blade.php`

## 🛡️ Seguridad y Permisos

### Roles y Permisos

- **Administrador**: Acceso completo a todos los reportes
- **Coordinador**: Puede generar y gestionar reportes
- **Docente**: Solo puede ver y descargar reportes

### Permisos Específicos

- `Ver reportes`: Listar reportes disponibles
- `Generar reportes`: Crear nuevos reportes
- `Descargar reportes`: Descargar archivos PDF
- `Eliminar reportes`: Eliminar reportes propios
- `Gestionar reportes`: Acceso completo (solo admin)

## 📈 Monitoreo y Mantenimiento

### Comandos Disponibles

```bash
# Limpiar reportes expirados
php artisan reports:cleanup --days=30

# Ver estadísticas de reportes
php artisan tinker
>>> App\Models\Report::count();
```

### Logs

Los logs de generación se encuentran en:
```
storage/logs/laravel.log
```

### Métricas del Dashboard

El widget de reportes muestra:
- Total de reportes generados
- Porcentaje de reportes completados
- Reportes generados hoy
- Reportes generados esta semana

## 🔄 Flujo de Trabajo

1. **Solicitud de Reporte**
   - Usuario selecciona tipo y entidad
   - Sistema crea registro en estado "pending"

2. **Generación**
   - Job en cola procesa la solicitud
   - Estado cambia a "generating"
   - Se calculan estadísticas y se genera PDF

3. **Completado**
   - Estado cambia a "completed"
   - Archivo se almacena en storage
   - Usuario puede descargar

4. **Limpieza**
   - Reportes expiran automáticamente
   - Comando de limpieza elimina archivos y registros

## 🐛 Solución de Problemas

### Reporte No Se Genera

1. Verificar logs: `tail -f storage/logs/laravel.log`
2. Comprobar permisos de storage
3. Verificar configuración de colas

### Error de Descarga

1. Verificar que el archivo existe en storage
2. Comprobar permisos de usuario
3. Verificar estado del reporte

### Rendimiento Lento

1. Configurar colas para generación asíncrona
2. Optimizar consultas en ReportService
3. Considerar índices en base de datos

## 📝 Notas de Desarrollo

### Estructura de Archivos

```
app/
├── Models/Report.php
├── Services/ReportService.php
├── Http/Controllers/ReportController.php
├── Jobs/GenerateReportJob.php
├── Console/Commands/CleanupExpiredReports.php
└── Filament/Resources/ReportResource.php

resources/views/reports/
├── facultad.blade.php
└── programa.blade.php

database/migrations/
└── 2025_01_15_000000_create_reports_table.php
```

### Dependencias

- `barryvdh/laravel-dompdf`: Generación de PDFs
- `spatie/laravel-permission`: Control de permisos
- `filament/filament`: Panel administrativo

## 🎉 Conclusión

El sistema de reportes proporciona una solución completa y profesional para el análisis de evaluaciones de competencias digitales. Con su interfaz intuitiva, generación asíncrona y gestión robusta de archivos, permite a los administradores y coordinadores obtener insights valiosos sobre el rendimiento académico de sus instituciones. 