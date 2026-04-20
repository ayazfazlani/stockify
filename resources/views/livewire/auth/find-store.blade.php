<div class="space-y-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold font-outfit text-gray-800">Find Your Store</h2>
        <p class="text-gray-500 mt-2">Enter your email and we'll send you your store login links.</p>
    </div>

    @if($submitted)
    <div class="text-center py-6 animate-fade-in">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-paper-plane text-green-600 text-xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">Check Your Inbox</h3>
        <p class="text-gray-500 mt-2">If your email is associated with a store, we have sent you the login links.</p>
        <button wire:click="$set('submitted', false)" class="mt-6 text-primary-600 hover:text-primary-700 font-medium transition-colors">
            Try another email
        </button>
    </div>
    @else
    <form wire:submit.prevent="find" class="space-y-4">
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

        <button type="submit" 
            class="w-full gradient-bg text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            Find My Store
        </button>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Back to Login
            </a>
        </div>
    </form>
    @endif
</div>
