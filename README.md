# 🎓 Sistema de Evaluación de Competencias Digitales Docentes

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.x-blue.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

> Plataforma web desarrollada en Laravel con Filament para evaluar competencias digitales de docentes universitarios mediante tests especializados y generación de reportes analíticos en PDF.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Documentación](#-documentación)
- [Contribución](#-contribución)
- [Licencia](#-licencia)

## ✨ Características

### 🎯 Funcionalidades Principales
- **Evaluación de Competencias Digitales**: Tests especializados para docentes universitarios
- **Sistema Multi-Rol**: Administrador, Coordinador y Docente con paneles separados
- **Generación de Reportes PDF**: 5 tipos de reportes con análisis detallados
- **Dashboard Interactivo**: Estadísticas y métricas en tiempo real
- **Sistema de Asignaciones**: Gestión de evaluaciones por docente
- **Niveles de Competencia**: Evaluación basada en marco europeo (A1-C2)
- **Áreas de Evaluación**: 8 áreas de competencia digital especializadas
- **Sistema de Caché**: Optimización de rendimiento con Redis
- **Comandos Artisan**: Automatización de tareas administrativas

### 📊 Tipos de Reportes Implementados
- **Por Universidad**: Análisis institucional general con estadísticas globales
- **Por Facultad**: Resultados específicos por facultad con comparativas
- **Por Programa**: Análisis detallado de programa académico
- **Por Profesor**: Evaluación individual con comparativas y progreso
- **Participación en Evaluación**: Reporte de profesores que completaron evaluaciones

### 🔐 Sistema de Seguridad
- **Autenticación Multi-Panel**: Paneles separados por rol con middleware personalizado
- **Autorización Granular**: Permisos específicos con Spatie Permission
- **Políticas de Acceso**: Control a nivel de modelo y recurso
- **Validación Robusta**: Frontend y backend con validación completa
- **Verificación de Email**: Sistema de verificación de correo electrónico

### 🎨 Interfaz de Usuario
- **Filament 3.x**: Admin panel con múltiples paneles personalizados
- **Tailwind CSS**: Framework CSS utilitario
- **Alpine.js**: JavaScript reactivo para interactividad
- **Modales Dinámicos**: Previsualización de reportes antes de generar
- **Gráficos Interactivos**: Visualización de datos con Chart.js

### 📈 Áreas de Competencia Digital
1. **Participación profesional**: Desarrollo profesional continuo
2. **Tecnologías digitales**: Prácticas pedagógicas digitales
3. **Promoción de competencia digital del alumnado**: Fomento en estudiantes
4. **Enseñanza y aprendizaje**: Aprendizaje activo
5. **Evaluación**: Evaluación del aprendizaje y retroalimentación
6. **Formación de estudiantes**: Empoderamiento del aprendizaje
7. **Educación Abierta**: Recursos educativos abiertos
8. **Información Socio-demográfica**: Datos demográficos relevantes

## 🛠️ Tecnologías

### Backend
- **Laravel 12.x** - Framework PHP moderno con características avanzadas
- **PHP 8.2+** - Lenguaje de programación con tipado fuerte
- **MySQL 8.0+** - Base de datos relacional con vistas optimizadas
- **Spatie Permission** - Gestión granular de roles y permisos
- **DomPDF** - Generación de PDFs profesionales
- **Laravel Sanctum** - Autenticación API segura
- **Laravel Queue** - Procesamiento asíncrono de tareas

### Frontend
- **Filament 3.x** - Admin panel con múltiples paneles personalizados
- **Tailwind CSS** - Framework CSS utilitario para diseño responsive
- **Alpine.js** - JavaScript reactivo sin complejidad
- **Chart.js** - Gráficos y visualizaciones interactivas
- **Vite** - Bundler de assets moderno y rápido

### Herramientas de Desarrollo
- **Composer** - Gestión de dependencias PHP
- **Artisan CLI** - Comandos personalizados para automatización
- **Redis** - Caché y sesiones de alto rendimiento
- **Git** - Control de versiones distribuido

### Librerías Especializadas
- **Carbon** - Manipulación de fechas y tiempos
- **Faker** - Generación de datos de prueba
- **Laravel UI** - Componentes de interfaz de usuario
- **Guzzle HTTP** - Cliente HTTP para APIs

## 🚀 Instalación

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

#### 4. Configurar Base de Datos
Editar `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evaluacion_profesores
DB_USERNAME=root
DB_PASSWORD=tu_password
```

#### 5. Ejecutar Migraciones y Seeders
```bash
php artisan migrate
php artisan db:seed
```

#### 6. Compilar Assets
```bash
npm run build
```

#### 7. Configurar Almacenamiento
```bash
php artisan storage:link
```

#### 8. Verificar Configuración de Correo
```bash
# Probar el envío de correos
php artisan tinker
# En tinker ejecuta:
Mail::raw('Test de correo', function($message) { $message->to('tu_correo@ejemplo.com')->subject('Test'); });
```

#### 9. Configurar Colas (Opcional)
```bash
php artisan queue:work
```

#### 10. Verificar Instalación
```bash
# Verificar que todo esté funcionando
php artisan route:list
php artisan config:cache
php artisan view:cache
```

## ⚙️ Configuración

### Configuración de Paneles Filament

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

### Configuración de Permisos

Ejecutar el seeder de permisos finales:

```bash
php artisan db:seed --class=FinalPermissionsSeeder
```

### Configuración de Reportes

Los reportes se generan en formato PDF usando DomPDF:

```php
// config/pdf.php
'default' => [
    'format' => 'A4',
    'orientation' => 'portrait',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
],
```


## 📖 Uso

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

### Flujo de Trabajo Típico

#### 1. Configuración Inicial (Administrador)
1. Crear usuarios y asignar roles
2. Configurar instituciones, facultades y programas
3. Crear tests y configurar preguntas
4. Asignar evaluaciones a docentes
5. Configurar niveles de competencia (A1-C2)

#### 2. Gestión Académica (Coordinador)
1. Revisar asignaciones de evaluaciones
2. Generar reportes por facultad/programa
3. Analizar resultados y tendencias
4. Monitorear participación de docentes

#### 3. Evaluación (Docente)
1. Acceder a evaluaciones asignadas
2. Completar tests de competencias digitales
3. Revisar resultados y comparativas
4. Visualizar progreso por áreas



## 📚 Documentación

### Documentación Técnica
- **[📚 Documentación Completa](docs/README.md)**: Índice de toda la documentación
- **[Documentación Técnica Completa](docs/DOCUMENTACION_TECNICA.md)**: Arquitectura, desarrollo y mantenimiento
- **[Guía de Usuario](docs/GUIA_USUARIO.md)**: Manual para usuarios finales
- **[Manual de Módulos](docs/MANUAL_MODULOS.md)**: Documentación detallada de funcionalidades
- **[Manual de Despliegue](docs/MANUAL_DESPLEGUE.md)**: Guía de instalación en producción

### Recursos Adicionales
- **[Resumen Ejecutivo](docs/RESUMEN_EJECUTIVO.md)**: Resumen del proyecto
- **[Documentación de Reportes](docs/REPORTES_README.md)**: Especificaciones de reportes



## 🚀 Despliegue

### Producción
```bash
# Optimizar para producción
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Configurar supervisor para colas
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Variables de Entorno de Producción
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



### Optimizaciones de Rendimiento
- **Vistas de Base de Datos**: Optimizadas para consultas complejas
- **Sistema de Caché**: Redis para datos frecuentemente accedidos
- **Colas Asíncronas**: Procesamiento de reportes en background
- **Lazy Loading**: Carga eficiente de relaciones



## 📞 Soporte

### Contacto
- **Desarrollador**: Jonathan Burbano
- **Email**: jonathanc.burbano221@umariana.edu.co
- **Documentación**: Ver archivos de documentación en el repositorio
- **Issues**: [GitHub Issues](https://github.com/joburbanop/Evaluation-techers/issues)

### Recursos de Ayuda
- **Documentación Técnica**: `DOCUMENTACION_TECNICA.md`
- **Guía de Usuario**: `GUIA_USUARIO.md`
- **Manual de Despliegue**: `MANUAL_DESPLEGUE.md`

## 🙏 Agradecimientos

- **Laravel Team**: Por el excelente framework
- **Filament Team**: Por el admin panel
- **Spatie**: Por el paquete de permisos
- **Comunidad PHP**: Por el apoyo continuo



*Versión 1.0.0*
