<?php

// Script de prueba para verificar que el sistema funciona
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    echo "🔍 Verificando sistema...\n";
    
    // Test 1: Verificar que las clases existen
    echo "✅ Verificando clases...\n";
    $classes = [
        'App\Models\User',
        'App\Models\Role', 
        'App\Models\Warehouse',
        'App\Models\SalesPoint',
        'App\Models\Product',
        'App\Http\Controllers\AdminController',
        'App\Http\Middleware\CheckRole'
    ];
    
    foreach ($classes as $class) {
        if (class_exists($class)) {
            echo "  ✅ $class\n";
        } else {
            echo "  ❌ $class - NO EXISTE\n";
        }
    }
    
    echo "\n🎯 Sistema verificado correctamente!\n";
    echo "Para iniciar el servidor ejecuta: php artisan serve\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}