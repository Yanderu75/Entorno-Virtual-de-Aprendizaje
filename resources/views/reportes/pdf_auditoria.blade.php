<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Auditoría y Seguridad</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; color: #333; }
        .details { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Reporte de Auditoría y Seguridad</h1>
    <div class="details">
        <p><strong>Fecha de Generación:</strong> {{ date('d/m/Y H:i') }}</p>
        <p><strong>Generado por:</strong> {{ Auth::user()->nombre }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acción</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ $log->usuario->nombre ?? 'Sistema' }}</td>
                <td>{{ $log->usuario->rol ?? '-' }}</td>
                <td>{{ $log->accion }}</td>
                <td>{{ $log->ip }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
