@extends('layouts.app')

@section('title', 'Respaldos de Base de Datos')

@section('main_content_body')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Respaldo y Restauración</h3>
            </div>
            <div class="card-body text-center">
                <p class="lead">Generar copia de seguridad de la Base de Datos</p>
                <div class="alert alert-info text-left">
                    <i class="fas fa-info-circle"></i> <strong>Nota:</strong>
                    <ul>
                        <li>Se descargará un archivo <code>.sql</code>.</li>
                        <li>Este archivo contiene toda la estructura y datos del sistema.</li>
                        <li>Guárdalo en un lugar seguro.</li>
                    </ul>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('respaldos.create') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-app bg-success">
                        <i class="fas fa-database"></i> Generar Respaldo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
