# 📚 Sistema de Evaluación de Competencias Digitales Docentes
## Documentación Técnica Completa

---

## 📋 Índice
1. [Descripción General](#descripción-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Tecnologías Utilizadas](#tecnologías-utilizadas)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [Configuración e Instalación](#configuración-e-instalación)
6. [Base de Datos](#base-de-datos)
7. [Autenticación y Autorización](#autenticación-y-autorización)
8. [Módulos del Sistema](#módulos-del-sistema)
9. [API y Endpoints](#api-y-endpoints)
10. [Reportes y Generación de PDFs](#reportes-y-generación-de-pdfs)
11. [Despliegue](#despliegue)
12. [Métricas y Rendimiento](#métricas-y-rendimiento)
13. [Actualizaciones y Versiones](#actualizaciones-y-versiones)
14. [Soporte y Contacto](#soporte-y-contacto)

---

## 🎯 Descripción General

El **Sistema de Evaluación de Competencias Digitales Docentes** es una aplicación web desarrollada en Laravel con Filament que permite evaluar las competencias digitales de los docentes universitarios mediante tests especializados. El sistema incluye gestión de usuarios, asignación de evaluaciones, generación de reportes y análisis de resultados.

### 🎯 Objetivos del Sistema
- Evaluar competencias digitales de docentes universitarios
- Generar reportes detallados por universidad, facultad, programa y profesor
- Gestionar asignaciones de evaluaciones
- Proporcionar análisis comparativos y estadísticos
- Facilitar la toma de decisiones basada en datos

### 👥 Roles del Sistema
- **Administrador**: Acceso completo al sistema
- **Coordinador**: Gestión de tests, reportes y visualización de asignaciones
- **Docente**: Realización de evaluaciones y visualización de resultados

### 📈 Áreas de Competencia Digital (8 áreas implementadas)
1. **Participación profesional**: Desarrollo profesional continuo y recursos digitales
2. **Tecnologías digitales**: Prácticas pedagógicas digitales y evaluación
3. **Promoción de competencia digital del alumnado**: Fomento en estudiantes
4. **Enseñanza y aprendizaje**: Aprendizaje activo y desarrollo de competencias
5. **Evaluación**: Evaluación del aprendizaje y retroalimentación efectiva
6. **Formación de estudiantes**: Empoderamiento del aprendizaje
7. **Educación Abierta**: Recursos educativos abiertos
8. **Información Socio-demográfica**: Datos demográficos relevantes

### 🎯 Niveles de Competencia (Marco Europeo)
- **A1 - Novato**: Muy poca experiencia (0-19 puntos)
- **A2 - Explorador**: Poco contacto con tecnología (20-33 puntos)
- **B1 - Integrador**: Experimenta con tecnología (34-49 puntos)
- **B2 - Experto**: Alto dominio tecnológico (50+ puntos)

---

## 🏗️ Arquitectura del Sistema

### Patrón de Arquitectura
- **MVC (Model-View-Controller)** con Laravel
- **Admin Panel** con Filament 3.x
- **Multi-panel** para diferentes roles de usuario
- **Repository Pattern** para servicios de negocio
- **Policy-based Authorization** con Spatie Permission

### Diagrama de Arquitectura
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Filament      │    │   Laravel       │
│   (Blade/JS)    │◄──►│   Admin Panel   │◄──►│   Backend       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │                       │
                                ▼                       ▼
                       ┌─────────────────┐    ┌─────────────────┐
                       │   Database      │    │   File Storage  │
                       │   (MySQL)       │    │   (PDFs)        │
                       └─────────────────┘    └─────────────────┘
```

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 12.x** - Framework PHP moderno con características avanzadas
- **PHP 8.2+** - Lenguaje de programación con tipado fuerte
- **MySQL 8.0+** - Base de datos relacional con vistas optimizadas
- **Redis** - Caché y sesiones de alto rendimiento

### Frontend
- **Filament 3.x** - Admin panel con múltiples paneles personalizados
- **Tailwind CSS** - Framework CSS utilitario para diseño responsive
- **Alpine.js** - JavaScript reactivo sin complejidad
- **Chart.js** - Gráficos y visualizaciones interactivas
- **Vite** - Bundler de assets moderno y rápido

### Librerías y Paquetes
- **Spatie Permission** - Gestión granular de roles y permisos
- **DomPDF** - Generación de PDFs profesionales
- **Laravel Sanctum** - Autenticación API segura
- **Laravel Queue** - Procesamiento asíncrono de tareas
- **Carbon** - Manipulación de fechas y tiempos
- **Faker** - Generación de datos de prueba
- **Laravel UI** - Componentes de interfaz de usuario
- **Guzzle HTTP** - Cliente HTTP para APIs

### Herramientas de Desarrollo
- **Composer** - Gestión de dependencias PHP
- **Artisan CLI** - Comandos personalizados para automatización
- **Git** - Control de versiones distribuido
- **Node.js** - Entorno de ejecución para JavaScript

---

## 📁 Estructura del Proyecto

```
EvaluacionProfesores/
├── app/
│   ├── Console/Commands/          # Comandos Artisan
│   ├── Filament/                  # Recursos de Filament
│   │   ├── Admin/                 # Panel de Administrador
│   │   ├── Coordinador/           # Panel de Coordinador
│   │   ├── Docente/               # Panel de Docente
│   │   └── Resources/             # Recursos de Filament
│   ├── Http/
│   │   ├── Controllers/           # Controladores
│   │   └── Middleware/            # Middlewares
│   ├── Jobs/                      # Jobs en cola
│   ├── Models/                    # Modelos Eloquent
│   ├── Notifications/             # Notificaciones
│   ├── Policies/                  # Políticas de autorización
│   ├── Providers/                 # Service Providers
│   └── Services/                  # Servicios de negocio
├── config/                        # Configuraciones
├── database/
│   ├── factories/                 # Factories para testing
│   ├── migrations/                # Migraciones
│   └── seeders/                   # Seeders
├── public/                        # Archivos públicos
├── resources/
│   ├── js/                        # JavaScript
│   ├── sass/                      # Estilos SCSS
│   └── views/                     # Vistas Blade
├── routes/                        # Rutas
├── storage/                       # Almacenamiento
└── tests/                         # Tests
```

---

## ⚙️ Configuración e Instalación

### Requisitos del Sistema
- **PHP**: 8.2 o superior
- **Composer**: 2.0 o superior
- **MySQL**: 8.0 o superior
- **Node.js**: 16+ (para compilación de assets)
- **Redis**: Opcional (para caché y colas)

### Instalación Paso a Paso

#### 1. Clonar el Repositorio
```bash
git clone https://github.com/joburbanop/Evaluation-techers.git
cd Evaluation-techers
```

#### 2. Instalar Dependencias
```bash
composer install
npm install
```

#### 3. Configurar Variables de Entorno
```bash
cp .env.example .env
php artisan key:generate
```

**⚠️ IMPORTANTE**: Después de generar la clave de aplicación, debes configurar las siguientes variables críticas:

##### Configuración Básica de la Aplicación
```env
APP_NAME="Sistema de Evaluación de Competencias Digitales"
APP_ENV=local
APP_KEY=base64:TU_CLAVE_GENERADA_AQUI
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
```

##### Configuración de Base de Datos
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evaluacion_profesores
DB_USERNAME=root
DB_PASSWORD=tu_password
```

##### Configuración de Correo Electrónico (CRÍTICA)
```env
# Configuración SMTP para Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_correo@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Configuración SMTP para Outlook/Hotmail
# MAIL_MAILER=smtp
# MAIL_HOST=smtp-mail.outlook.com
# MAIL_PORT=587
# MAIL_USERNAME=tu_correo@outlook.com
# MAIL_PASSWORD=tu_contraseña_de_aplicacion
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS=tu_correo@outlook.com
# MAIL_FROM_NAME="${APP_NAME}"
```

**🔐 Configuración de Contraseñas de Aplicación**

Para que el envío de correos funcione correctamente, necesitas configurar una **contraseña de aplicación**:

###### Para Gmail:
1. Ve a tu cuenta de Google
2. Activa la verificación en dos pasos
3. Ve a "Seguridad" → "Contraseñas de aplicación"
4. Genera una nueva contraseña para "Correo"
5. Usa esa contraseña en `MAIL_PASSWORD`

###### Para Outlook/Hotmail:
1. Ve a tu cuenta de Microsoft
2. Activa la autenticación de dos factores
3. Ve a "Seguridad" → "Contraseñas de aplicación"
4. Genera una nueva contraseña
5. Usa esa contraseña en `MAIL_PASSWORD`

##### Configuración de Caché y Sesiones
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

##### Configuración de Archivos
```env
FILESYSTEM_DISK=local
```

#### 4. Ejecutar Migraciones y Seeders
```bash
php artisan migrate
php artisan db:seed
```

#### 5. Compilar Assets
```bash
npm run build
```

#### 6. Configurar Almacenamiento
```bash
php artisan storage:link
```

#### 7. Verificar Configuración de Correo
```bash
# Probar el envío de correos
php artisan tinker
# En tinker ejecuta:
Mail::raw('Test de correo', function($message) { $message->to('tu_correo@ejemplo.com')->subject('Test'); });
```

#### 8. Configurar Colas (Opcional)
```bash
php artisan queue:work
```

#### 9. Verificar Instalación
```bash
# Verificar que todo esté funcionando
php artisan route:list
php artisan config:cache
php artisan view:cache
```

### Configuración de Filament

#### Paneles de Usuario
El sistema utiliza tres paneles separados con middleware personalizado:

```php
// app/Providers/Filament/AdminPanelProvider.php
->path('admin')
->authMiddleware([
    EnsureUserHasRole::class . ':Administrador',
])

// app/Providers/Filament/CoordinadorPanelProvider.php  
->path('coordinador')
->authMiddleware([
    EnsureUserHasRole::class . ':Coordinador',
])

// app/Providers/Filament/DocentePanelProvider.php
->path('docente')
->authMiddleware([
    EnsureUserHasRole::class . ':Docente',
])
```

### Comandos Artisan Disponibles

```bash
# Generar reporte de rendimiento del sistema
php artisan reports:performance --days=30

# Generar reporte de profesores completados
php artisan reports:profesores-completados

# Limpiar reportes expirados
php artisan reports:cleanup-expired

# Actualizar orden de preguntas
php artisan questions:update-order
```

---

## 🗄️ Base de Datos

### Diagrama ER
```
Users (1) ──── (N) TestAssignments (N) ──── (1) Tests
   │                                              │
   │                                              │
   │                                              │
   └─── (1) Institutions ──── (N) Facultades ────┘
                           │
                           └─── (N) Programas
```

### Principales Tablas

#### Users
- Información de usuarios del sistema
- Relaciones con instituciones, facultades y programas
- Roles y permisos mediante Spatie Permission

#### Tests
- Definición de evaluaciones
- Configuración de preguntas y opciones
- Niveles de competencia

#### TestAssignments
- Asignaciones de tests a usuarios
- Estado de progreso y completitud
- Respuestas y resultados

#### Reports
- Reportes generados
- Metadatos y archivos PDF
- Parámetros de generación



## 🔐 Autenticación y Autorización

### Sistema de Roles
El sistema utiliza **Spatie Permission** para gestión granular de permisos:

#### Roles Definidos
- **Administrador**: Acceso completo
- **Coordinador**: Gestión limitada
- **Docente**: Acceso restringido

#### Permisos Principales
```php
// Permisos de Administración
'Gestionar usuarios'
'Gestionar roles'
'Gestionar permisos'

// Permisos de Evaluaciones
'Gestionar tests'
'Realizar test'
'Ver resultados'

// Permisos de Reportes
'Generar reportes'
'Descargar reportes'
'Eliminar reportes'
```

### Middlewares de Autorización
```php
// app/Http/Middleware/CheckRole.php
class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403);
        }
        return $next($request);
    }
}
```

### Políticas de Autorización
```php
// app/Policies/ReportPolicy.php
class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Coordinador']);
    }
}
```

---

## 📊 Módulos del Sistema

### 1. Gestión de Usuarios
**Recurso**: `UserResource`
**Acceso**: Solo Administradores

#### Funcionalidades
- Crear, editar y eliminar usuarios
- Asignar roles y permisos
- Gestionar información personal y académica
- Activar/desactivar cuentas

#### Código Ejemplo
```php
class UserResource extends Resource
{
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('Administrador');
    }
}
```

### 2. Gestión de Tests
**Recurso**: `TestResource`
**Acceso**: Administradores y Coordinadores

#### Funcionalidades
- Crear y configurar evaluaciones
- Gestionar preguntas y opciones
- Definir niveles de competencia
- Configurar áreas de evaluación

### 3. Asignación de Evaluaciones
**Recurso**: `TestAssignmentResource`
**Acceso**: Administradores (completo), Coordinadores (solo ver)

#### Funcionalidades
- Asignar tests a docentes
- Seguimiento de progreso
- Visualización de resultados
- Gestión de estados

### 4. Realización de Tests
**Recurso**: `RealizarTestResource`
**Acceso**: Solo Docentes

#### Funcionalidades
- Interfaz de evaluación interactiva
- Guardado de progreso
- Validación de respuestas
- Visualización de resultados

### 5. Generación de Reportes
**Recurso**: `ReportResource`
**Acceso**: Administradores y Coordinadores

#### Tipos de Reportes (5 tipos implementados)
- **Por Universidad**: Análisis general institucional con estadísticas globales
- **Por Facultad**: Resultados específicos por facultad con comparativas
- **Por Programa**: Análisis detallado de programa académico
- **Por Profesor**: Evaluación individual con comparativas y progreso
- **Participación en Evaluación**: Reporte de profesores que completaron evaluaciones

#### Características de Reportes
- **Formato PDF Profesional**: Listo para impresión
- **Análisis Estadístico**: Medias, percentiles, comparativas
- **Gráficos y Tablas**: Visualización de datos
- **Metadatos Completos**: Información de generación
- **Vista Previa**: Modal antes de generar PDF
- **Expiración Automática**: Reportes expiran en 30 días

---


#### Reportes
```php
// Generar reporte
POST /api/reports/generate
{
    "tipo_reporte": "universidad",
    "entidad_id": 1,
    "date_from": "2024-01-01",
    "date_to": "2024-12-31"
}

// Descargar reporte
GET /api/reports/{id}/download
```

#### Tests
```php
// Obtener tests asignados
GET /api/tests/assigned

// Enviar respuestas
POST /api/tests/{id}/submit
{
    "answers": {
        "1": 5,
        "2": [3, 4],
        "3": 2
    }
}
```



## 📄 Reportes y Generación de PDFs

### Arquitectura de Reportes
```php
// app/Services/ReportService.php
class ReportService
{
    public function generateUniversidadReport(Institution $institution, array $parameters)
    {
        // Lógica de generación
    }
}
```

### Tipos de Reportes

#### 1. Reporte por Universidad
- Estadísticas generales
- Resultados por área de competencia
- Top 10 mejores evaluados
- Análisis comparativo

#### 2. Reporte por Facultad
- Información de la facultad
- Resultados por programa
- Estadísticas por área
- Comparación con la universidad

#### 3. Reporte por Programa
- Detalles del programa
- Análisis de rendimiento
- Top evaluados del programa
- Comparación con la facultad

#### 4. Reporte por Profesor
- Información personal
- Rendimiento por área
- Historial de evaluaciones
- Comparación con el programa

### Generación de PDFs
```php
// app/Http/Controllers/ReportPdfController.php
public function generate(Request $request)
{
    $pdf = PDF::loadView('reports.' . $tipo, $data);
    return $pdf->download('reporte-' . $tipo . '.pdf');
}
```

### Templates de Reportes
Los templates están ubicados en `resources/views/reports/`:
- `universidad.blade.php`
- `facultad.blade.php`
- `programa.blade.php`
- `profesor.blade.php`



## 🚀 Despliegue

### Requisitos de Producción
- **PHP**: 8.2+
- **MySQL**: 8.0+
- **Redis**: Recomendado para caché y colas
- **Supervisor**: Para colas asíncronas
- **Nginx/Apache**: Servidor web

### Configuración de Producción

#### 1. Variables de Entorno
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prod_evaluacion_profesores
DB_USERNAME=prod_user
DB_PASSWORD=secure_password

# Configuración de correo para producción
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_correo@gmail.com
MAIL_FROM_NAME="Sistema de Evaluación de Competencias Digitales"

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

#### 2. Optimizaciones
```bash
# Optimizar autoloader
composer install --optimize-autoloader --no-dev

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compilar assets
npm run build

### Optimizaciones de Rendimiento
- **Vistas de Base de Datos**: Optimizadas para consultas complejas
- **Sistema de Caché**: Redis para datos frecuentemente accedidos
- **Colas Asíncronas**: Procesamiento de reportes en background
- **Lazy Loading**: Carga eficiente de relaciones


## 📞 Soporte y Contacto

### Equipo de Desarrollo
- **Desarrollador Principal**: Jonathan Burbano
- **Email**: jonathanc.burbano221@umariana.edu.co
- **GitHub**: [joburbanop](https://github.com/joburbanop)

### Recursos Adicionales

- **Guía de Usuario**: `/docs/user-guide`


---

*Documentación generada el: {{ date('d/m/Y H:i:s') }}*
*Versión del sistema: 1.0.0* 