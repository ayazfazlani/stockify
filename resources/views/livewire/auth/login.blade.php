<div class="space-y-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold font-outfit text-gray-800">Welcome Back</h2>
        <p class="text-gray-500 mt-2">Please enter your details to sign in</p>
    </div>

    @if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 animate-fade-in-down">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <form wire:submit.prevent="login" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input wire:model="email" type="email" id="email" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="name@company.com" required>
            </div>
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input wire:model="password" type="password" id="password" 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                    placeholder="••••••••" required>
            </div>
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" id="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 transition-all cursor-pointer">
            <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer">Remember me</label>
        </div>

        <button type="submit" 
            class="w-full gradient-bg text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            Sign In
        </button>

        <div class="relative py-4 text-center">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <span class="relative px-4 text-xs text-gray-400 bg-white uppercase tracking-wider font-medium">Or</span>
        </div>

        <div class="text-center space-y-4">
            <p class="text-sm text-gray-500">
                Want to buy items? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-bold transition-colors">Create Client Account</a>
            </p>
            <p class="text-sm text-gray-500">
                Are you a store owner? 
                <a href="{{ route('tenant.register.post') }}" class="text-primary-600 hover:text-primary-700 font-bold transition-colors">Start Free Trial</a>
            </p>
            <p class="text-xs text-gray-400">
                Forgot your store? 
                <a href="{{ route('find-store') }}" class="text-gray-500 hover:text-primary-600 font-medium underline transition-colors">Find it by email</a>
            </p>
        </div>
    </form>
</div>