<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Usuarios</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Listado de Usuarios - EVA</h1>
    <p>Fecha de emisión: {{ date('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha Ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $user)
            <tr>
                <td>{{ $user->id_usuario }}</td>
                <td>{{ $user->nombre }}</td>
                <td>{{ $user->correo }}</td>
                <td>{{ ucfirst($user->rol) }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
