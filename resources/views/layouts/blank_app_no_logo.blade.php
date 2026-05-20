<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($page_title) ? $page_title . ' - ' : '') . config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/tailwind/tailwind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/fontawesome/css/all.min.css') }}">
    @stack('styles')
</head>
<body class="bg-slate-100 antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
