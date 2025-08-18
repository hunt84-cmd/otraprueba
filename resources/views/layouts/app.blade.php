<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Sistema de Inventario')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .main-content {
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: 600;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .badge {
            font-size: 0.75em;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-3">
                        <h5 class="text-white">Sistema de Inventario</h5>
                        <small class="text-muted">{{ auth()->user()->role->description ?? 'Usuario' }}</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                    <i class="bi bi-people"></i> Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.warehouses*') ? 'active' : '' }}" href="{{ route('admin.warehouses') }}">
                                    <i class="bi bi-building"></i> Almacenes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.sales-points*') ? 'active' : '' }}" href="{{ route('admin.sales-points') }}">
                                    <i class="bi bi-shop"></i> Puntos de Venta
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                                    <i class="bi bi-box"></i> Productos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                                    <i class="bi bi-clipboard-check"></i> Órdenes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.warehouse-entry*') ? 'active' : '' }}" href="{{ route('admin.warehouse-entry.create') }}">
                                    <i class="bi bi-plus-circle"></i> Entrada a Almacén
                                </a>
                            </li>
                        @elseif(auth()->user()->isWarehouseManager())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('warehouse.dashboard') ? 'active' : '' }}" href="{{ route('warehouse.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('warehouse.inventory') ? 'active' : '' }}" href="{{ route('warehouse.inventory') }}">
                                    <i class="bi bi-boxes"></i> Inventario
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('warehouse.orders*') ? 'active' : '' }}" href="{{ route('warehouse.orders') }}">
                                    <i class="bi bi-clipboard-check"></i> Órdenes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('warehouse.transfers*') ? 'active' : '' }}" href="{{ route('warehouse.transfers.create') }}">
                                    <i class="bi bi-arrow-right-circle"></i> Enviar a Puntos
                                </a>
                            </li>
                        @elseif(auth()->user()->isSalesPointManager())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales-point.dashboard') ? 'active' : '' }}" href="{{ route('sales-point.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales-point.inventory') ? 'active' : '' }}" href="{{ route('sales-point.inventory') }}">
                                    <i class="bi bi-boxes"></i> Inventario
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales-point.orders*') ? 'active' : '' }}" href="{{ route('sales-point.orders') }}">
                                    <i class="bi bi-clipboard-check"></i> Órdenes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales-point.sales*') ? 'active' : '' }}" href="{{ route('sales-point.sales') }}">
                                    <i class="bi bi-cash-coin"></i> Ventas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales-point.sales.daily') ? 'active' : '' }}" href="{{ route('sales-point.sales.daily') }}">
                                    <i class="bi bi-calendar-day"></i> Ventas Diarias
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales-point.returns*') ? 'active' : '' }}" href="{{ route('sales-point.returns.create') }}">
                                    <i class="bi bi-arrow-left-circle"></i> Devoluciones
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top navbar -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-item-text">{{ auth()->user()->email }}</span></li>
                                <li><span class="dropdown-item-text"><small class="text-muted">{{ auth()->user()->role->name ?? 'Sin rol' }}</small></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Flash messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page content -->
                <div class="content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>