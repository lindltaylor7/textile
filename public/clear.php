<?php
define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Limpiando caché de Laravel...</h1>";
try {
    $kernel->call('optimize:clear');
    echo "<p>Caché optimizada: OK</p>";
    $kernel->call('config:clear');
    echo "<p>Caché config: OK</p>";
    $kernel->call('route:clear');
    echo "<p>Caché rutas: OK</p>";
    echo "<br><a href='./'>Regresar a la App</a>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
