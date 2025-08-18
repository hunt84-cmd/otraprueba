# 📦 Sistema de Gestión de Inventario - Laravel

## 🎯 Descripción del Sistema

Sistema completo desarrollado en Laravel para gestionar **puntos de ventas**, **almacenes** y **productos** con un robusto sistema de usuarios, roles y aprobaciones.

## 🏗️ Arquitectura del Sistema

### 🔄 Flujo de Operaciones

```
ADMIN → Crea entrada → ALMACÉN → Aprueba → Inventario actualizado
ALMACENERO → Crea transferencia → PUNTO DE VENTA → Aprueba → Inventario transferido
PUNTO DE VENTA → Registra venta → Inventario reducido → IPV actualizado
PUNTO DE VENTA → Crea devolución → ALMACÉN → Aprueba → Inventario devuelto
```

### 👥 Roles y Permisos

#### 🔴 **ADMIN**
- ✅ Gestiona usuarios y asigna roles
- ✅ Crea y configura almacenes
- ✅ Crea y configura puntos de venta
- ✅ Gestiona catálogo de productos
- ✅ Crea órdenes de entrada a almacenes
- ✅ Supervisa todas las operaciones

#### 🟢 **ALMACENERO**
- ✅ Gestiona inventario de su almacén
- ✅ Aprueba/rechaza entradas de productos
- ✅ Crea transferencias a puntos de venta
- ✅ Aprueba/rechaza devoluciones
- ✅ Controla movimientos de inventario

#### 🔵 **PUNTO**
- ✅ Gestiona inventario de su punto de venta
- ✅ Aprueba/rechaza recepciones de almacén
- ✅ Registra ventas diarias (IPV)
- ✅ Crea devoluciones a almacén
- ✅ Genera reportes de ventas

## 🗄️ Estructura de Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `roles` | Roles del sistema (admin, almacenero, punto) |
| `users` | Usuarios con roles asignados |
| `warehouses` | Almacenes con encargados |
| `sales_points` | Puntos de venta vinculados a almacenes |
| `products` | Catálogo de productos con códigos únicos |
| `warehouse_inventory` | Inventario en almacenes |
| `sales_point_inventory` | Inventario en puntos de venta |
| `orders` | Órdenes entre entidades |
| `order_items` | Productos en cada orden |
| `inventory_movements` | Historial de movimientos |
| `daily_sales` | Resumen de ventas diarias (IPV) |
| `sales_transactions` | Transacciones individuales |

### 🔗 Relaciones Clave

- **Almacén** ↔ **Múltiples Puntos de Venta**
- **Usuario** → **Rol** (admin/almacenero/punto)
- **Producto** → **Múltiples Inventarios** (almacén/punto)
- **Orden** → **Múltiples Items** → **Productos**

## 💼 Funcionalidades Principales

### 📋 Sistema de Órdenes y Aprobaciones

**Todas las operaciones requieren aprobación del receptor:**

1. **Entrada a Almacén**: Admin → Almacenero
2. **Transferencia**: Almacenero → Punto de Venta  
3. **Devolución**: Punto de Venta → Almacenero
4. **Venta**: Registro directo (sin aprobación)

### 💰 Gestión de Precios

- **Precio de Costo**: Al ingresar al almacén
- **Precio de Venta**: Diferente en cada punto de venta
- **Cálculo Automático**: Promedio ponderado en transferencias

### 📊 Reportes y Control

- **IPV (Ventas por Día)**: Resumen automático de ventas diarias
- **Movimientos de Inventario**: Historial completo de transacciones
- **Stock Bajo**: Alertas automáticas
- **Reportes de Ventas**: Por período y producto

## 🛠️ Instalación

### Prerrequisitos
```bash
- PHP 8.2+
- Composer
- Node.js & NPM
- Base de datos (MySQL/PostgreSQL/SQLite)
```

### Instalación Rápida
```bash
# 1. Instalar dependencias
composer install
npm install

# 2. Configurar entorno
cp .env.example .env
php artisan key:generate

# 3. Configurar base de datos en .env
# 4. Ejecutar migraciones
php artisan migrate
php artisan db:seed

# 5. Compilar assets
npm run build

# 6. Iniciar servidor
php artisan serve
```

## 👤 Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | admin@inventory.com | password |
| Almacenero | almacenero1@inventory.com | password |
| Punto | punto1@inventory.com | password |

## 🌟 Características Técnicas

### 🔒 Seguridad
- ✅ Autenticación Laravel Breeze
- ✅ Middleware de roles personalizado
- ✅ Protección CSRF
- ✅ Validación de permisos por operación

### 📱 Interfaz de Usuario
- ✅ Bootstrap 5 responsivo
- ✅ Dashboard específico por rol
- ✅ Iconos Bootstrap Icons
- ✅ Formularios dinámicos con JavaScript
- ✅ Alertas y notificaciones

### 🔧 Backend
- ✅ Servicios de negocio separados
- ✅ Transacciones de base de datos
- ✅ Relaciones Eloquent optimizadas
- ✅ Validación robusta
- ✅ Manejo de errores

## 📈 Casos de Uso

### 1. 📥 Entrada de Productos
```
Admin → Crea orden entrada → Almacenero → Aprueba → Stock actualizado
```

### 2. 🔄 Transferencia a Punto de Venta
```
Almacenero → Crea transferencia → Punto → Aprueba → Inventario transferido
```

### 3. 💵 Venta en Punto
```
Punto → Registra venta → Stock reducido → IPV actualizado
```

### 4. ↩️ Devolución
```
Punto → Crea devolución → Almacenero → Aprueba → Stock devuelto
```

## 🚀 Próximas Mejoras

- 📊 Dashboard con gráficos en tiempo real
- 📱 API REST para aplicaciones móviles
- 🔔 Sistema de notificaciones push
- 📄 Generación de reportes PDF
- 🏷️ Integración con códigos de barras
- 💳 Módulo de facturación
- 📈 Analytics avanzados

## 📞 Soporte

Para soporte técnico o consultas sobre el sistema, contacte al equipo de desarrollo.

---

**🛡️ Desarrollado con Laravel 12** - Sistema empresarial robusto y escalable.