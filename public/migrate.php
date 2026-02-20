<?php
define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Ejecutando Migraciones...</h1>";
try {
    // El flag --force es obligatorio en producción
    $kernel->call('migrate', ['--force' => true]);
    echo "<p>Migraciones completadas con éxito.</p>";
    echo "<pre>";
    echo $kernel->output();
    echo "</pre>";
    echo "<br><a href='./'>Regresar a la App</a>";
} catch (\Exception $e) {
    echo "<h2 style='color:red'>Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
