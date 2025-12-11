<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Rendimiento</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte de Rendimiento Académico</h1>
    <p>Fecha de emisión: {{ date('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Materia</th>
                <th>L1</th>
                <th>L2</th>
                <th>L3</th>
                <th>Def.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $registro)
            <tr>
                <td>{{ $registro->estudiante->nombre ?? 'N/A' }}</td>
                <td>{{ $registro->materia->nombre ?? 'N/A' }}</td>
                <td>{{ $registro->l1 }}</td>
                <td>{{ $registro->l2 }}</td>
                <td>{{ $registro->l3 }}</td>
                <td>{{ $registro->promedio }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
