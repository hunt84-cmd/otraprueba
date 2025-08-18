#!/bin/bash

echo "🚀 Configuración rápida del Sistema de Inventario..."

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env..."
    cp .env.example .env
    
    # Configurar SQLite
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    sed -i 's/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/' .env
    sed -i 's/DB_PORT=3306/#DB_PORT=3306/' .env
    sed -i 's/DB_DATABASE=laravel/#DB_DATABASE=laravel/' .env
    sed -i 's/DB_USERNAME=root/#DB_USERNAME=root/' .env
    sed -i 's/DB_PASSWORD=/#DB_PASSWORD=/' .env
fi

# Crear base de datos SQLite
echo "🗄️ Creando base de datos SQLite..."
touch database/database.sqlite

# Generar clave de aplicación
echo "🔑 Generando clave de aplicación..."
if command -v php > /dev/null 2>&1; then
    php artisan key:generate --force
    
    # Ejecutar migraciones
    echo "🏗️ Ejecutando migraciones..."
    php artisan migrate --force
    
    # Ejecutar seeders
    echo "🌱 Poblando base de datos..."
    php artisan db:seed --force
    
    echo "✅ ¡Configuración completada!"
    echo ""
    echo "🎯 Usuarios de prueba:"
    echo "   Admin: admin@inventory.com / password"
    echo "   Almacenero: almacenero1@inventory.com / password"
    echo "   Punto: punto1@inventory.com / password"
    echo ""
    echo "🚀 Para iniciar el servidor:"
    echo "   php artisan serve"
    echo ""
    echo "🌐 Luego accede a: http://localhost:8000"
else
    echo "⚠️ PHP no encontrado. Instala PHP 8.2+ y ejecuta:"
    echo "   php artisan key:generate"
    echo "   php artisan migrate"
    echo "   php artisan db:seed"
    echo "   php artisan serve"
fi