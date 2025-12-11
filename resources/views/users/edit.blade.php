@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario: {{ $usuario->nombre }}</h1>
@stop

@section('main_content_body')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos del Usuario</h3>
            </div>
            <form action="{{ route('users.update', $usuario->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}" required>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula" value="{{ $usuario->cedula }}" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="{{ $usuario->correo }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="estudiante" {{ $usuario->rol == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                            <option value="docente" {{ $usuario->rol == 'docente' ? 'selected' : '' }}>Docente</option>
                            <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    <div class="form-group" id="especialidad-group" style="{{ $usuario->rol == 'docente' ? '' : 'display:none;' }}">
                        <label for="especialidad">Especialidad (Solo Docentes)</label>
                        <select class="form-control" name="especialidad" id="especialidad">
                            <option value="">Seleccione Especialidad</option>
                            @foreach(['Castellano', 'Matemáticas', 'Inglés', 'GHC (Geografía, Historia)', 'Arte y Patrimonio', 'Educación Física', 'Ciencias Naturales', 'Biología', 'Física', 'Química', 'Soberanía', 'Grupo de Recreación', 'General'] as $e)
                                <option value="{{ $e }}" {{ (old('especialidad') ?? $usuario->especialidad) == $e ? 'selected' : '' }}>{{ $e }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="estudiante-group" style="{{ $usuario->rol == 'estudiante' ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label for="grado">Grado / Año</label>
                            <select class="form-control" id="grado" name="grado">
                                <option value="">Seleccione Grado</option>
                                @foreach(['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'] as $g)
                                    <option value="{{ $g }}" {{ $usuario->grado == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="seccion">Sección</label>
                            <select class="form-control" id="seccion" name="seccion">
                                <option value="">Seleccione Sección</option>
                                @foreach(['A', 'B', 'C', 'D', 'E', 'U'] as $s)
                                    <option value="{{ $s }}" {{ $usuario->seccion == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <script>
                        document.getElementById('rol').addEventListener('change', function() {
                            var especialidadGroup = document.getElementById('especialidad-group');
                            var estudianteGroup = document.getElementById('estudiante-group');
                            
                            especialidadGroup.style.display = 'none';
                            estudianteGroup.style.display = 'none';

                            if (this.value === 'docente') {
                                especialidadGroup.style.display = 'block';
                            } else if (this.value === 'estudiante') {
                                estudianteGroup.style.display = 'block';
                            }
                        });
                    </script>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="activo" {{ $usuario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="bloqueado" {{ $usuario->estado == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contraseña">Contraseña (Dejar en blanco para no cambiar)</label>
                        <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Nueva contraseña">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="{{ route('users.index') }}" class="btn btn-default float-right">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
