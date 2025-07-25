# 🚀 Manual de Despliegue - Sistema de Evaluación de Competencias Digitales Docentes

## 📋 Índice
1. [Preparación del Servidor](#preparación-del-servidor)
2. [Configuración del Entorno](#configuración-del-entorno)
3. [Despliegue de la Aplicación](#despliegue-de-la-aplicación)
4. [Configuración de Servicios](#configuración-de-servicios)
5. [Optimización de Rendimiento](#optimización-de-rendimiento)
6. [Configuración de Seguridad](#configuración-de-seguridad)
7. [Monitoreo y Logs](#monitoreo-y-logs)
8. [Backup y Recuperación](#backup-y-recuperación)

---

## 🖥️ Preparación del Servidor



### Instalación de Dependencias del Sistema

#### Actualizar Sistema
```bash
# Actualizar paquetes del sistema
sudo apt update && sudo apt upgrade -y

# Instalar herramientas básicas
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
```

#### Instalar PHP 8.2
```bash
# Agregar repositorio de PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Instalar PHP y extensiones
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-gd php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-json php8.2-tokenizer php8.2-fileinfo php8.2-redis php8.2-intl

# Verificar instalación
php -v
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml|curl|gd|zip|redis)"
```

#### Instalar Composer
```bash
# Descargar e instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verificar instalación
composer --version
```

#### Instalar Node.js
```bash
# Agregar repositorio de Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -

# Instalar Node.js
sudo apt-get install -y nodejs

# Verificar instalación
node --version
npm --version
```

#### Instalar MySQL 8.0
```bash
# Instalar MySQL
sudo apt install -y mysql-server

# Configurar MySQL
sudo mysql_secure_installation

# Crear base de datos y usuario
sudo mysql -u root -p
```

```sql
-- Crear base de datos
CREATE DATABASE evaluacion_profesores CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario
CREATE USER 'eval_user'@'localhost' IDENTIFIED BY 'tu_password_seguro';
GRANT ALL PRIVILEGES ON evaluacion_profesores.* TO 'eval_user'@'localhost';
FLUSH PRIVILEGES;

-- Verificar
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User = 'eval_user';
EXIT;
```

#### Instalar Redis
```bash
# Instalar Redis
sudo apt install -y redis-server

# Configurar Redis
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Verificar instalación
redis-cli ping
```

#### Instalar Nginx
```bash
# Instalar Nginx
sudo apt install -y nginx

# Habilitar Nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# Verificar instalación
sudo systemctl status nginx
```

---

## ⚙️ Configuración del Entorno

### Crear Usuario del Sistema
```bash
# Crear usuario para la aplicación
sudo adduser --disabled-password --gecos "" evaluacion
sudo usermod -aG sudo evaluacion

# Cambiar al usuario
sudo su - evaluacion
```

### Configurar SSH Keys
```bash
# Generar SSH key (si no existe)
ssh-keygen -t rsa -b 4096 -C "tu-email@dominio.com"

# Agregar clave pública a GitHub/GitLab
cat ~/.ssh/id_rsa.pub
```

### Clonar el Proyecto
```bash
# Navegar al directorio de aplicaciones
cd /var/www

# Clonar el proyecto
sudo git clone https://github.com/tu-usuario/evaluacion-profesores.git
sudo chown -R evaluacion:evaluacion evaluacion-profesores
cd evaluacion-profesores

# Verificar estructura
ls -la
```

### Configurar Variables de Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Editar configuración
nano .env
```

Configuración de producción en `.env`:
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
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evaluacion_profesores
DB_USERNAME=eval_user
DB_PASSWORD=tu_password_seguro

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=tu-servidor-smtp.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@dominio.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tu-dominio.com"
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

---

## 🚀 Despliegue de la Aplicación

### Instalar Dependencias
```bash
# Instalar dependencias PHP
composer install --optimize-autoloader --no-dev

# Instalar dependencias Node.js
npm install

# Compilar assets
npm run build

# Verificar instalación
php artisan --version
composer --version
```

### Configurar Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate --force

# Ejecutar seeders
php artisan db:seed --force

# Verificar datos
php artisan tinker
>>> \App\Models\User::count();
>>> \App\Models\Test::count();
>>> \App\Models\Institution::count();
>>> exit
```

### Configurar Almacenamiento
```bash
# Crear enlace simbólico
php artisan storage:link

# Configurar permisos
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
```

### Optimizar para Producción
```bash
# Cache de configuración
php artisan config:cache

# Cache de rutas
php artisan route:cache

# Cache de vistas
php artisan view:cache

# Optimizar autoloader
composer dump-autoload --optimize

# Limpiar caché de aplicación
php artisan cache:clear
```

---

## 🔧 Configuración de Servicios

### Configurar Nginx

#### Crear Configuración del Sitio
```bash
sudo nano /etc/nginx/sites-available/evaluacion-profesores
```

Contenido de la configuración:
```nginx
server {
    listen 80;
    server_name tu-dominio.com www.tu-dominio.com;
    root /var/www/evaluacion-profesores/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }
}
```

#### Habilitar el Sitio
```bash
# Crear enlace simbólico
sudo ln -s /etc/nginx/sites-available/evaluacion-profesores /etc/nginx/sites-enabled/

# Eliminar sitio por defecto
sudo rm /etc/nginx/sites-enabled/default

# Verificar configuración
sudo nginx -t

# Recargar Nginx
sudo systemctl reload nginx
```

### Configurar PHP-FPM
```bash
# Editar configuración de PHP-FPM
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

Configuraciones importantes:
```ini
user = www-data
group = www-data
listen = /run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

```bash
# Recargar PHP-FPM
sudo systemctl reload php8.1-fpm

# Verificar estado
sudo systemctl status php8.1-fpm
```

### Configurar Supervisor para Colas
```bash
# Instalar Supervisor
sudo apt install -y supervisor

# Crear configuración
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Contenido de la configuración:
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
stdout_logfile_maxbytes=50MB
stdout_logfile_backups=10
```

```bash
# Recargar configuración
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*

# Verificar estado
sudo supervisorctl status
```

### Configurar Cron Jobs
```bash
# Editar crontab
crontab -e
```

Agregar las siguientes líneas:
```bash
# Laravel Scheduler
* * * * * cd /var/www/evaluacion-profesores && php artisan schedule:run >> /dev/null 2>&1

# Limpiar reportes expirados (diario a las 2 AM)
0 2 * * * cd /var/www/evaluacion-profesores && php artisan reports:cleanup >> /dev/null 2>&1

# Backup de base de datos (diario a las 3 AM)
0 3 * * * /var/www/evaluacion-profesores/backup.sh >> /var/log/backup.log 2>&1
```

---

## ⚡ Optimización de Rendimiento

### Configurar OPcache
```bash
# Editar configuración de OPcache
sudo nano /etc/php/8.1/fpm/conf.d/10-opcache.ini
```

Configuración optimizada:
```ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
opcache.fast_shutdown=1
opcache.enable_file_override=1
opcache.validate_permission=1
opcache.validate_root=1
```

### Configurar Redis
```bash
# Editar configuración de Redis
sudo nano /etc/redis/redis.conf
```

Configuraciones importantes:
```ini
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

```bash
# Reiniciar Redis
sudo systemctl restart redis-server
```

### Configurar MySQL
```bash
# Editar configuración de MySQL
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Configuraciones optimizadas:
```ini
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
query_cache_type = 1
query_cache_size = 128M
query_cache_limit = 2M
max_connections = 200
thread_cache_size = 8
table_open_cache = 2000
```

```bash
# Reiniciar MySQL
sudo systemctl restart mysql
```

### Configurar Nginx para Rendimiento
```bash
# Editar configuración principal de Nginx
sudo nano /etc/nginx/nginx.conf
```

Configuraciones en el bloque `http`:
```nginx
http {
    # Configuraciones existentes...
    
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/x-javascript
        application/xml+rss
        application/javascript
        application/json;

    # Client max body size
    client_max_body_size 100M;

    # Keep alive
    keepalive_timeout 65;
    keepalive_requests 100;

    # File cache
    open_file_cache max=1000 inactive=20s;
    open_file_cache_valid 30s;
    open_file_cache_min_uses 2;
    open_file_cache_errors on;
}
```

---

## 🔒 Configuración de Seguridad


### Configurar Seguridad de PHP
```bash
# Editar configuración de PHP
sudo nano /etc/php/8.1/fpm/php.ini
```

### Configurar Permisos de Archivos
```bash
# Configurar permisos correctos
sudo find /var/www/evaluacion-profesores -type f -exec chmod 644 {} \;
sudo find /var/www/evaluacion-profesores -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/evaluacion-profesores/storage
sudo chmod -R 775 /var/www/evaluacion-profesores/bootstrap/cache
sudo chown -R www-data:www-data /var/www/evaluacion-profesores
```

---

## 📊 Monitoreo y Logs

### Configurar Logs de Aplicación
```bash
# Verificar logs de Laravel
tail -f /var/www/evaluacion-profesores/storage/logs/laravel.log

# Verificar logs de colas
tail -f /var/www/evaluacion-profesores/storage/logs/worker.log

# Verificar logs de Nginx
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log

# Verificar logs de PHP-FPM
sudo tail -f /var/log/php8.1-fpm.log
```

### Configurar Monitoreo del Sistema
```bash
# Instalar herramientas de monitoreo
sudo apt install -y htop iotop nethogs

# Verificar uso de recursos
htop
df -h
free -h
```



---

## 💾 Backup y Recuperación

### Script de Backup Automático
```bash
# Crear script de backup
nano /var/www/evaluacion-profesores/backup.sh
```

Contenido del script:
```bash
#!/bin/bash

# Configuración
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/evaluacion_profesores"
DB_NAME="evaluacion_profesores"
DB_USER="eval_user"
DB_PASS="tu_password_seguro"
APP_DIR="/var/www/evaluacion-profesores"

# Crear directorio de backup
mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Backup de archivos de la aplicación
tar -czf $BACKUP_DIR/app_backup_$DATE.tar.gz -C /var/www evaluacion-profesores

# Backup de archivos subidos
tar -czf $BACKUP_DIR/storage_backup_$DATE.tar.gz -C /var/www evaluacion-profesores/storage/app

# Eliminar backups antiguos (más de 30 días)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

# Log del backup
echo "Backup completado: $DATE" >> $BACKUP_DIR/backup.log

# Verificar espacio en disco
df -h $BACKUP_DIR
```

```bash
# Hacer ejecutable
chmod +x /var/www/evaluacion-profesores/backup.sh

# Probar backup manual
/var/www/evaluacion-profesores/backup.sh
```

### Script de Recuperación
```bash
# Crear script de recuperación
nano /var/www/evaluacion-profesores/restore.sh
```

Contenido del script:
```bash
#!/bin/bash

# Configuración
BACKUP_FILE=$1
DB_NAME="evaluacion_profesores"
DB_USER="eval_user"
DB_PASS="tu_password_seguro"
APP_DIR="/var/www/evaluacion-profesores"

if [ -z "$BACKUP_FILE" ]; then
    echo "Uso: $0 <archivo_backup>"
    exit 1
fi

# Verificar que el archivo existe
if [ ! -f "$BACKUP_FILE" ]; then
    echo "Error: El archivo $BACKUP_FILE no existe"
    exit 1
fi

# Restaurar base de datos
echo "Restaurando base de datos..."
mysql -u $DB_USER -p$DB_PASS $DB_NAME < $BACKUP_FILE

# Limpiar caché
cd $APP_DIR
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Recuperación completada"
```

```bash
# Hacer ejecutable
chmod +x /var/www/evaluacion-profesores/restore.sh
```



### Actualizaciones del Sistema
```bash
# Actualizar sistema operativo
sudo apt update && sudo apt upgrade -y

# Reiniciar servicios
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
sudo systemctl restart mysql
sudo systemctl restart redis-server
```

### Actualizaciones de la Aplicación
```bash
# Backup antes de actualizar
/var/www/evaluacion-profesores/backup.sh

# Cambiar al directorio de la aplicación
cd /var/www/evaluacion-profesores

# Obtener cambios del repositorio
git pull origin main

# Instalar dependencias actualizadas
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Ejecutar migraciones
php artisan migrate --force

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recrear caché optimizado
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar workers
sudo supervisorctl restart laravel-worker:*
```

### Limpieza de Archivos Temporales
```bash
# Limpiar logs antiguos
find /var/www/evaluacion-profesores/storage/logs -name "*.log" -mtime +30 -delete

# Limpiar archivos temporales
php artisan storage:clear

# Limpiar caché de Composer
composer clear-cache

# Limpiar caché de npm
npm cache clean --force
```

### Monitoreo de Rendimiento
```bash
# Verificar uso de memoria
free -h

# Verificar uso de disco
df -h

# Verificar procesos PHP
ps aux | grep php

# Verificar colas
php artisan queue:work --once

# Verificar logs de errores
tail -f /var/www/evaluacion-profesores/storage/logs/laravel.log
```



### Problemas Comunes

#### Error 500 - Internal Server Error
```bash
# Verificar logs de Laravel
tail -f /var/www/evaluacion-profesores/storage/logs/laravel.log

# Verificar logs de Nginx
sudo tail -f /var/log/nginx/error.log

# Verificar permisos
ls -la /var/www/evaluacion-profesores/storage/
ls -la /var/www/evaluacion-profesores/bootstrap/cache/

# Solución: Corregir permisos
sudo chown -R www-data:www-data /var/www/evaluacion-profesores
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
php artisan migrate --force
```

#### Error de Colas
```bash
# Verificar estado de workers
sudo supervisorctl status

# Reiniciar workers
sudo supervisorctl restart laravel-worker:*

# Verificar logs de workers
tail -f /var/www/evaluacion-profesores/storage/logs/worker.log

# Probar cola manualmente
php artisan queue:work --once
```

#### Error de SSL/HTTPS
```bash
# Verificar certificado SSL
sudo certbot certificates

# Renovar certificado
sudo certbot renew

# Verificar configuración de Nginx
sudo nginx -t
sudo systemctl reload nginx
```


### Comandos de Diagnóstico
```bash
# Estado de servicios
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
sudo systemctl status redis-server
sudo systemctl status supervisor

# Información del sistema
php artisan about
php artisan config:show
php artisan route:list

# Verificar conexiones
netstat -tlnp | grep :80
netstat -tlnp | grep :443
netstat -tlnp | grep :3306
netstat -tlnp | grep :6379

# Verificar logs del sistema
journalctl -u nginx -f
journalctl -u php8.1-fpm -f
journalctl -u mysql -f
```

---

## ✅ Checklist de Despliegue

### ✅ Preparación del Servidor
- [ ] Sistema operativo actualizado
- [ ] PHP 8.2 instalado y configurado
- [ ] MySQL 8.0 instalado y configurado
- [ ] Redis instalado y configurado
- [ ] Nginx instalado y configurado
- [ ] Composer instalado
- [ ] Node.js instalado

### ✅ Configuración del Entorno
- [ ] Usuario del sistema creado
- [ ] Proyecto clonado
- [ ] Variables de entorno configuradas
- [ ] Base de datos creada y configurada
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados

### ✅ Despliegue de la Aplicación
- [ ] Dependencias instaladas
- [ ] Assets compilados
- [ ] Almacenamiento configurado
- [ ] Optimizaciones aplicadas
- [ ] Permisos configurados

### ✅ Configuración de Servicios
- [ ] Nginx configurado
- [ ] PHP-FPM configurado
- [ ] Supervisor configurado
- [ ] Cron jobs configurados
- [ ] SSL configurado

### ✅ Optimización de Rendimiento
- [ ] OPcache configurado
- [ ] Redis optimizado
- [ ] MySQL optimizado
- [ ] Nginx optimizado
- [ ] Gzip habilitado

### ✅ Configuración de Seguridad
- [ ] Firewall configurado
- [ ] SSL/HTTPS configurado
- [ ] Permisos de archivos configurados
- [ ] Fail2ban configurado
- [ ] Headers de seguridad configurados

### ✅ Monitoreo y Logs
- [ ] Logs configurados
- [ ] Monitoreo del sistema configurado
- [ ] Logrotate configurado
- [ ] Alertas configuradas




---

## 📞 Soporte de Emergencia

### Contacto Inmediato
- **Email**: soporte@evaluacionprofesores.com
- **Teléfono**: +57 XXX XXX XXXX
- **Horario**: 24/7 para emergencias críticas

### Información del Sistema
```bash
# Información del servidor
uname -a
lsb_release -a

# Información de la aplicación
php artisan about
php artisan config:show | grep APP_URL

# Estado de servicios
systemctl status nginx php8.2-fpm mysql redis-server supervisor

# Logs recientes
tail -20 /var/www/evaluacion-profesores/storage/logs/laravel.log
```

---

**🎯 ¡El sistema está desplegado y listo para producción!**

*Manual de Despliegue - Versión 1.0*
