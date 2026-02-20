---
description: Preparar el proyecto Laravel para producción
---

Este flujo de trabajo te ayudará a preparar el proyecto para ser desplegado en un servidor de producción.

### 1. Configuración del Entorno
- Asegúrate de que el archivo `.env` en el servidor tenga:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=https://tu-dominio.com`
- Genera la llave de la aplicación si no existe:
```bash
php artisan key:generate
```

### 2. Instalación de Dependencias
Ejecuta los siguientes comandos para instalar dependencias de PHP y JavaScript:
```bash
// turbo
composer install --optimize-autoloader --no-dev
// turbo
npm install
```

### 3. Compilación de Assets (Frontend)
Genera los archivos optimizados para producción:
```bash
// turbo
npm run build
```
**Importante (Git):** Si despliegas vía Git, asegúrate de que la carpeta de compilación se suba:
```bash
git add public/build -f
git commit -m "Compilación para producción"
git push
```

### 4. Optimización de Laravel
Ejecuta estos comandos para cachear la configuración y rutas, lo que mejora significativamente el rendimiento:
```bash
// turbo
php artisan config:cache
// turbo
php artisan route:cache
// turbo
php artisan view:cache
// turbo
php artisan event:cache
```

### 5. Base de Datos
Corre las migraciones en el servidor de producción:
```bash
// turbo
php artisan migrate --force
```

### 6. Permisos de Almacenamiento
Asegúrate de que las carpetas de almacenamiento tengan los permisos correctos:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Enlace Simbólico de Almacenamiento
Si usas archivos públicos:
```bash
// turbo
php artisan storage:link
```

### 8. Despliegue en Hostinger (Shared Hosting)
Si estás desplegando en Hostinger y tienes tus archivos en la carpeta `public_html`, es muy probable que te salga un error 404 porque Laravel espera que la raíz sea la carpeta `public`.

**Solución 1: Archivo .htaccess en la raíz**
He creado un archivo `.htaccess` en la raíz del proyecto con el siguiente contenido. Asegúrate de que esté subido a tu servidor en la carpeta principal (donde están `app`, `config`, etc.):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Solución 2: Mover el contenido de `public`**
Si prefieres no usar el redireccionamiento:
1. Mueve todo el contenido de la carpeta `public/` (incluyendo el archivo `build`) directamente a `public_html/`.
2. Edita `index.php` (ahora en `public_html/`) y ajusta las rutas de `vendor/autoload.php` y `storage/bootstrap/app.php` para que apunten correctamente (usualmente quitando un `..`).
