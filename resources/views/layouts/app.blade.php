<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#1e40af">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="@yield('meta_description', 'POS for Shops — Simple inventory and sales tracking for small retail businesses in Pakistan')">

    <title>@yield('title', 'Dashboard') — POS for Shops</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Nastaliq+Urdu&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        input,
        select,
        textarea {
            font-size: 16px;
        }

        @media (min-width: 768px) {

            input,
            select,
            textarea {
                font-size: 14px;
            }
        }

        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .safe-top {
            padding-top: env(safe-area-inset-top, 0px);
        }

        @media (min-width: 768px) {
            .card-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.1);
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-enter {
            animation: slideInRight 0.3s ease-out;
        }

        .toast-exit {
            animation: slideOutRight 0.3s ease-in;
        }

        [dir="rtl"] .sidebar {
            right: 0;
            left: auto;
        }

        [dir="rtl"] .main-content {
            margin-right: 16rem;
            margin-left: 0;
        }

        [dir="rtl"] .ml-auto {
            margin-right: auto;
            margin-left: 0;
        }

        [dir="rtl"] .mr-auto {
            margin-left: auto;
            margin-right: 0;
        }

        [dir="rtl"] .text-left {
            text-align: right;
        }

        [dir="rtl"] .text-right {
            text-align: left;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ mobileMenu: false, toast: [] }" x-init="
    window.addEventListener('toast', e => {
        const id = Date.now();
        toast.push({ id, ...e.detail });
        setTimeout(() => toast = toast.filter(t => t.id !== id), 4000);
    });
">

    <!-- Toast Container -->
    <div class="fixed top-4 right-4 z-[60] space-y-2 w-full max-w-sm px-4 md:px-0" dir="ltr">
        <template x-for="t in toast" :key="t.id">
            <div x-show="true" x-transition:enter="toast-enter" x-transition:leave="toast-exit" :class="{
                     'bg-green-50 border-green-400 text-green-800': t.type === 'success',
                     'bg-red-50 border-red-400 text-red-800': t.type === 'error',
                     'bg-blue-50 border-blue-400 text-blue-800': t.type === 'info'
                 }" class="rounded-lg border-l-4 p-4 shadow-lg backdrop-blur-sm bg-opacity-95">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg x-show="t.type === 'success'" class="h-5 w-5 text-green-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="t.type === 'error'" class="h-5 w-5 text-red-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" x-text="t.message"></p>
                    </div>
                    <button @click="toast = toast.filter(item => item.id !== t.id)"
                        class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Header -->
        <div
            class="md:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40 safe-top">
            <button @click="mobileMenu = !mobileMenu" class="p-2 -ml-2 rounded-lg hover:bg-gray-100">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="/images/logo.sv" alt="POS for Shops" class="h-8">
            </a>
            <div class="w-8"></div>
        </div>

        <!-- Sidebar -->
        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="sidebar fixed md:sticky top-0 left-0 z-50 md:z-30 h-screen w-64 bg-slate-900 text-white flex flex-col transition-transform duration-300 md:transition-none">

            <div class="md:hidden flex justify-end p-4">
                <button @click="mobileMenu = false" class="p-2 rounded-lg hover:bg-slate-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight">POS for Shops</h1>
                        <p class="text-xs text-slate-400">Inventory Made Simple</p>
                    </div>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @livewire('sidebar')
            </nav>

            <div class="p-4 border-t border-slate-800">
                @auth
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff' }}"
                            class="w-9 h-9 rounded-full border-2 border-slate-700">
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    @php
                        $tenant = tenant();
                        $plan = $tenant?->plan;
                    @endphp

                    @if($plan)
                        <div class="bg-slate-800 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-slate-300">{{ $plan->name }} Plan</span>
                                <span
                                    class="text-xs text-blue-400">{{ $tenant->subscription('default')?->onTrial() ? 'Trial' : 'Active' }}</span>
                            </div>
                            <a href="{{ route('subscription-manager') }}"
                                class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Upgrade Plan
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </aside>

        <div x-show="mobileMenu" @click="mobileMenu = false"
            class="md:hidden fixed inset-0 bg-black/50 z-40 backdrop-blur-sm" x-transition.opacity></div>

        <!-- Main Content -->
        <main class="main-content flex-1 min-w-0">
            <header
                class="hidden md:flex items-center justify-between bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-30">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Search items, orders..."
                            class="pl-10 pr-4 py-2 bg-gray-100 border-0 rounded-lg text-sm w-64 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    </div>

                    <button class="relative p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    @livewire('team-switcher')
                </div>
            </header>

            <div class="p-4 md:p-8 pb-24 md:pb-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Mobile Bottom Navigation (FoodiesPakistan Style) -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 safe-bottom pb-safe">
            <div class="flex justify-around items-center h-16">
                <a href="{{ route('dashboard') }}"
                    class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-[10px] font-medium">Dashboard</span>
                </a>
                <a href="{{ route('items.index') }}"
                    class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('items.*') ? 'text-blue-600' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-[10px] font-medium">Items</span>
                </a>

                <div class="relative -top-4">
                    <button @click="$dispatch('open-quick-actions')"
                        class="w-14 h-14 bg-blue-600 rounded-full shadow-lg shadow-blue-600/40 flex items-center justify-center text-white hover:bg-blue-700 active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('stock-in') }}"
                    class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('stock-in') ? 'text-blue-600' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <span class="text-[10px] font-medium">Stock</span>
                </a>
                <a href="{{ route('transactions') }}"
                    class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('transactions') ? 'text-blue-600' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-[10px] font-medium">History</span>
                </a>
            </div>
        </nav>
    </div>

    @livewireScripts
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: '{{ session('success') }}' } }));
            @endif
            @if(session('error'))
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: '{{ session('error') }}' } }));
            @endif
        });
    </script>
</body>

</html>