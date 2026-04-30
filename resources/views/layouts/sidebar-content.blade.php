<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4 shadow-xl">
    <div class="flex h-16 shrink-0 items-center">
        <a href="{{ route('tenant.dashboard') }}" class="flex items-center space-x-3 w-full mt-6 mb-4">
             <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500">
                <i class="fas fa-cube text-lg text-white"></i>
             </div>
             <span class="text-2xl font-bold tracking-wider text-white">Stockify</span>
        </a>
    </div>
    
    <nav class="flex flex-1 flex-col mt-4 border-t border-slate-800 pt-6">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('tenant.dashboard') }}" class="{{ request()->routeIs('tenant.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-chart-line h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Dashboard
                        </a>
                    </li>
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
                    <li>
                        <a href="{{ route('tenant.purchase-orders') }}" class="{{ request()->routeIs('tenant.purchase-orders') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-file-invoice h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.purchase-orders') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Purchase Orders
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="mt-auto">
                <ul role="list" class="-mx-2 space-y-1 mb-4">
                    <li>
                        <a href="{{ route('tenant.subscription.manage') }}" class="{{ request()->routeIs('tenant.subscription.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-star h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.subscription.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Subscription
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.admin') }}" class="{{ request()->routeIs('tenant.admin') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2.5 text-sm leading-6 font-semibold transition-all duration-200">
                            <i class="fas fa-cog h-6 w-6 shrink-0 text-[1.1rem] flex items-center justify-center {{ request()->routeIs('tenant.admin') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
