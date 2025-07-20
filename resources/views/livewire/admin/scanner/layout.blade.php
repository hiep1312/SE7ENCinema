<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? "Quét QR - SE7ENCinema" }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/css/scanner.css'])
</head>
<body class="scRender">
    {{ $slot }}

    @vite('resources/js/app.js')
</body>
</html>
