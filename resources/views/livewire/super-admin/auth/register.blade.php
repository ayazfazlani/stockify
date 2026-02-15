{{-- <x-layouts.super-admin-auth> --}}
  {{-- <div class="flex min-h-screen">
    <!-- Left Section -->
    <div class="flex-1 hidden lg:block bg-gray-900">
      <div class="flex items-center justify-center h-full">
        <div class="max-w-2xl px-8">
          <div class="text-white space-y-6">
            <h1 class="text-4xl font-bold">Welcome to Stockify Admin</h1>
            <p class="text-gray-400">Register as a super admin to manage and oversee your entire Stockify system.</p>
          </div>
        </div>
      </div>
    </div> --}}

    <!-- Right Section -->
    <div class="flex-1 flex items-center justify-center p-8">
      <div class="w-full max-w-md space-y-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold">Create Super Admin Account</h2>
          <p class="mt-2 text-gray-600">Fill in your information below</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input wire:model="name" type="text" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input wire:model="email" type="email" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input wire:model="password" type="password" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input wire:model="password_confirmation" type="password" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
          </div>

          <div>
            <button type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Register
            </button>
          </div>

          <div class="text-center">
            <a href="{{ route('super-admin.login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">Already
              have an account? Sign in</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{--
</x-layouts.super-admin-auth> --}}