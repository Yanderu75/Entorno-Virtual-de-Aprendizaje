<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Materias y Asignaciones</title>
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
    <h1>Reporte de Materias y Asignaciones</h1>
    <div class="details">
        <p><strong>Fecha de Generación:</strong> {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Materia</th>
                <th>Grado/Sección</th>
                <th>Docente Asignado</th>
                <th>N° Estudiantes</th>
                <th>Horario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $materia)
            <tr>
                <td>{{ $materia->nombre }}</td>
                <td>{{ $materia->grado }} "{{ $materia->seccion }}"</td>
                <td>{{ $materia->docente->nombre ?? 'SIN ASIGNAR' }}</td>
                <td>{{ $materia->estudiantes->count() }} inscritos</td>
                <td>{{ $materia->horario ?? 'No definido' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
