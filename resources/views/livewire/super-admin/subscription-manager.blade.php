<div>
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-green-800">{{ session('message') }}</span>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Subscription Management</h1>
            <p class="text-gray-600 mt-2">Manage your subscription plans and user subscriptions</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Plans</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_plans'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Subscriptions</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['active_subscriptions'] }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['monthly_revenue'], 2)
                            }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Yearly Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['yearly_revenue'], 2)
                            }}
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px space-x-8">
                    <button wire:click="$set('activeTab', 'user_plans')" class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm 
                               {{ $activeTab === 'user_plans' 
                                  ? 'border-blue-500 text-blue-600' 
                                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Subscription Plans
                    </button>

                    @if(auth()->user()->isAdmin())
                    <button wire:click="$set('activeTab', 'admin_plans')" class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm 
                               {{ $activeTab === 'admin_plans' 
                                  ? 'border-blue-500 text-blue-600' 
                                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Manage Plans
                    </button>

                    <button wire:click="$set('activeTab', 'admin_subscriptions')" class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm 
                               {{ $activeTab === 'admin_subscriptions' 
                                  ? 'border-blue-500 text-blue-600' 
                                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.136V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14.136a.25.25 0 00.375.217L12 17l9.625 5.353a.25.25 0 00.375-.217z" />
                        </svg>
                        Manage Subscriptions
                    </button>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'user_plans')
        <!-- User Plans Tab -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- User Subscription Info -->
            @if($userSubscription)
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Current Subscription</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $userSubscription->plan->name }} •
                            ${{ number_format($userSubscription->plan->price, 2) }}/{{ $userSubscription->plan->interval
                            }}
                        </p>
                        @if($userSubscription->trial_ends_at && $userSubscription->trial_ends_at->isFuture())
                        <p class="text-sm text-green-600 mt-1">
                            Trial ends {{ $userSubscription->trial_ends_at->diffForHumans() }}
                        </p>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="changePlan({{ $userSubscription->plan->id === 1 ? 2 : 1 }})"
                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                            Change Plan
                        </button>
                        <button wire:click="$set('showCancelModal', true)"
                            class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100">
                            Cancel Subscription
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pricing Plans Grid -->
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Choose Your Plan</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($userPlans as $plan)
                    <div class="relative rounded-2xl border-2 p-8 
                                  {{ $selectedUserPlanId == $plan->id 
                                     ? 'border-blue-500 bg-blue-50' 
                                     : 'border-gray-200 hover:border-blue-300' }}">

                        @if($plan->is_featured)
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                            <span
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-bold px-4 py-1 rounded-full">
                                Most Popular
                            </span>
                        </div>
                        @endif

                        <div class="text-center">
                            <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h4>
                            <p class="text-gray-600 mb-4">{{ $plan->description }}</p>

                            <div class="mb-6">
                                <span class="text-4xl font-bold text-gray-900">${{ number_format($plan->price, 2)
                                    }}</span>
                                <span class="text-gray-600">/{{ $plan->interval }}</span>
                                @if($plan->trial_days > 0)
                                <p class="text-sm text-green-600 mt-2">{{ $plan->trial_days }} days free trial</p>
                                @endif
                            </div>

                            <div class="mb-8">
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model="selectedUserPlanId" value="{{ $plan->id }}"
                                        class="text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Select this plan</span>
                                </label>
                            </div>

                            @if($plan->features)
                            <div class="text-left space-y-3 mb-8">
                                {!! $plan->features !!}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Subscription Options -->
                <div class="mt-8 p-6 bg-gray-50 rounded-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Selected Plan</h4>
                            @php $selectedPlan = $userPlans->firstWhere('id', $selectedUserPlanId) @endphp
                            @if($selectedPlan)
                            <p class="text-2xl font-bold text-gray-900">{{ $selectedPlan->name }}</p>
                            <p class="text-gray-600">${{ number_format($selectedPlan->price, 2) }}/{{
                                $selectedPlan->interval }}</p>
                            @endif
                        </div>

                        <div class="flex-1 max-w-md">
                            <div class="flex gap-4 mb-4">
                                <label class="flex-1">
                                    <input type="radio" wire:model="selectedBillingCycle" value="month"
                                        class="sr-only peer">
                                    <div class="p-4 border-2 rounded-xl cursor-pointer text-center
                                                {{ $selectedBillingCycle === 'month' 
                                                   ? 'border-blue-500 bg-blue-50' 
                                                   : 'border-gray-200 hover:border-blue-300' }}">
                                        <div class="font-medium text-gray-900">Monthly</div>
                                        <div class="text-sm text-gray-600 mt-1">Billed monthly</div>
                                    </div>
                                </label>

                                <label class="flex-1">
                                    <input type="radio" wire:model="selectedBillingCycle" value="year"
                                        class="sr-only peer">
                                    <div class="p-4 border-2 rounded-xl cursor-pointer text-center
                                                {{ $selectedBillingCycle === 'year' 
                                                   ? 'border-blue-500 bg-blue-50' 
                                                   : 'border-gray-200 hover:border-blue-300' }}">
                                        <div class="font-medium text-gray-900">Yearly</div>
                                        <div class="text-sm text-gray-600 mt-1">Save 20% annually</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="text-right">
                            <button wire:click="subscribe" wire:loading.attr="disabled" wire:target="subscribe"
                                class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50">
                                <span wire:loading.remove wire:target="subscribe">
                                    Subscribe Now
                                </span>
                                <span wire:loading wire:target="subscribe">
                                    <svg class="animate-spin h-5 w-5 text-white inline mr-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Secure payment powered by Stripe</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @elseif($activeTab === 'admin_plans')
        <!-- Admin Plans Management Tab -->
        <div class="space-y-6">
            <!-- Search and Filters -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search plans..."
                                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <select wire:model.live="intervalFilter"
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Intervals</option>
                            <option value="month">Monthly</option>
                            <option value="year">Yearly</option>
                            <option value="lifetime">Lifetime</option>
                        </select>

                        <select wire:model.live="statusFilter"
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>

                        <button wire:click="createPlan"
                            class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            New Plan
                        </button>
                    </div>
                </div>

                <!-- Plans Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Interval</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stripe ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subscribers</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($plans as $plan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $plan->slug }}</div>
                                        </div>
                                        @if($plan->is_featured)
                                        <span
                                            class="ml-2 px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Featured</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($plan->price, 2) }}</div>
                                    <div class="text-sm text-gray-500">{{ strtoupper($plan->currency) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getIntervalColor($plan->interval) }}">
                                        {{ ucfirst($plan->interval) }}ly
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono truncate max-w-xs"
                                        title="{{ $plan->stripe_price_id }}">
                                        {{ $plan->stripe_price_id ? substr($plan->stripe_price_id, 0, 20) . '...' : '-'
                                        }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="togglePlanStatus({{ $plan->id }})" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                       {{ $plan->active 
                                                          ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                                          : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $plan->active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $plan->activeSubscriptions()->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <button wire:click="editPlan({{ $plan->id }})"
                                            class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="deletePlan({{ $plan->id }})"
                                            onclick="return confirm('Are you sure you want to delete this plan?')"
                                            class="text-red-600 hover:text-red-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $plans->links() }}
                </div>
            </div>
        </div>

        @elseif($activeTab === 'admin_subscriptions')
        <!-- Admin Subscriptions Management Tab -->
        <div class="space-y-6">
            <!-- Search and Filters -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search users or plans..."
                                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <select wire:model.live="statusFilter"
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="trialing">Trialing</option>
                            <option value="canceled">Canceled</option>
                            <option value="past_due">Past Due</option>
                            <option value="unpaid">Unpaid</option>
                        </select>

                        <input type="text" wire:model.live="dateRange" placeholder="Date Range"
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            x-data x-init="flatpickr($el, {mode: 'range', dateFormat: 'Y-m-d'})">

                        <button wire:click="createSubscription"
                            class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            New Subscription
                        </button>
                    </div>
                </div>

                <!-- Subscriptions Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Start Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    End Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subscriptions as $subscription)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">
                                                    {{ substr($subscription->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $subscription->user->name
                                                }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $subscription->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $subscription->plan->name }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($subscription->plan->interval) }}ly
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($subscription->plan->price, 2)
                                        }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ strtoupper($subscription->plan->currency) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $this->getStatusColor($subscription->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $subscription->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $subscription->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($subscription->ends_at)
                                    {{ $subscription->ends_at->format('M d, Y') }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <button wire:click="editSubscription({{ $subscription->id }})"
                                            class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        @if($subscription->status === 'active')
                                        <button wire:click="cancelAdminSubscription({{ $subscription->id }})"
                                            onclick="return confirm('Are you sure you want to cancel this subscription?')"
                                            class="text-red-600 hover:text-red-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
        @endif

        <!-- Plan Modal -->
        @if($showPlanModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingPlanId ? 'Edit Plan' : 'Create New Plan' }}
                    </h3>
                </div>

                <form wire:submit.prevent="savePlan" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
                                <input type="text" wire:model="planName"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., Pro Plan">
                                @error('planName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                                <input type="text" wire:model="planSlug"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., pro-monthly">
                                @error('planSlug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model="planDescription" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Describe the plan..."></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                        <input type="number" step="0.01" wire:model="planPrice"
                                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="29.99">
                                    </div>
                                    @error('planPrice') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                                    <select wire:model="planCurrency"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="usd">USD ($)</option>
                                        <option value="eur">EUR (€)</option>
                                        <option value="gbp">GBP (£)</option>
                                    </select>
                                    @error('planCurrency') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Billing Interval *</label>
                                <select wire:model="planInterval"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                    <option value="lifetime">Lifetime</option>
                                </select>
                                @error('planInterval') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Trial Days</label>
                                    <input type="number" wire:model="planTrialDays"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="0">
                                    @error('planTrialDays') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                    <input type="number" wire:model="planSortOrder"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="0">
                                    @error('planSortOrder') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" wire:model="planIsActive"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Active Plan</span>
                                </label>

                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" wire:model="planIsFeatured"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Featured Plan</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stripe Price ID</label>
                                    <input type="text" wire:model="planStripePriceId"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="price_1...">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stripe Product
                                        ID</label>
                                    <input type="text" wire:model="planStripeProductId"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="prod_...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Editor -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 border-b border-gray-300 p-2">
                                <div class="flex flex-wrap gap-1">
                                    <button type="button" onclick="formatFeatureText('strong')"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
                                        <strong>B</strong>
                                    </button>
                                    <button type="button" onclick="formatFeatureText('em')"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
                                        <em>I</em>
                                    </button>
                                    <div class="w-px h-6 bg-gray-300 mx-1"></div>
                                    <button type="button" onclick="insertFeatureList('ul')"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
                                        • List
                                    </button>
                                    <button type="button" onclick="insertFeatureList('ol')"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
                                        1. List
                                    </button>
                                    <div class="w-px h-6 bg-gray-300 mx-1"></div>
                                    <button type="button" onclick="insertFeatureCheck()"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
                                        ✓ Check
                                    </button>
                                    <button type="button" onclick="insertFeatureCross()"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100">
                                        ✗ Cross
                                    </button>
                                </div>
                            </div>
                            <textarea wire:model="planFeatures" rows="8"
                                class="w-full border-0 focus:ring-0 resize-none"
                                placeholder="Enter plan features... Use HTML formatting or plain text."></textarea>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Use HTML tags for formatting. Example: &lt;ul&gt;&lt;li&gt;Feature
                            1&lt;/li&gt;&lt;li&gt;Feature
                            2&lt;/li&gt;&lt;/ul&gt;
                        </p>
                        @error('planFeatures') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Preview -->
                    @if($planFeatures)
                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="prose max-w-none">
                            {!! $planFeatures !!}
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <button type="button" wire:click="$set('showPlanModal', false)"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                            {{ $editingPlanId ? 'Update Plan' : 'Create Plan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Subscription Modal -->
        @if($showSubscriptionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingSubscriptionId ? 'Edit Subscription' : 'Create New Subscription' }}
                    </h3>
                </div>

                <form wire:submit.prevent="saveSubscription" class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">User *</label>
                        <select wire:model="subscriptionUserId"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select User</option>
                            @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('subscriptionUserId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan *</label>
                        <select wire:model="subscriptionPlanId"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Plan</option>
                            @foreach(\App\Models\Plan::where('active', true)->get() as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (${{ $plan->price }}/{{ $plan->interval
                                }})
                            </option>
                            @endforeach
                        </select>
                        @error('subscriptionPlanId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select wire:model="subscriptionStatus"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="trialing">Trialing</option>
                            <option value="canceled">Canceled</option>
                            <option value="past_due">Past Due</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="incomplete">Incomplete</option>
                        </select>
                        @error('subscriptionStatus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trial Ends At</label>
                            <input type="date" wire:model="subscriptionTrialEndsAt"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('subscriptionTrialEndsAt') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ends At</label>
                            <input type="date" wire:model="subscriptionEndsAt"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('subscriptionEndsAt') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <button type="button" wire:click="$set('showSubscriptionModal', false)"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                            {{ $editingSubscriptionId ? 'Update Subscription' : 'Create Subscription' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Cancel Subscription Modal -->
        @if($showCancelModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Cancel Subscription</h3>
                </div>

                <div class="p-6">
                    <p class="text-gray-600 mb-6">Are you sure you want to cancel your subscription? You will lose
                        access to
                        premium features at the end of your billing cycle.</p>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="$set('showCancelModal', false)"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Keep Subscription
                        </button>
                        <button type="button" wire:click="cancelUserSubscription"
                            class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                            Yes, Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif


    </div>

    <!-- Rich Text Editor Scripts -->
    <script>
        function formatFeatureText(tag) {
    const editor = document.querySelector('[wire\\:model="planFeatures"]');
    if (!editor) return;
    
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const selectedText = editor.value.substring(start, end);
    
    let formattedText = '';
    switch(tag) {
        case 'strong': formattedText = `<strong>${selectedText}</strong>`; break;
        case 'em': formattedText = `<em>${selectedText}</em>`; break;
    }
    
    editor.value = editor.value.substring(0, start) + formattedText + editor.value.substring(end);
    editor.dispatchEvent(new Event('input'));
    editor.focus();
}

function insertFeatureList(type) {
    const editor = document.querySelector('[wire\\:model="planFeatures"]');
    if (!editor) return;
    
    const start = editor.selectionStart;
    const listHTML = type === 'ul' 
        ? '<ul>\n  <li>Feature 1</li>\n  <li>Feature 2</li>\n  <li>Feature 3</li>\n</ul>'
        : '<ol>\n  <li>Feature 1</li>\n  <li>Feature 2</li>\n  <li>Feature 3</li>\n</ol>';
    
    editor.value = editor.value.substring(0, start) + listHTML + editor.value.substring(start);
    editor.dispatchEvent(new Event('input'));
    editor.focus();
}

function insertFeatureCheck() {
    const editor = document.querySelector('[wire\\:model="planFeatures"]');
    if (!editor) return;
    
    const start = editor.selectionStart;
    const checkItem = '<li><span class="text-green-500">✓</span> Included feature</li>\n';
    
    editor.value = editor.value.substring(0, start) + checkItem + editor.value.substring(start);
    editor.dispatchEvent(new Event('input'));
    editor.focus();
}

function insertFeatureCross() {
    const editor = document.querySelector('[wire\\:model="planFeatures"]');
    if (!editor) return;
    
    const start = editor.selectionStart;
    const crossItem = '<li><span class="text-red-500">✗</span> Not included</li>\n';
    
    editor.value = editor.value.substring(0, start) + crossItem + editor.value.substring(start);
    editor.dispatchEvent(new Event('input'));
    editor.focus();
}

// Initialize Flatpickr for date inputs
document.addEventListener('livewire:init', () => {
    if (window.flatpickr) {
        flatpickr('[wire\\:model="dateRange"]', {
            mode: "range",
            dateFormat: "Y-m-d",
        });
    }
});
    </script>
</div>