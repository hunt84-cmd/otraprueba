# Sistema de Gestión de Inventario - Laravel

Un sistema completo para gestionar puntos de ventas, almacenes y productos con roles de usuario y sistema de aprobaciones.

## Características Principales

### 🏢 Gestión de Entidades
- **Almacenes**: Gestión completa con asignación de encargados
- **Puntos de Venta**: Vinculados a almacenes específicos
- **Productos**: Catálogo con códigos únicos, unidades de medida (kg, lb, unidad)
- **Inventario**: Control en tiempo real de stock en almacenes y puntos de venta

### 👥 Sistema de Usuarios y Roles

#### Administrador (admin)
- Gestiona usuarios, roles y permisos
- Crea y configura almacenes y puntos de venta
- Gestiona catálogo de productos
- Crea órdenes de entrada a almacenes
- Supervisa todas las operaciones del sistema

#### Almacenero (almacenero)
- Gestiona el inventario de su almacén asignado
- Aprueba/rechaza entradas de productos
- Crea órdenes de transferencia a puntos de venta
- Recibe devoluciones de puntos de venta
- Controla movimientos de inventario

#### Punto de Venta (punto)
- Gestiona el inventario de su punto de venta
- Aprueba/rechaza recepciones de almacén
- Registra ventas diarias (IPV)
- Crea órdenes de devolución a almacén
- Genera reportes de ventas

### 📋 Sistema de Órdenes y Aprobaciones

Todas las operaciones entre entidades requieren:
1. **Creación de orden** por el solicitante
2. **Aprobación** por el receptor
3. **Ejecución automática** tras aprobación
4. **Registro de movimientos** de inventario

#### Tipos de Órdenes
- **Entrada a Almacén**: Admin → Almacenero
- **Transferencia**: Almacenero → Punto de Venta
- **Devolución**: Punto de Venta → Almacenero
- **Venta**: Registro directo en punto de venta

### 💰 Gestión de Precios
- **Precio de Costo**: Precio al que ingresa al almacén
- **Precio de Venta**: Precio diferente en cada punto de venta
- **Cálculo automático** de valores de inventario

## Estructura de la Base de Datos

### Tablas Principales
- `users` - Usuarios del sistema con roles
- `roles` - Roles del sistema (admin, almacenero, punto)
- `warehouses` - Almacenes
- `sales_points` - Puntos de venta
- `products` - Catálogo de productos
- `warehouse_inventory` - Inventario en almacenes
- `sales_point_inventory` - Inventario en puntos de venta
- `orders` - Órdenes entre entidades
- `order_items` - Productos en cada orden
- `inventory_movements` - Historial de movimientos
- `daily_sales` - Resumen de ventas por día
- `sales_transactions` - Transacciones individuales de venta

## Instalación y Configuración

### Prerrequisitos
- PHP 8.2+
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js y NPM (para assets)

### Pasos de Instalación

1. **Instalar dependencias:**
```bash
composer install
npm install
```

2. **Configurar entorno:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar base de datos en `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **Ejecutar migraciones y seeders:**
```bash
php artisan migrate
php artisan db:seed
```

5. **Compilar assets:**
```bash
npm run build
```

6. **Iniciar servidor:**
```bash
php artisan serve
```

## Usuarios de Prueba

El sistema incluye usuarios de prueba con la contraseña `password`:

- **Admin**: admin@inventory.com
- **Almacenero 1**: almacenero1@inventory.com
- **Almacenero 2**: almacenero2@inventory.com
- **Punto 1**: punto1@inventory.com
- **Punto 2**: punto2@inventory.com

## Flujo de Operaciones

### 1. Entrada de Productos al Almacén
1. Admin crea orden de entrada
2. Almacenero recibe y aprueba/rechaza
3. Sistema actualiza inventario automáticamente

### 2. Transferencia a Punto de Venta
1. Almacenero crea orden de transferencia
2. Punto de venta recibe y aprueba/rechaza
3. Sistema transfiere inventario automáticamente

### 3. Venta en Punto de Venta
1. Punto registra venta directamente
2. Sistema reduce inventario automáticamente
3. Se actualiza el resumen diario (IPV)

### 4. Devolución al Almacén
1. Punto crea orden de devolución
2. Almacenero recibe y aprueba/rechaza
3. Sistema transfiere inventario de vuelta

## Características Técnicas

### Seguridad
- Autenticación mediante Laravel Breeze
- Middleware de roles personalizado
- Protección CSRF en formularios
- Validación de permisos por operación

### Base de Datos
- Migraciones estructuradas
- Relaciones Eloquent optimizadas
- Índices para consultas eficientes
- Integridad referencial

### Interfaz de Usuario
- Bootstrap 5 responsivo
- Iconos Bootstrap Icons
- Dashboard específico por rol
- Formularios dinámicos con JavaScript

### Funcionalidades Avanzadas
- Cálculo automático de precios promedio ponderado
- Historial completo de movimientos de inventario
- Reportes de ventas por período
- Sistema de notificaciones de stock bajo
- Validación de disponibilidad en tiempo real

## API y Extensiones Futuras

El sistema está preparado para:
- API REST para integración con otros sistemas
- Reportes avanzados y analytics
- Sistema de notificaciones push
- Integración con códigos de barras
- Módulo de facturación
- Dashboard con gráficos en tiempo real

## Soporte y Mantenimiento

Para soporte técnico o consultas sobre el sistema, contacte al equipo de desarrollo.

---

**Desarrollado con Laravel 12** - Sistema robusto y escalable para gestión de inventario empresarial.