@php
    $tenantId = tenancy()->initialized ? tenant('slug') : null;
    $routePrefix = tenancy()->initialized ? 'tenant.' : '';
@endphp

<div class="md:hidden fixed left-1/2 -translate-x-1/2 w-[92%] max-w-sm bg-white/98 backdrop-blur-md border border-gray-200/50 shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-[2rem] z-[5000] px-2 py-3 flex items-center justify-around"
     style="bottom: calc(1.5rem + env(safe-area-inset-bottom, 0px)) !important; top: auto !important;">
    <!-- Dashboard -->
    <a href="{{ $tenantId ? route('tenant.dashboard', ['tenant' => $tenantId]) : route('dashboard') }}" 
       class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs(['*dashboard']) ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-blue-600' }}">
        <i class='bx bx-grid-alt text-2xl'></i>
    </a>

    <!-- Item List -->
    <a href="{{ $tenantId ? route('tenant.items', ['tenant' => $tenantId]) : '#' }}" 
       class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs(['*items']) ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-blue-600' }}">
        <i class='bx bx-list-ol text-2xl'></i>
    </a>

    <!-- Stock In -->
    <a href="{{ $tenantId ? route('tenant.stock-in', ['tenant' => $tenantId]) : route('stock-in') }}" 
       class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs(['*stock-in']) ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-blue-600' }}">
        <i class='bx bx-plus-circle text-2xl'></i>
    </a>

    <!-- Stock Out -->
    <a href="{{ $tenantId ? route('tenant.stock-out', ['tenant' => $tenantId]) : route('stock-out') }}" 
       class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs(['*stock-out']) ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-blue-600' }}">
        <i class='bx bx-minus-circle text-2xl'></i>
    </a>

    <!-- Marketplace -->
    <a href="{{ $tenantId ? route('tenant.marketplace-settings', ['tenant' => $tenantId]) : '#' }}" 
       class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs(['*marketplace*']) ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-blue-600' }}">
        <i class='bx bx-store-alt text-2xl'></i>
    </a>
</div>