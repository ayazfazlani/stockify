<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4 shadow-xl">
    <div class="flex h-16 shrink-0 items-center">
        <a href="{{ route('tenant.dashboard') }}" class="flex items-center space-x-3 w-full mt-6 mb-4">
             <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500">
                <i class="fas fa-cube text-lg text-white"></i>
             </div>
             <span class="text-2xl font-bold tracking-wider text-white">POSforShops</span>
        </a>
    </div>
    
    <nav class="flex flex-1 flex-col mt-4 border-t border-slate-800 pt-6">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    @feature('analytics')
                    <li>
                        <a href="{{ route('tenant.dashboard') }}" class="{{ request()->routeIs('tenant.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-chart-line h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Dashboard
                        </a>
                    </li>
                    @endfeature
                </ul>
            </li>
            
            <li>
                <div class="text-xs font-bold leading-6 text-slate-500 uppercase tracking-widest pl-2 mb-2">Inventory</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('tenant.items') }}" class="{{ request()->routeIs('tenant.items') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-boxes h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.items') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.stock-in') }}" class="{{ request()->routeIs('tenant.stock-in') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-arrow-down h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.stock-in') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Stock In
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.stock-out') }}" class="{{ request()->routeIs('tenant.stock-out') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-arrow-up h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.stock-out') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Stock Out
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.adjust') }}" class="{{ request()->routeIs('tenant.adjust') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-sliders-h h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.adjust') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Adjustments
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <div class="text-xs font-bold leading-6 text-slate-500 uppercase tracking-widest pl-2 mb-2">Financials</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('tenant.transactions') }}" class="{{ request()->routeIs('tenant.transactions') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-exchange-alt h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.transactions') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Transactions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.expenses') }}" class="{{ request()->routeIs('tenant.expenses') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-receipt h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.expenses') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Expenses
                        </a>
                    </li>
                    @feature('advanced-reports')
                    <li>
                        <a href="{{ route('tenant.purchase-orders') }}" class="{{ request()->routeIs('tenant.purchase-orders') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-file-invoice h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.purchase-orders') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Purchase Orders
                        </a>
                    </li>
                    @endfeature
                </ul>
            </li>
            
            <li class="mt-auto">
                <ul role="list" class="-mx-2 space-y-1 mb-4">
                    <li>
                        <a href="{{ route('tenant.admin', ['tenant' => tenant('id'), 'section' => 'billing']) }}" class="{{ request()->routeIs('tenant.admin') && request('section') === 'billing' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-star h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.admin') && request('section') === 'billing' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Subscription
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.admin', ['tenant' => tenant('id')]) }}" class="{{ request()->routeIs('tenant.admin') && !request('section') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-cog h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.admin') && !request('section') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Settings
                        </a>
                    </li>
                    @feature('marketplace')
                    <li>
                        <a href="{{ route('tenant.marketplace-settings') }}" class="{{ request()->routeIs('tenant.marketplace-settings') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-shop h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.marketplace-settings') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Marketplace
                        </a>
                    </li>
                    @endfeature
                </ul>

                <!-- Language Switcher -->
                <div class="px-2 pb-6 border-t border-slate-800 pt-6">
                    <div class="flex items-center justify-between text-slate-400 mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Language</span>
                        <i class="fas fa-language"></i>
                    </div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                            class="w-full flex items-center justify-between bg-slate-800 text-white px-3 py-2 rounded-lg text-xs font-bold border border-slate-700 hover:border-slate-500 transition-all">
                            <span>
                                @switch(app()->getLocale())
                                    @case('en') English @break
                                    @case('es') Español @break
                                    @case('fr') Français @break
                                    @case('ar') العربية @break
                                    @default English
                                @endswitch
                            </span>
                            <i class="fas fa-chevron-down text-[8px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                            class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-xl shadow-2xl border border-slate-100 py-2 z-50 overflow-hidden"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100">
                            
                            @foreach(['en' => 'English', 'es' => 'Español', 'fr' => 'Français', 'ar' => 'العربية'] as $code => $name)
                                <button onclick="window.location.href='{{ route('tenant.locale.update', ['locale' => $code]) }}'"
                                    class="w-full text-left px-4 py-2 text-xs font-bold transition-colors {{ app()->getLocale() === $code ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                    {{ $name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</div>
