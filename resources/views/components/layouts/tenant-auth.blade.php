<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StockFlow') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome/css/all.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        h1, h2, h3, .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased selection:bg-primary-500/30">
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-gray-50">
        <!-- Abstract Background Shapes -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        
        <div class="w-full max-w-md z-10">
            <!-- Logo area -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center space-x-2 group">
                    <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center shadow-lg transform group-hover:rotate-12 transition-all duration-300">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold font-outfit gradient-text">StockFlow</span>
                </a>
            </div>

            <!-- Main Content Card -->
            <div class="glass-card rounded-2xl p-8 md:p-10">
                {{ $slot }}
            </div>

            <!-- Footer links -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} StockFlow. All rights reserved.</p>
                <div class="mt-2 space-x-4">
                    <a href="#" class="hover:text-primary-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-primary-600 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
