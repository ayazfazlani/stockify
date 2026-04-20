<div class="space-y-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold font-outfit text-gray-800">Create Your Account</h2>
        <p class="text-gray-500 mt-2">Start your 14-day free trial today</p>
    </div>

    @if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 animate-fade-in-down">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <form wire:submit.prevent="register" class="space-y-4">
        <!-- Subdomain -->
        <div>
            <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-1">Store Identifier (Subdomain)</label>
            <div class="relative flex">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-globe text-gray-400"></i>
                </div>
                <input wire:model.live="subdomain" type="text" id="subdomain" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="my-company">
                <span class="inline-flex items-center px-4 bg-gray-50 border border-l-0 border-gray-200 rounded-r-xl text-gray-500 text-sm font-medium">
                    .{{ config('tenancy.central_domains')[0] }}
                </span>
            </div>
            @error('subdomain') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-gray-400">This will be your unique store URL</p>
        </div>

        <!-- Company Name -->
        <div>
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-building text-gray-400"></i>
                </div>
                <input wire:model.live="company_name" type="text" id="company_name" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="Acme Trading Ltd">
            </div>
            @error('company_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input wire:model.live="name" type="text" id="name" 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="John Doe">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input wire:model.live="email" type="email" id="email" 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="name@company.com">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input wire:model.live="password" type="password" id="password" 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="••••••••">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input wire:model.live="password_confirmation" type="password" id="password_confirmation" 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="••••••••">
            </div>
        </div>

        <div class="flex items-start py-2">
            <input type="checkbox" id="terms" required class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 transition-all cursor-pointer">
            <label for="terms" class="ml-2 text-sm text-gray-600 leading-tight">
                I agree to the <a href="#" class="text-primary-600 hover:underline">Terms of Service</a> and <a href="#" class="text-primary-600 hover:underline">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" 
            class="w-full gradient-bg text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            Create My Store
        </button>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-bold transition-colors">Sign In</a>
            </p>
        </div>
    </form>
</div>