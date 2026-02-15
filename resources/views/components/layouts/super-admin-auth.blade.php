<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Stockify') }} - Super Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side (Image) -->
        <div class="hidden lg:flex lg:w-1/2 bg-indigo-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/90 to-indigo-900/90"></div>
            <img src="{{ asset('bi.svg') }}" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay" alt="Background">
            <div class="relative z-10 flex flex-col justify-center px-12">
                <div class="max-w-md">
                    <h2 class="text-3xl font-bold text-white mb-6">Welcome to Stockify Admin</h2>
                    <p class="text-indigo-100">Manage your entire SaaS platform, monitor subscriptions, and analyze growth from one central dashboard.</p>
                </div>
            </div>
        </div>

        <!-- Right Side (Content) -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>