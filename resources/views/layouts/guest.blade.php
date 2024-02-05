<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    {{ Vite::useHotFile(public_path('hailo.hot'))
            ->useBuildDirectory('hailo')
            ->withEntryPoints(['resources/css/hailo.css', 'resources/js/hailo.js']) }}
</head>
<body class="h-full">
<div class="font-sans text-gray-900 antialiased">
    {{ $slot }}
</div>
</body>
</html>
