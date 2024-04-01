<!-- resources/views/report.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Tareas</title>
</head>

<body>
    <h1>Informe de Tareas</h1>
    <table>
        <thead>
            <tr>
                <th>ID Tarea</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Usuario Asignado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->user ? $task->user->name : 'Sin asignar' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
