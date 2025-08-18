@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-gear"></i> Editar Usuario: {{ $user->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Dejar en blanco para mantener la contraseña actual</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label">Rol</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">Seleccionar rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }} - {{ $role->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado del Usuario</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Usuario activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-briefcase"></i> Asignaciones del Usuario
                </h5>
            </div>
            <div class="card-body">
                @if($user->managedWarehouses->count() > 0)
                    <h6>Almacenes Gestionados:</h6>
                    <ul class="list-unstyled">
                        @foreach($user->managedWarehouses as $warehouse)
                            <li>
                                <i class="bi bi-building"></i> {{ $warehouse->name }} ({{ $warehouse->code }})
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($user->managedSalesPoints->count() > 0)
                    <h6>Puntos de Venta Gestionados:</h6>
                    <ul class="list-unstyled">
                        @foreach($user->managedSalesPoints as $salesPoint)
                            <li>
                                <i class="bi bi-shop"></i> {{ $salesPoint->name }} ({{ $salesPoint->code }})
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($user->managedWarehouses->count() === 0 && $user->managedSalesPoints->count() === 0)
                    <p class="text-muted">Este usuario no tiene asignaciones específicas.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información del Usuario
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Fecha de Registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Última Actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Rol Actual:</strong> 
                    <span class="badge bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'almacenero' ? 'success' : 'info') }}">
                        {{ ucfirst($user->role->name) }}
                    </span>
                </p>
                <p><strong>Estado:</strong> 
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>
                <p><strong>Órdenes Creadas:</strong> {{ $user->sentOrders->count() }}</p>
                <p><strong>Órdenes Aprobadas:</strong> {{ $user->approvedOrders->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection