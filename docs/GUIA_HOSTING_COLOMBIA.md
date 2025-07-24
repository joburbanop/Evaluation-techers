# 🌐 Guía de Despliegue en Hosting Colombia - Sistema de Evaluación de Competencias Digitales Docentes

## 📋 Índice
1. [Preparación del Proyecto](#preparación-del-proyecto)
2. [Configuración del Hosting](#configuración-del-hosting)
3. [Subida de Archivos](#subida-de-archivos)
4. [Configuración de Base de Datos](#configuración-de-base-de-datos)
5. [Configuración de la Aplicación](#configuración-de-la-aplicación)
6. [Configuración de Dominio](#configuración-de-dominio)
7. [Verificación y Testing](#verificación-y-testing)

---

## 🛠️ Preparación del Proyecto

### Paso 1: Preparar el Proyecto para Producción

#### Optimizar para Producción
```bash
# En tu máquina local, navegar al proyecto
cd /Users/joburbanop/Desktop/EvaluacionProfesores

# Instalar dependencias optimizadas
composer install --optimize-autoloader --no-dev

# Compilar assets
npm run build

# Limpiar archivos innecesarios
rm -rf node_modules
rm -rf .git
rm -rf tests
rm -rf .github
rm -rf .vscode
rm -rf .idea
rm package-lock.json
rm yarn.lock
rm .env.example
rm .gitignore
rm .gitattributes
rm README.md
rm CHANGELOG.md
rm *.log
```

#### Crear Archivo de Configuración de Producción
```bash
# Crear archivo .env para producción
cp .env .env.production
```

Editar `.env.production`:
```env
APP_NAME="Sistema de Evaluación de Competencias Digitales Docentes"
APP_ENV=production
APP_KEY=base64:tu_clave_generada_localmente
APP_DEBUG=false
APP_URL=https://tu-dominio.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_nombre_base_datos
DB_USERNAME=tu_usuario_base_datos
DB_PASSWORD=tu_password_base_datos

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mail.tu-dominio.com
MAIL_PORT=587
MAIL_USERNAME=info@tu-dominio.com
MAIL_PASSWORD=tu_password_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tu-dominio.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

#### Crear Archivo .htaccess para Apache
```bash
# Crear archivo .htaccess en la raíz del proyecto
nano .htaccess
```

Contenido del `.htaccess`:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/ico "access plus 1 year"
    ExpiresByType image/icon "access plus 1 year"
    ExpiresByType text/plain "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
</IfModule>

# Deny access to sensitive files
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.lock">
    Order allow,deny
    Deny from all
</Files>
```

#### Crear Archivo de Instalación
```bash
# Crear script de instalación
nano install.sh
```

Contenido del `install.sh`:
```bash
#!/bin/bash

echo "🚀 Instalando Sistema de Evaluación de Competencias Digitales Docentes..."

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado"
    exit 1
fi

# Verificar Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado"
    exit 1
fi

echo "✅ PHP y Composer verificados"

# Instalar dependencias
echo "📦 Instalando dependencias..."
composer install --optimize-autoloader --no-dev

# Configurar permisos
echo "🔐 Configurando permisos..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Crear enlace simbólico de storage
echo "🔗 Creando enlace de storage..."
php artisan storage:link

# Cache de configuración
echo "⚡ Optimizando caché..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Instalación completada exitosamente!"
echo "🌐 Tu aplicación está lista en: https://tu-dominio.com"
```

```bash
# Hacer ejecutable
chmod +x install.sh
```

### Paso 2: Crear Archivo ZIP para Subida

```bash
# Crear archivo ZIP del proyecto
zip -r evaluacion-profesores.zip . -x "*.git*" "node_modules/*" "tests/*" ".env" "*.log"

# Verificar tamaño del archivo
ls -lh evaluacion-profesores.zip
```

---

## 🌐 Configuración del Hosting

### Paso 1: Acceder al Panel de Control

#### Hosting Colombia - cPanel
1. **Acceder al cPanel**:
   - URL: `https://tu-dominio.com/cpanel`
   - Usuario: Tu usuario de hosting
   - Contraseña: Tu contraseña de hosting

2. **Verificar Recursos Disponibles**:
   - **PHP**: Mínimo 8.1 (verificar en "PHP Selector")
   - **MySQL**: Mínimo 5.7 (verificar en "Bases de datos MySQL")
   - **Espacio**: Mínimo 500MB disponibles
   - **Ancho de banda**: Suficiente para el tráfico esperado

### Paso 2: Configurar PHP

#### En cPanel - PHP Selector
1. Ir a **"PHP Selector"** o **"Select PHP Version"**
2. Seleccionar **PHP 8.1** (o la versión más alta disponible)
3. Configurar extensiones PHP:
   - ✅ `bcmath`
   - ✅ `ctype`
   - ✅ `fileinfo`
   - ✅ `json`
   - ✅ `mbstring`
   - ✅ `openssl`
   - ✅ `pdo`
   - ✅ `tokenizer`
   - ✅ `xml`
   - ✅ `curl`
   - ✅ `gd`
   - ✅ `zip`

#### Configuraciones PHP Recomendadas
```ini
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
post_max_size = 100M
upload_max_filesize = 100M
max_file_uploads = 20
```

### Paso 3: Crear Base de Datos

#### En cPanel - Bases de datos MySQL
1. Ir a **"Bases de datos MySQL"**
2. **Crear nueva base de datos**:
   - Nombre: `tu_usuario_evaluacion`
   - Usuario: `tu_usuario_evaluser`
   - Contraseña: `ContraseñaSegura123!`

3. **Anotar información**:
   - Nombre de la base de datos: `tu_usuario_evaluacion`
   - Usuario: `tu_usuario_evaluser`
   - Contraseña: `ContraseñaSegura123!`
   - Host: `localhost`

#### Acceder a phpMyAdmin
1. Ir a **"phpMyAdmin"**
2. Seleccionar la base de datos creada
3. **Importar estructura** (si tienes un archivo SQL):
   - Ir a pestaña **"Importar"**
   - Seleccionar archivo SQL
   - Hacer clic en **"Continuar"**

---

## 📤 Subida de Archivos

### Paso 1: Usar File Manager

#### En cPanel - File Manager
1. Ir a **"File Manager"**
2. Navegar a **`public_html`** (directorio raíz del sitio web)
3. **Crear directorio** para la aplicación:
   - Nombre: `evaluacion`
   - Permisos: `755`

### Paso 2: Subir Archivos

#### Opción 1: Subida por ZIP
1. En **File Manager**, ir al directorio `public_html/evaluacion`
2. Hacer clic en **"Upload"**
3. Seleccionar el archivo `evaluacion-profesores.zip`
4. **Extraer el ZIP**:
   - Seleccionar el archivo ZIP
   - Hacer clic derecho → **"Extract"**
   - Extraer en el directorio actual

#### Opción 2: Subida por FTP
```bash
# Usar cliente FTP (FileZilla, WinSCP, etc.)
Host: tu-dominio.com
Usuario: tu_usuario_ftp
Contraseña: tu_password_ftp
Puerto: 21

# Subir archivos a: /public_html/evaluacion/
```

### Paso 3: Organizar Archivos

#### Estructura Correcta
```
public_html/
├── evaluacion/          # Directorio de la aplicación
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/          # Directorio público
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── install.sh
└── index.html           # Página principal (opcional)
```

#### Configurar Document Root (Opcional)
Si quieres que la aplicación esté en la raíz del dominio:

1. En **File Manager**, ir a `public_html`
2. **Mover todos los archivos** de `evaluacion/` a `public_html/`
3. **Eliminar** el directorio `evaluacion/` vacío

---

## 🗄️ Configuración de Base de Datos

### Paso 1: Ejecutar Migraciones

#### Acceder por SSH (si está disponible)
```bash
# Conectar por SSH
ssh tu_usuario@tu-dominio.com

# Navegar al directorio de la aplicación
cd public_html/evaluacion

# Ejecutar migraciones
php artisan migrate --force

# Ejecutar seeders
php artisan db:seed --force
```

#### Sin SSH - Usar Terminal Web
1. En cPanel, ir a **"Terminal"** o **"SSH Access"**
2. Ejecutar comandos:
```bash
cd public_html/evaluacion
php artisan migrate --force
php artisan db:seed --force
```

### Paso 2: Verificar Base de Datos
```bash
# Verificar tablas creadas
php artisan tinker
>>> \App\Models\User::count();
>>> \App\Models\Test::count();
>>> \App\Models\Institution::count();
>>> exit
```

---

## ⚙️ Configuración de la Aplicación

### Paso 1: Configurar Variables de Entorno

#### Editar .env
En **File Manager**, editar el archivo `.env`:

```env
APP_NAME="Sistema de Evaluación de Competencias Digitales Docentes"
APP_ENV=production
APP_KEY=base64:tu_clave_generada
APP_DEBUG=false
APP_URL=https://tu-dominio.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_usuario_evaluacion
DB_USERNAME=tu_usuario_evaluser
DB_PASSWORD=ContraseñaSegura123!

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mail.tu-dominio.com
MAIL_PORT=587
MAIL_USERNAME=info@tu-dominio.com
MAIL_PASSWORD=tu_password_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tu-dominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Paso 2: Ejecutar Instalación

#### Ejecutar Script de Instalación
```bash
# En Terminal Web o SSH
cd public_html/evaluacion
chmod +x install.sh
./install.sh
```

#### O Ejecutar Comandos Manualmente
```bash
# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Configurar permisos
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Crear enlace simbólico
php artisan storage:link

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Paso 3: Configurar Permisos

#### En File Manager
1. **Seleccionar directorios**:
   - `storage/`
   - `bootstrap/cache/`

2. **Cambiar permisos**:
   - Hacer clic derecho → **"Change Permissions"**
   - Establecer: `755` para directorios
   - Establecer: `644` para archivos

---

## 🌍 Configuración de Dominio

### Paso 1: Configurar Subdominio (Opcional)

#### En cPanel - Subdominios
1. Ir a **"Subdominios"**
2. **Crear subdominio**:
   - Subdominio: `evaluacion`
   - Dominio: `tu-dominio.com`
   - Directorio: `public_html/evaluacion/public`

### Paso 2: Configurar SSL

#### En cPanel - SSL/TLS
1. Ir a **"SSL/TLS"**
2. **Instalar certificado SSL**:
   - Seleccionar dominio
   - Instalar certificado gratuito (Let's Encrypt)
   - O usar certificado pagado

### Paso 3: Configurar Redirecciones

#### Crear .htaccess en Raíz (si es necesario)
Si la aplicación está en subdirectorio:

```apache
# En public_html/.htaccess
RewriteEngine On
RewriteRule ^$ evaluacion/public/ [L]
RewriteRule (.*) evaluacion/public/$1 [L]
```

---

## ✅ Verificación y Testing

### Paso 1: Verificar Instalación

#### Probar URLs
1. **URL Principal**: `https://tu-dominio.com`
2. **Panel Admin**: `https://tu-dominio.com/admin`
3. **Panel Coordinador**: `https://tu-dominio.com/coordinador`
4. **Panel Docente**: `https://tu-dominio.com/docente`

#### Verificar Funcionalidades
1. **Login de usuarios**:
   - Admin: `jonathanc.burbano221@umariana.edu.co` / `12345678`
   - Coordinador: `carlos.rodriguez@example.com` / `12345678`
   - Docente: `juan.perez@example.com` / `12345678`

2. **Generación de reportes**
3. **Creación de tests**
4. **Asignación de evaluaciones**

### Paso 2: Verificar Logs

#### En File Manager
1. Ir a `storage/logs/`
2. Verificar archivo `laravel.log`
3. Buscar errores o advertencias

### Paso 3: Verificar Base de Datos

#### En phpMyAdmin
1. Ir a **"phpMyAdmin"**
2. Seleccionar base de datos
3. Verificar tablas creadas:
   - `users`
   - `tests`
   - `test_assignments`
   - `reports`
   - etc.


```bash
# Configurar permisos correctos
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

#### Error de Storage
```bash
# Recrear enlace simbólico
rm public/storage
php artisan storage:link
```

### Comandos de Diagnóstico
```bash
# Información del sistema
php artisan about

# Estado de migraciones
php artisan migrate:status

# Verificar configuración
php artisan config:show

# Limpiar todo el caché
php artisan optimize:clear
```

---

## 📞 Soporte Hosting Colombia

### Contacto de Soporte
- **Email**: soporte@hostingcolombia.com
- **Teléfono**: +57 1 XXX XXXX
- **Chat en vivo**: Disponible en el panel de control
- **Horario**: 24/7

### Información Técnica
- **Panel de Control**: cPanel
- **Versión PHP**: 8.1+
- **Base de Datos**: MySQL 5.7+
- **SSL**: Let's Encrypt (gratuito)
- **Backup**: Automático diario

### Recursos del Hosting
- **Documentación**: https://hostingcolombia.com/ayuda
- **Tutoriales**: https://hostingcolombia.com/tutoriales
- **FAQ**: https://hostingcolombia.com/faq

---

## ✅ Checklist de Despliegue

### ✅ Preparación
- [ ] Proyecto optimizado para producción
- [ ] Archivo ZIP creado
- [ ] Script de instalación preparado
- [ ] Configuración .env lista

### ✅ Hosting
- [ ] Cuenta de hosting activa
- [ ] PHP 8.1+ configurado
- [ ] Base de datos creada
- [ ] SSL configurado

### ✅ Subida
- [ ] Archivos subidos al servidor
- [ ] Estructura de directorios correcta
- [ ] Permisos configurados
- [ ] .env configurado

### ✅ Base de Datos
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados
- [ ] Datos de prueba verificados
- [ ] Conexión probada

### ✅ Aplicación
- [ ] Dependencias instaladas
- [ ] Caché optimizado
- [ ] Storage configurado
- [ ] Permisos correctos

### ✅ Dominio
- [ ] URL principal funcionando
- [ ] Paneles accesibles
- [ ] SSL funcionando
- [ ] Redirecciones configuradas

### ✅ Testing
- [ ] Login de usuarios probado
- [ ] Generación de reportes probada
- [ ] Creación de tests probada
- [ ] Logs verificados

### ✅ Mantenimiento
- [ ] Backup configurado
- [ ] Cron jobs configurados
- [ ] Monitoreo activo
- [ ] Documentación actualizada

---

## 🎯 URLs Finales

### URLs de Acceso
- **Sitio Principal**: `https://tu-dominio.com`
- **Panel Administrador**: `https://tu-dominio.com/admin`
- **Panel Coordinador**: `https://tu-dominio.com/coordinador`
- **Panel Docente**: `https://tu-dominio.com/docente`

### Credenciales de Acceso
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

---

**🎉 ¡Tu aplicación está lista y funcionando en Hosting Colombia!**

*Guía de Despliegue en Hosting Colombia - Versión 1.0*
*Última actualización: {{ date('d/m/Y') }}* 