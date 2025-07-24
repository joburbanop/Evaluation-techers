# 🛠️ Manual de Implementación - Sistema de Evaluación de Competencias Digitales Docentes

## 📋 Índice
1. [Requisitos del Sistema](#requisitos-del-sistema)
2. [Instalación Inicial](#instalación-inicial)
3. [Configuración de Base de Datos](#configuración-de-base-de-datos)
4. [Configuración de Filament](#configuración-de-filament)
5. [Configuración de Permisos](#configuración-de-permisos)
6. [Configuración de Reportes](#configuración-de-reportes)
7. [Configuración de Producción](#configuración-de-producción)

---

## 🖥️ Requisitos del Sistema

### Requisitos Mínimos
- **Sistema Operativo**: Ubuntu 20.04+, CentOS 8+, macOS 10.15+, Windows 10+
- **PHP**: 8.2 o superior
- **Composer**: 2.0 o superior
- **MySQL**: 8.0 o superior
- **Node.js**: 16+ (para compilación de assets)
- **Git**: Para control de versiones

### Requisitos Recomendados
- **RAM**: 4GB mínimo, 8GB recomendado
- **Almacenamiento**: 10GB mínimo
- **CPU**: 2 cores mínimo, 4 cores recomendado
- **Redis**: Para caché y colas (opcional pero recomendado)

### Extensiones PHP Requeridas
```bash
# Verificar extensiones PHP
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml|curl|gd|zip)"
```

Extensiones necesarias:
- `bcmath`
- `ctype`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `pdo`
- `tokenizer`
- `xml`
- `curl`
- `gd`
- `zip`

---

## 🚀 Instalación Inicial

### Paso 1: Preparar el Entorno

#### En Ubuntu/Debian:
```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependencias
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-gd php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-json php8.2-tokenizer php8.2-fileinfo

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Instalar MySQL
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

#### En macOS:
```bash
# Instalar Homebrew si no está instalado
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Instalar PHP
brew install php@8.2

# Instalar Composer
brew install composer

# Instalar Node.js
brew install node

# Instalar MySQL
brew install mysql
brew services start mysql
```

#### En Windows:
1. Instalar **XAMPP** o **WAMP** con PHP 8.2+
2. Instalar **Composer** desde https://getcomposer.org/
3. Instalar **Node.js** desde https://nodejs.org/
4. Instalar **Git** desde https://git-scm.com/

### Paso 2: Clonar el Proyecto
```bash
# Clonar repositorio
git clone https://github.com/tu-usuario/evaluacion-profesores.git
cd evaluacion-profesores

# Verificar estructura del proyecto
ls -la
```

### Paso 3: Instalar Dependencias
```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install

# Verificar instalación
php artisan --version
composer --version
node --version
npm --version
```

---

## 🗄️ Configuración de Base de Datos

### Paso 1: Crear Base de Datos
```sql
-- Conectar a MySQL
mysql -u root -p

-- Crear base de datos
CREATE DATABASE evaluacion_profesores CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario específico (recomendado)
CREATE USER 'eval_user'@'localhost' IDENTIFIED BY 'tu_password_seguro';
GRANT ALL PRIVILEGES ON evaluacion_profesores.* TO 'eval_user'@'localhost';
FLUSH PRIVILEGES;

-- Verificar creación
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User = 'eval_user';
```

### Paso 2: Configurar Variables de Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

Editar `.env`:
```env
APP_NAME="Sistema de Evaluación de Competencias Digitales Docentes"
APP_ENV=local
APP_KEY=base64:tu_clave_generada
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evaluacion_profesores
DB_USERNAME=eval_user
DB_PASSWORD=tu_password_seguro

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
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Paso 3: Ejecutar Migraciones
```bash
# Ejecutar migraciones
php artisan migrate

# Verificar tablas creadas
php artisan migrate:status

# Ejecutar seeders
php artisan db:seed

# Verificar datos insertados
php artisan tinker
>>> \App\Models\User::count();
>>> \App\Models\Test::count();
>>> \App\Models\Institution::count();
```

---

## ⚙️ Configuración de Filament

### Paso 1: Instalar Filament
```bash
# Verificar que Filament esté instalado
composer show filament/filament

# Si no está instalado
composer require filament/filament
```

### Paso 2: Configurar Paneles
```bash
# Publicar configuración de Filament
php artisan vendor:publish --tag=filament-config

# Crear paneles
php artisan make:filament-panel admin
php artisan make:filament-panel coordinador
php artisan make:filament-panel docente
```

### Paso 3: Configurar Providers
Verificar que los providers estén registrados en `config/app.php`:
```php
'providers' => [
    // ...
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\CoordinadorPanelProvider::class,
    App\Providers\Filament\DocentePanelProvider::class,
],
```

### Paso 4: Configurar Recursos
```bash
# Verificar recursos existentes
php artisan filament:list-resources

# Si necesitas crear nuevos recursos
php artisan make:filament-resource User
php artisan make:filament-resource Test
php artisan make:filament-resource Report
```

---

## 🔐 Configuración de Permisos

### Paso 1: Instalar Spatie Permission
```bash
# Verificar instalación
composer show spatie/laravel-permission

# Si no está instalado
composer require spatie/laravel-permission
```

### Paso 2: Publicar Migraciones
```bash
# Publicar migraciones de permisos
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Ejecutar migraciones de permisos
php artisan migrate
```

### Paso 3: Configurar Modelo User
Verificar que el modelo `User` tenga el trait:
```php
// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    // ...
}
```

### Paso 4: Ejecutar Seeder de Permisos
```bash
# Ejecutar seeder de permisos finales
php setup_permissions.php

# O manualmente
php artisan db:seed --class=FinalPermissionsSeeder

# Verificar permisos
php artisan tinker
>>> \Spatie\Permission\Models\Role::all()->pluck('name');
>>> \Spatie\Permission\Models\Permission::all()->pluck('name');
```

### Paso 5: Verificar Configuración
```bash
# Verificar roles y permisos
php artisan tinker
>>> $admin = \App\Models\User::role('Administrador')->first();
>>> $admin->getAllPermissions()->pluck('name');
```

---

## 📊 Configuración de Reportes

### Paso 1: Instalar DomPDF
```bash
# Verificar instalación
composer show barryvdh/laravel-dompdf

# Si no está instalado
composer require barryvdh/laravel-dompdf
```

### Paso 2: Publicar Configuración
```bash
# Publicar configuración de DomPDF
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Paso 3: Configurar Templates
Verificar que los templates estén en su lugar:
```bash
# Verificar templates de reportes
ls -la resources/views/reports/
```

Templates necesarios:
- `universidad.blade.php`
- `facultad.blade.php`
- `programa.blade.php`
- `profesor.blade.php`

### Paso 4: Configurar Almacenamiento
```bash
# Crear enlace simbólico para storage
php artisan storage:link

# Verificar permisos
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Paso 5: Configurar Colas (Opcional)
```bash
# Instalar Redis (recomendado)
sudo apt install redis-server  # Ubuntu
brew install redis             # macOS

# Configurar colas
php artisan queue:table
php artisan migrate

# Iniciar worker de colas
php artisan queue:work
```



## 🚀 Configuración de Producción

### Paso 1: Optimizar para Producción
```bash
# Instalar dependencias optimizadas
composer install --optimize-autoloader --no-dev

# Compilar assets
npm run build

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar caché de aplicación
php artisan cache:clear
```

### Paso 2: Configurar Variables de Producción
Editar `.env` para producción:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prod_evaluacion_profesores
DB_USERNAME=prod_user
DB_PASSWORD=password_muy_seguro

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=tu-servidor-smtp.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@dominio.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tu-dominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Paso 3: Configurar Servidor Web

#### Con Nginx:
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/evaluacion-profesores/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Con Apache:
```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    DocumentRoot /var/www/evaluacion-profesores/public
    
    <Directory /var/www/evaluacion-profesores/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/evaluacion_profesores_error.log
    CustomLog ${APACHE_LOG_DIR}/evaluacion_profesores_access.log combined
</VirtualHost>
```

### Paso 4: Configurar Supervisor
```bash
# Instalar Supervisor
sudo apt install supervisor

# Crear configuración
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Contenido del archivo:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/evaluacion-profesores/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/evaluacion-profesores/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Recargar configuración
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Paso 5: Configurar SSL (Recomendado)
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obtener certificado SSL
sudo certbot --nginx -d tu-dominio.com

# Configurar renovación automática
sudo crontab -e
# Agregar: 0 12 * * * /usr/bin/certbot renew --quiet
```



```bash
# Hacer ejecutable
chmod +x /var/www/evaluacion-profesores/backup.sh

# Configurar cron job
crontab -e
# Agregar: 0 2 * * * /var/www/evaluacion-profesores/backup.sh
```

### Paso 2: Monitoreo de Logs
```bash
# Verificar logs de aplicación
tail -f storage/logs/laravel.log

# Verificar logs de colas
tail -f storage/logs/worker.log

# Verificar logs del servidor web
tail -f /var/log/nginx/error.log
tail -f /var/log/apache2/error.log
```

### Paso 3: Actualizaciones
```bash
# Backup antes de actualizar
php artisan backup:run

# Actualizar dependencias
composer update
npm update

# Ejecutar migraciones
php artisan migrate

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recompilar assets
npm run build
```

### Paso 4: Optimización de Rendimiento
```bash
# Optimizar autoloader
composer dump-autoload --optimize

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimizar base de datos
php artisan db:optimize

# Limpiar archivos temporales
php artisan storage:clear
```

### Paso 5: Seguridad
```bash
# Verificar permisos de archivos
find /var/www/evaluacion-profesores -type f -exec chmod 644 {} \;
find /var/www/evaluacion-profesores -type d -exec chmod 755 {} \;
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Verificar configuración de seguridad
php artisan config:show | grep -E "(APP_DEBUG|APP_ENV)"

# Actualizar dependencias de seguridad
composer audit
npm audit
```

---

## 🚨 Troubleshooting

### Problemas Comunes

#### Error de Permisos
```bash
# Solución
sudo chown -R www-data:www-data /var/www/evaluacion-profesores
sudo chmod -R 755 /var/www/evaluacion-profesores
sudo chmod -R 775 /var/www/evaluacion-profesores/storage
sudo chmod -R 775 /var/www/evaluacion-profesores/bootstrap/cache
```

#### Error de Base de Datos
```bash
# Verificar conexión
php artisan tinker
>>> DB::connection()->getPdo();

# Verificar migraciones
php artisan migrate:status

# Revertir y reejecutar migraciones
php artisan migrate:rollback
php artisan migrate
```

#### Error de Caché
```bash
# Limpiar todo el caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

#### Error de Colas
```bash
# Verificar estado de colas
php artisan queue:work --once

# Reiniciar workers
sudo supervisorctl restart laravel-worker:*
```

#### Error de Storage
```bash
# Recrear enlace simbólico
rm public/storage
php artisan storage:link

# Verificar permisos
chmod -R 775 storage/
```

---

## 📞 Soporte

### Contacto de Emergencia
- **Email**: soporte@evaluacionprofesores.com
- **Teléfono**: +57 XXX XXX XXXX
- **Horario**: 24/7 para emergencias críticas

### Recursos de Ayuda
- **Documentación**: `/docs`
- **FAQ**: `/docs/faq`
- **Issues**: GitHub Issues
- **Chat**: Slack/Discord (si está disponible)

### Información del Sistema
```bash
# Información del sistema
php artisan about

# Estado de servicios
systemctl status nginx
systemctl status mysql
systemctl status redis
systemctl status supervisor

# Espacio en disco
df -h

# Uso de memoria
free -h

# Logs del sistema
journalctl -u nginx -f
journalctl -u mysql -f
```

---

## ✅ Checklist de Implementación

### ✅ Instalación
- [ ] Requisitos del sistema verificados
- [ ] Dependencias instaladas
- [ ] Proyecto clonado
- [ ] Variables de entorno configuradas

### ✅ Base de Datos
- [ ] Base de datos creada
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados
- [ ] Datos de prueba verificados

### ✅ Filament
- [ ] Paneles configurados
- [ ] Recursos creados
- [ ] Permisos configurados
- [ ] Roles asignados

### ✅ Reportes
- [ ] DomPDF instalado
- [ ] Templates verificados
- [ ] Almacenamiento configurado
- [ ] Generación de PDFs probada

### ✅ API
- [ ] Sanctum configurado
- [ ] Rutas API verificadas
- [ ] CORS configurado
- [ ] Endpoints probados

### ✅ Producción
- [ ] Optimizaciones aplicadas
- [ ] Servidor web configurado
- [ ] SSL configurado
- [ ] Supervisor configurado

### ✅ Testing
- [ ] Funcionalidades verificadas
- [ ] Usuarios de prueba creados
- [ ] Reportes generados
- [ ] API probada

### ✅ Mantenimiento
- [ ] Backup configurado
- [ ] Logs monitoreados
- [ ] Seguridad verificada
- [ ] Documentación actualizada

---

**🎯 ¡El sistema está listo para producción!**

*Manual de Implementación - Versión 1.0*
*Última actualización: {{ date('d/m/Y') }}* 