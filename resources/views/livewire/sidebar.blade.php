<div>
    <nav class="sidebar max-sm:fixed  md:block z-10 border-r border-gray-300">
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
            @if(auth()->check() && auth()->user()->accessibleTeams()->where('tenant_id', tenant('id'))->count() > 1)
            <div class="px-5 py-4 border-b border-gray-200">
                <span class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Switch Store</span>
                @livewire('team-switcher')
            </div>
            @endif
        @endfeature

        <div class="menu-bar">
            <div class="menu">

                @php
                    $routePrefix = tenancy()->initialized ? 'tenant.' : '';
                @endphp
                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'items')}}"
                        class="{{ request()->routeIs($routePrefix . 'items') ? 'active' : '' }}">
                        <i class='bx bx-list-ol icon'></i>
                        <span class="text nav-text">Item List</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'stock-in')}}"
                        class="{{ request()->routeIs($routePrefix . 'stock-in') ? 'active' : '' }}">
                        <i class='bx bx-cart icon'></i>
                        <span class="text nav-text">Stock In</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'stock-out')}}"
                        class="{{ request()->routeIs($routePrefix . 'stock-out') ? 'active' : '' }}">
                        <i class='bx bx-cart-alt icon'></i>
                        <span class="text nav-text">Stock Out</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'adjust')}}"
                        class="{{ request()->routeIs($routePrefix . 'adjust') ? 'active' : '' }}">
                        <i class='bx bx-adjust icon'></i>
                        <span class="text nav-text">Adjust</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'transactions')}}"
                        class="{{ request()->routeIs($routePrefix . 'transactions') ? 'active' : '' }}">
                        <i class='bx bx-credit-card icon'></i>
                        <span class="text nav-text">Transactions</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'expenses')}}"
                        class="{{ request()->routeIs($routePrefix . 'expenses') ? 'active' : '' }}">
                        <i class='bx bx-wallet icon'></i>
                        <span class="text nav-text">Expenses</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'purchase-orders')}}"
                        class="{{ request()->routeIs($routePrefix . 'purchase-orders') ? 'active' : '' }}">
                        <i class='bx bx-package icon'></i>
                        <span class="text nav-text">Purchase Orders</span>
                    </a>
                </li>

                @feature('analytics')
                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'analytics')}}"
                        class="{{ request()->routeIs($routePrefix . 'analytics') ? 'active' : '' }}">
                        <i class='bx bx-bar-chart-square icon'></i>
                        <span class="text nav-text">Analytics</span>
                    </a>
                </li>
                @endfeature

                @feature('advanced-reports')
                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'summary')}}"
                        class="{{ request()->routeIs($routePrefix . 'summary') ? 'active' : '' }}">
                        <i class='bx bx-file icon'></i>
                        <span class="text nav-text">Summary</span>
                    </a>
                </li>
                @endfeature

                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'dashboard')}}"
                        class="{{ request()->routeIs($routePrefix . 'dashboard') ? 'active' : '' }}">
                        <i class='bx bx-grid-alt icon'></i>
                        <span class="text nav-text">Dashboard</span>
                    </a>
                </li>

                @feature('custom-roles')
                @if(auth()->check() && (auth()->user()->isStoreAdmin() || auth()->user()->isSuperAdmin() || tenant('owner_id') === auth()->id()))
                <li class="nav-link">
                    <a wire:navigate href="{{ route($routePrefix . 'user')}}"
                        class="{{ request()->routeIs($routePrefix . 'user') ? 'active' : '' }}">
                        <i class='bx bx-user-circle icon'></i>
                        <span class="text nav-text">Users</span>
                    </a>
                </li>
                @endif
                @endfeature

            </div>

            {{-- <div class="bottom-content">
                <li class="">
                    <a href="#">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>

            </div> --}}
        </div>

    </nav>
</div>

@script
<script>
    const body = document.querySelector('body'),
        sidebar = body.querySelector('nav'),
        toggle = body.querySelector(".toggle"),
        searchBtn = body.querySelector(".search-box"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");

    // Load sidebar state from localStorage
    const sidebarState = localStorage.getItem('sidebarState');
    if (sidebarState === 'closed') {
        sidebar.classList.add('close');
    }

    // Toggle sidebar state and save to localStorage
    toggle.addEventListener("click", () => {
        sidebar.classList.toggle("close");
        const isClosed = sidebar.classList.contains("close");
        localStorage.setItem('sidebarState', isClosed ? 'closed' : 'open');
    });

    // Ensure the sidebar opens when search is clicked
    searchBtn.addEventListener("click", () => {
        sidebar.classList.remove("close");
        localStorage.setItem('sidebarState', 'open'); // Save open state to localStorage
    });

    // Toggle dark mode
    modeSwitch.addEventListener("click", () => {
        body.classList.toggle("dark");

        if (body.classList.contains("dark")) {
            modeText.innerText = "Light mode";
        } else {
            modeText.innerText = "Dark mode";
        }
    });

    document.addEventListener("click", function () {
        const sidebar = document.querySelector(".sidebar");
        const toggle = document.querySelector(".toggle");

        if (!sidebar || !toggle) return;

        // Load sidebar state from localStorage
        if (localStorage.getItem("sidebarState") === "closed") {
            sidebar.classList.add("close");
        }

        // Toggle sidebar state and save to localStorage
        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            localStorage.setItem("sidebarState", sidebar.classList.contains("close") ? "closed" : "open");
        });

        // Highlight active link based on the current URL
        const links = document.querySelectorAll(".menu-links a");
        links.forEach(link => {
            if (window.location.href.includes(link.href)) {
                link.classList.add("active"); // Add an 'active' class for styling
            }
        });
    });
</script>
@endscript