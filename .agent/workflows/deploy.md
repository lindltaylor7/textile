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
