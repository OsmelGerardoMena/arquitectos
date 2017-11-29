<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <title>Builder</title>
</head>
<body>
<table class="table">
    <thead>
        <tr>
            <th>tbDirPersonaEmpresaID_DirPersonaMiEmpresa</th>
            <th>tbDirEmpresaID_DirPersonaEmpresa</th>
            <th>DirPersonaEmpresaDepartamento</th>
            <th>DirPersonaEmpresaCategoria</th>
        </tr>
    </thead>
    <p>{{ count($rows) }}</p>
    <tbody>
        @foreach($rows as $row)
            <tr>
                <td>
                    {{ $row->tbDirPersonaEmpresaID_DirPersonaEmpresaObra }}
                </td>
                <td>
                    {{ $row->tbDirEmpresaID_DirEmpresaObra }}
                </td>
                <td>
                    {{ $row->DirPersonaObraEmpresaDepartamento }}
                </td>
                <td>
                    {{ $row->DirPersonaObraEmpresaCategoria }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>