@php
    $tenantId = tenancy()->initialized ? tenant('slug') : null;
    $routePrefix = tenancy()->initialized ? 'tenant.' : '';
@endphp

<div class="sf-bottom-nav">
    <!-- Dashboard -->
    <a href="{{ $tenantId ? route('tenant.dashboard', ['tenant' => $tenantId]) : route('dashboard') }}"
        class="sf-bottom-nav-item {{ request()->routeIs(['*dashboard']) ? 'active' : '' }}">
        <i class='bx bx-grid-alt'></i>
        <span class="sf-bottom-nav-label">Home</span>
    </a>

    <!-- Item List -->
    <a href="{{ $tenantId ? route('tenant.items', ['tenant' => $tenantId]) : '#' }}"
        class="sf-bottom-nav-item {{ request()->routeIs(['*items']) ? 'active' : '' }}">
        <i class='bx bx-list-ol'></i>
        <span class="sf-bottom-nav-label">Items</span>
    </a>

    <!-- Stock In -->
    <a href="{{ $tenantId ? route('tenant.stock-in', ['tenant' => $tenantId]) : route('stock-in') }}"
        class="sf-bottom-nav-item {{ request()->routeIs(['*stock-in']) ? 'active' : '' }}">
        <i class='bx bx-plus-circle'></i>
        <span class="sf-bottom-nav-label">Stock In</span>
    </a>

    <!-- Stock Out -->
    <a href="{{ $tenantId ? route('tenant.stock-out', ['tenant' => $tenantId]) : route('stock-out') }}"
        class="sf-bottom-nav-item {{ request()->routeIs(['*stock-out']) ? 'active' : '' }}">
        <i class='bx bx-minus-circle'></i>
        <span class="sf-bottom-nav-label">Stock Out</span>
    </a>

    <!-- Marketplace -->
    <a href="{{ $tenantId ? route('tenant.marketplace-settings', ['tenant' => $tenantId]) : '#' }}"
        class="sf-bottom-nav-item {{ request()->routeIs(['*marketplace*']) ? 'active' : '' }}">
        <i class='bx bx-store-alt'></i>
        <span class="sf-bottom-nav-label">Market</span>
    </a>
</div>

<style>
    /* ============================================
   Bottom Navigation - BoxHero Style
   Sharp corners (2-4px)
   ============================================ */
    .sf-bottom-nav {
        position: fixed;
        bottom: calc(0rem + env(safe-area-inset-bottom, 0px));
        left: 0;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: space-around;
        background: #FFFFFF;
        border-top: 1px solid #E8EAF0;
        padding: 8px 16px;
        z-index: 5000;
        box-shadow: 0 -2px 10px rgba(15, 17, 23, .04);
    }

    .sf-bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 8px 12px;
        border-radius: 4px;
        transition: all .2s ease;
        text-decoration: none;
        color: #9CA3B8;
        min-width: 56px;
    }

    .sf-bottom-nav-item i {
        font-size: 22px;
        transition: all .2s ease;
    }

    .sf-bottom-nav-label {
        font-size: 10px;
        font-weight: 500;
        transition: all .2s ease;
    }

    /* Active State */
    .sf-bottom-nav-item.active {
        background: #EEF1FD;
        color: #4361EE;
    }

    .sf-bottom-nav-item.active i {
        transform: translateY(-2px);
    }

    /* Hover State (Desktop) */
    @media (min-width: 769px) {
        .sf-bottom-nav {
            display: none;
        }
    }

    /* Mobile Hover Effects */
    @media (max-width: 768px) {
        .sf-bottom-nav-item {
            flex: 1;
            padding: 6px 8px;
            min-width: auto;
        }

        .sf-bottom-nav-item:active {
            transform: scale(0.95);
            background: #EEF1FD;
        }

        .sf-bottom-nav-label {
            font-size: 9px;
        }

        .sf-bottom-nav-item i {
            font-size: 20px;
        }
    }

    /* Dark Mode Support */
    body.dark .sf-bottom-nav {
        background: #1E1E2E;
        border-top-color: #2D2D3D;
    }

    body.dark .sf-bottom-nav-item {
        color: #6B6B8D;
    }

    body.dark .sf-bottom-nav-item.active {
        background: #363648;
        color: #4361EE;
    }

    body.dark .sf-bottom-nav-item:hover {
        background: #2A2A3A;
    }

    /* Safe Area Support for Notch Devices */
    @supports (padding-bottom: env(safe-area-inset-bottom)) {
        .sf-bottom-nav {
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
        }
    }
</style>