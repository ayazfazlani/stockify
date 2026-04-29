<div>
    <nav class="sidebar max-sm:fixed md:block z-10 border-r border-gray-300">
        <header>
            <div class="image-text">
                <span class="image">
                    {{-- <img src="icon.png" alt=""> --}}
                </span>

                <div class="text logo-text">
                    <span class="name">GENERAL</span>
                    <span class="profession">TRADING</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        @feature('multi-store')
        @if(auth()->check() && auth()->user()->accessibleTeams()->where('tenant_', tenant('id'))->count() > 1)
            <div class="px-5 py-4 border-b border-gray-200">
                <span class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Switch Store</span>
                @livewire('team-switcher')
            </div>
        @endif
        @endfeature

        <div class="menu-bar">
            <div class="menu">

                @php
                    $tenantId = tenancy()->initialized ? tenant('slug') : null;
                    $routePrefix = tenancy()->initialized ? 'tenant.' : '';
                @endphp

                @can('view items')
                    <li class="nav-link">
                        {{-- Only show the link if we have a tenant ID, or use a default route --}}
                        <a wire:navigate href="{{ $tenantId ? route('tenant.items', ['tenant' => $tenantId]) : '#' }}"
                            class="{{ request()->routeIs('tenant.items') ? 'active' : '' }}">
                            <i class='bx bx-list-ol icon'></i>
                            <span class="text nav-text">Item List</span>
                        </a>
                    </li>
                @endcan

                @can('manage stock')
                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.stock-in', ['tenant' => $tenantId]) : route('stock-in') }}"
                            class="{{ request()->routeIs($routePrefix . 'stock-in') ? 'active' : '' }}">
                            <i class='bx bx-cart icon'></i>
                            <span class="text nav-text">Stock In</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.stock-out', ['tenant' => $tenantId]) : route('stock-out') }}"
                            class="{{ request()->routeIs($routePrefix . 'stock-out') ? 'active' : '' }}">
                            <i class='bx bx-cart-alt icon'></i>
                            <span class="text nav-text">Stock Out</span>
                        </a>
                    </li>
                @endcan

                @can('manage stock')
                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.adjust', ['tenant' => $tenantId]) : route('adjust') }}"
                            class="{{ request()->routeIs($routePrefix . 'adjust') ? 'active' : '' }}">
                            <i class='bx bx-adjust icon'></i>
                            <span class="text nav-text">Adjust</span>
                        </a>
                    </li>
                @endcan

                @can('view transactions')
                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.transactions', ['tenant' => $tenantId]) : route('transactions') }}"
                            class="{{ request()->routeIs($routePrefix . 'transactions') ? 'active' : '' }}">
                            <i class='bx bx-credit-card icon'></i>
                            <span class="text nav-text">Transactions</span>
                        </a>
                    </li>
                @endcan

                @can('view expenses')
                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.expenses', ['tenant' => $tenantId]) : route('expenses') }}"
                            class="{{ request()->routeIs($routePrefix . 'expenses') ? 'active' : '' }}">
                            <i class='bx bx-wallet icon'></i>
                            <span class="text nav-text">Expenses</span>
                        </a>
                    </li>
                @endcan

                @can('view purchase orders')
                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.purchase-orders', ['tenant' => $tenantId]) : route('purchase-orders') }}"
                            class="{{ request()->routeIs($routePrefix . 'purchase-orders') ? 'active' : '' }}">
                            <i class='bx bx-package icon'></i>
                            <span class="text nav-text">Purchase Orders</span>
                        </a>
                    </li>
                @endcan

                @feature('analytics')
                <li class="nav-link">
                    <a wire:navigate
                        href="{{ $tenantId ? route('tenant.analytics', ['tenant' => $tenantId]) : route('analytics') }}"
                        class="{{ request()->routeIs($routePrefix . 'analytics') ? 'active' : '' }}">
                        <i class='bx bx-bar-chart-square icon'></i>
                        <span class="text nav-text">Analytics</span>
                    </a>
                </li>
                @endfeature

                @feature('advanced-reports')
                <li class="nav-link">
                    <a wire:navigate
                        href="{{ $tenantId ? route('tenant.summary', ['tenant' => $tenantId]) : route('summary') }}"
                        class="{{ request()->routeIs($routePrefix . 'summary') ? 'active' : '' }}">
                        <i class='bx bx-file icon'></i>
                        <span class="text nav-text">Summary</span>
                    </a>
                </li>
                @endfeature

                <li class="nav-link">
                    <a wire:navigate
                        href="{{ $tenantId ? route('tenant.dashboard', ['tenant' => $tenantId]) : route('dashboard') }}"
                        class="{{ request()->routeIs($routePrefix . 'dashboard') ? 'active' : '' }}">
                        <i class='bx bx-grid-alt icon'></i>
                        <span class="text nav-text">Dashboard</span>
                    </a>
                </li>

                @can('manage team members')
                    <li class="nav-link">
                        <a wire:navigate
                            href="{{ $tenantId ? route('tenant.user', ['tenant' => $tenantId]) : route('user') }}"
                            class="{{ request()->routeIs($routePrefix . 'user') ? 'active' : '' }}">
                            <i class='bx bx-user-circle icon'></i>
                            <span class="text nav-text">Users & Roles</span>
                        </a>
                    </li>
                @endcan

            </div>
        </div>
    </nav>
</div>

@script
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.querySelector('body'),
            sidebar = body.querySelector('nav'),
            toggle = body.querySelector(".toggle"),
            searchBtn = body.querySelector(".search-box"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");

        // Load sidebar state from localStorage
        const sidebarState = localStorage.getItem('sidebarState');
        if (sidebarState === 'closed' && sidebar) {
            sidebar.classList.add('close');
        }

        // Toggle sidebar state and save to localStorage
        if (toggle) {
            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("close");
                const isClosed = sidebar.classList.contains("close");
                localStorage.setItem('sidebarState', isClosed ? 'closed' : 'open');
            });
        }

        // Ensure the sidebar opens when search is clicked
        if (searchBtn) {
            searchBtn.addEventListener("click", () => {
                sidebar.classList.remove("close");
                localStorage.setItem('sidebarState', 'open');
            });
        }

        // Toggle dark mode
        if (modeSwitch) {
            modeSwitch.addEventListener("click", () => {
                body.classList.toggle("dark");

                if (body.classList.contains("dark")) {
                    modeText.innerText = "Light mode";
                } else {
                    modeText.innerText = "Dark mode";
                }
            });
        }

        // Highlight active link based on the current URL
        const links = document.querySelectorAll(".menu-links a");
        links.forEach(link => {
            if (window.location.href.includes(link.href)) {
                link.classList.add("active");
            }
        });
    });
</script>
@endscript