<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Stockify') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 overflow-hidden">
    <div x-data="{ sidebarOpen: false, profileOpen: false }" class="h-full w-full">
    <!-- Mobile sidebar offcanvas -->
    <div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-description="Off-canvas menu for mobile" role="dialog" aria-modal="true" style="display: none;">
        <!-- Backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/80"></div>

        <div class="fixed inset-0 flex">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in-out duration-300 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 class="relative mr-16 flex w-full max-w-xs flex-1">
                
                <!-- Close button -->
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                    <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5 text-white hover:text-slate-300">
                        <span class="sr-only">Close sidebar</span>
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Mobile Sidebar Content -->
                @include('layouts.sidebar-content')
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col shadow-xl">
        @include('layouts.sidebar-content')
    </div>

    <!-- Main Content Area Wrapper -->
    <div class="lg:pl-72 flex flex-col h-screen">
        
        <!-- Top Navigation Header -->
        <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <div class="flex items-center gap-x-4">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-slate-700 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="h-6 w-px bg-slate-200 lg:hidden" aria-hidden="true"></div>
                <div class="hidden lg:block text-slate-500 font-medium text-sm">
                    {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                </div>
            </div>

            <div class="flex items-center gap-x-4 lg:gap-x-6">
                <!-- Team Switcher -->
                @auth
                    @if(auth()->user()->teams && auth()->user()->teams->count() > 1)
                        <div class="hidden sm:block">
                            @livewire('team-switcher')
                        </div>
                    @endif
                @endauth

                <button type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-slate-500 relative">
                    <span class="sr-only">View notifications</span>
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <!-- Separator -->
                <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200" aria-hidden="true"></div>

                <!-- Profile dropdown -->
                @auth
                <div class="relative" x-data="{ profileOpen: false }">
                    <button type="button" @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="-m-1.5 flex items-center p-1.5" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden lg:flex lg:items-center pl-3">
                            <span class="text-sm font-semibold leading-6 text-slate-900" aria-hidden="true">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-slate-400 text-xs transition-transform duration-200" :class="{ 'rotate-180': profileOpen }"></i>
                        </span>
                    </button>
                    
                    <div x-show="profileOpen" style="display: none;" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2.5 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-slate-900/5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        
                        <div class="px-4 py-2 border-b border-slate-100 mb-1 lg:hidden">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <a href="#" class="block px-4 py-2 text-sm leading-6 text-slate-700 hover:bg-slate-50" role="menuitem" tabindex="-1">
                            <i class="fas fa-user mr-2 text-slate-400 text-center w-4"></i> Profile
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-6 text-red-600 hover:bg-slate-50 font-medium" role="menuitem" tabindex="-1">
                                <i class="fas fa-sign-out-alt mr-2 text-red-500 text-center w-4"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto w-full h-[calc(100vh-4rem)] bg-slate-50">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>