@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <h1>Lista de Usuarios</h1>
@stop

@section('main_content_body')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row w-100 mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('users.index') }}">
                            <!-- Preserve filters if searching? Logic might get tricky. For now, simple search resets filters or include hidden inputs if needed. Let's keep it simple: Search is global. -->
                            <!-- To allow searching efficiently within a filtered view, we should keep the hidden inputs if they exist, but PHP handles that if we construct the form right.
                                 Simplest approach: A dedicated search bar that searches EVERYTHING. -->
                            <div class="input-group">
                                <input type="text" name="busqueda" class="form-control" placeholder="Buscar por Cédula, Nombre o Correo..." value="{{ request('busqueda') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                                 @if(request('rol'))
                                    <input type="hidden" name="rol" value="{{ request('rol') }}">
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row w-100">
                    <div class="col-md-12">
                         <!-- Tabs for Roles logic using simple links -->
                         <div class="btn-group w-100 mb-3" role="group">
                            <a href="{{ route('users.index') }}" class="btn btn-{{ !request('rol') ? 'primary' : 'default' }}">Todos</a>
                            <a href="{{ route('users.index', ['rol' => 'admin']) }}" class="btn btn-{{ request('rol') == 'admin' ? 'primary' : 'default' }}">Administradores</a>
                            <a href="{{ route('users.index', ['rol' => 'docente']) }}" class="btn btn-{{ request('rol') == 'docente' ? 'primary' : 'default' }}">Profesores</a>
                            <a href="{{ route('users.index', ['rol' => 'estudiante']) }}" class="btn btn-{{ request('rol') == 'estudiante' ? 'primary' : 'default' }}">Estudiantes</a>
                        </div>
                    </div>
                </div>

                @if(request('rol') == 'estudiante')
                <form method="GET" action="{{ route('users.index') }}" class="mb-2">
                    <input type="hidden" name="rol" value="estudiante">
                    <div class="row">
                        <div class="col-md-5">
                            <select name="grado" class="form-control" onchange="this.form.submit()">
                                <option value="">Filtrar por Grado</option>
                                @foreach(['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'] as $g)
                                    <option value="{{ $g }}" {{ request('grado') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select name="seccion" class="form-control" onchange="this.form.submit()">
                                <option value="">Filtrar por Sección</option>
                                @foreach(['A', 'B', 'C', 'D', 'E', 'U'] as $s)
                                    <option value="{{ $s }}" {{ request('seccion') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('users.index', ['rol' => 'estudiante']) }}" class="btn btn-default btn-block">Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
                @endif

                <h3 class="card-title">Usuarios Registrados</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Cédula</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id_usuario }}</td>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->cedula ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $usuario->rol == 'admin' ? 'danger' : ($usuario->rol == 'docente' ? 'warning' : 'info') }}">{{ ucfirst($usuario->rol) }}</span>
                                @if($usuario->rol == 'estudiante' && $usuario->grado)
                                    <br><small>{{ $usuario->grado }} "{{ $usuario->seccion }}"</small>
                                @endif
                                @if($usuario->rol == 'docente' && $usuario->especialidad)
                                    <br><small>{{ $usuario->especialidad }}</small>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $usuario->estado == 'activo' ? 'success' : 'secondary' }}">{{ ucfirst($usuario->estado ?? 'activo') }}</span></td>
                            <td>
                                <a href="{{ route('users.show', $usuario->id_usuario) }}" class="btn btn-sm btn-info" title="Ver"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('users.edit', $usuario->id_usuario) }}" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                @if(Auth::id() != $usuario->id_usuario)
                                <form action="{{ route('users.destroy', $usuario->id_usuario) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este usuario?')"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
