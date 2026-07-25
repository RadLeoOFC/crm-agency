<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;

try {
    $kernel = $app->make(Kernel::class);
    // Принудительный вызов миграции без участия консольной оболочки Symfony
    $status = $kernel->call('migrate', ['--force' => true]);
    echo "--- РЕЗУЛЬТАТ МИГРАЦИИ ---\n";
    echo $kernel->output();
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
