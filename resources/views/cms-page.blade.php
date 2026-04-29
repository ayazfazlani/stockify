<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->effective_meta_title }}</title>
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if($page->canonical_url)
        <link rel="canonical" href="{{ $page->canonical_url }}">
    @else
        <link rel="canonical" href="{{ url('/' . $page->slug) }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
    @if($page->meta_description)
    <meta property="og:description" content="{{ $page->meta_description }}">@endif
    <meta property="og:url" content="{{ url('/' . $page->slug) }}">
    <meta property="og:type" content="website">
    @if($page->og_image)
    <meta property="og:image" content="{{ asset('storage/' . $page->og_image) }}">@endif

    {{-- Schema --}}
    @if($page->schema_markup)
        <script type="application/ld+json">{!! json_encode($page->schema_markup, JSON_UNESCAPED_SLASHES) !!}</script>
    @elseif($globalSchema)
        <script type="application/ld+json">{!! json_encode($globalSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    @endif

    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/fontawesome/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e' },
                        accent: { 400: '#34d399', 500: '#10b981', 600: '#059669' },
                        indigo: { 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5' }
                    },
                    fontFamily: { 'sans': ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-white">
    {{-- Navigation (same as welcome page) --}}
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        <div class="rounded-lg bg-gradient-to-r from-primary-500 to-indigo-500 p-2 mr-3">
                            <i class="fas fa-boxes text-white text-xl"></i>
                        </div>
                        <span class="font-bold text-2xl bg-gradient-to-r from-primary-600 to-indigo-600 bg-clip-text text-transparent">StockFlow</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('welcome') }}#features" class="text-gray-800 font-medium hover:text-primary-600 transition-colors">Features</a>
                    <a href="{{ route('welcome') }}#pricing" class="text-gray-800 font-medium hover:text-primary-600 transition-colors">Pricing</a>
                    <a href="{{ url('/blog') }}" class="text-gray-800 font-medium hover:text-primary-600 transition-colors">Blog</a>
                    <a href="{{ route('find-store') }}" class="text-gray-800 font-medium hover:text-primary-600 transition-colors">Login</a>
                    <a href="{{ route('tenant.register.post') }}" class="bg-gradient-to-r from-primary-500 to-indigo-500 text-white px-5 py-2.5 rounded-lg font-medium hover:from-primary-600 hover:to-indigo-600 transition-all shadow-lg">Start Free Trial</a>
                </div>
            </div>
        </div>
    </nav> -->

    {{-- Page Content --}}
    <main class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-8 text-gray-900">{{ $page->title }}</h1>
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! $page->body !!}
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <!-- <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} StockFlow. All rights reserved.</p>
        </div>
    </footer> -->
    </body>

</html>