<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add download</title>
</head>
<body>
<h3>Add URL to download</h3>

@if ($errors->any())
    <div>
        ERROR: {{ $errors->first() }}
    </div>
    <br>
@endif

<form method="post" action="/downloads">
    @csrf
    <input type="text" name="url">
    <button type="submit">add</button>
</form>
</body>
</html>
