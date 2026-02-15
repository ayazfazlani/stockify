<x-layouts.super-admin-auth>
  <div class="flex min-h-screen">
    <!-- Left Section -->
    <div class="flex-1 hidden lg:block bg-gray-900">
      <div class="flex items-center justify-center h-full">
        <div class="max-w-2xl px-8">
          <div class="text-white space-y-6">
            <h1 class="text-4xl font-bold">Welcome Back to Stockify Admin</h1>
            <p class="text-gray-400">Reset your password to regain access to your super admin account.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Section -->
    <div class="flex-1 flex items-center justify-center p-8">
      <div class="w-full max-w-md space-y-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold">Reset Password</h2>
          <p class="mt-2 text-gray-600">Enter your new password below</p>
        </div>

        <form wire:submit.prevent="resetPassword" class="space-y-6">
          <input type="hidden" wire:model="token">

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input wire:model="email" type="email" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input wire:model="password" type="password" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New
              Password</label>
            <input wire:model="password_confirmation" type="password" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
          </div>

          <div>
            <button type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Reset Password
            </button>
          </div>

          <div class="text-center">
            <a href="{{ route('super-admin.login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">Back to
              login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-layouts.super-admin-auth>