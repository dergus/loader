<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Downloads</title>
</head>
<body>
<a href="/downloads/create">add</a>
<br>
<table>
    <tr>
        <th>URL</th>
        <th>STATUS</th>
        <th>DOWNLOAD</th>
    </tr>
    @foreach ($downloads as $download)
        <tr>
            <td>{{ $download->url }}</td>
            <td>{{ $download->statusString() }}</td>
            <td> <a href="{{ $download->downloadLink() }}">{{ $download->downloadLink() }}</a> </td>
        </tr>
    @endforeach
</table>
</body>
</html>
