#!/bin/bash

echo "🚀 Instalando Sistema de Gestión de Inventario..."

# Install PHP dependencies
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "📦 Instalando dependencias de Node.js..."
npm install

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "⚙️ Configurando archivo de entorno..."
    cp .env.example .env
fi

# Generate application key
echo "🔑 Generando clave de aplicación..."
php artisan key:generate

# Configure database (SQLite for simplicity)
echo "🗄️ Configurando base de datos..."
touch database/database.sqlite

# Update .env for SQLite
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i 's/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/' .env
sed -i 's/DB_PORT=3306/#DB_PORT=3306/' .env
sed -i 's/DB_DATABASE=laravel/#DB_DATABASE=laravel/' .env
sed -i 's/DB_USERNAME=root/#DB_USERNAME=root/' .env
sed -i 's/DB_PASSWORD=/#DB_PASSWORD=/' .env

# Run migrations and seeders
echo "🏗️ Ejecutando migraciones..."
php artisan migrate --force

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

# Build assets
echo "🎨 Compilando assets..."
npm run build

# Set permissions
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

echo "✅ ¡Instalación completada!"
echo ""
echo "🎯 Usuarios de prueba:"
echo "   Admin: admin@inventory.com"
echo "   Almacenero: almacenero1@inventory.com"
echo "   Punto: punto1@inventory.com"
echo "   Contraseña: password"
echo ""
echo "🚀 Para iniciar el servidor:"
echo "   php artisan serve"
echo ""
echo "🌐 Accede al sistema en: http://localhost:8000"