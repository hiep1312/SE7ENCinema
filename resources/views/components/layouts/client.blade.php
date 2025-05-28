<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Pro Responsive HTML Template</title>
    <meta name="description" content="Movie Pro" />
    <meta name="keywords" content="Movie Pro" />
    <meta name="author" content="" />
    <meta name="MobileOptimized" content="320" />

    <title>{{ $title ?? 'SE7ENCinema' }}</title>
    @vite('resources/css/app.css', 'resources/js/app.js')
</head>

<body>
    {{ $slot }}
</body>

</html>
