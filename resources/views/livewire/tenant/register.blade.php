<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Register Your Company</h2>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit="register">
        <!-- Subdomain (tenant identifier) -->
        <div class="mb-4">
            <label class="block text-gray-700">Subdomain</label>
            <div class="flex">
                <input wire:model.live="subdomain" type="text"
                    class="flex-1 border rounded-l px-3 py-2 focus:outline-none focus:ring" placeholder="yourcompany">
                <span class="inline-flex items-center px-3 bg-gray-200 border border-l-0 rounded-r text-gray-700">.{{
                    config('tenancy.central_domains')[0] }}</span>
            </div>
            @error('subdomain') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Company Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Company Name</label>
            <input wire:model.live="company_name" type="text"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Acme Trading Ltd">
            @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Your Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Your Full Name</label>
            <input wire:model.live="name" type="text"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input wire:model.live="email" type="email"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input wire:model.live="password" type="password"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label class="block text-gray-700">Confirm Password</label>
            <input wire:model.live="password_confirmation" type="password"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Create Company Account
        </button>
    </form>
</div>